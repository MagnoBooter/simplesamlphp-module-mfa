<?php

/**
 * This "controller" (per MVC) must be called with the following query string
 * parameters:
 * - StateId
 * - mfaId
 */

use sspmod_mfa_Auth_Process_Mfa as Mfa;
use Sil\PhpEnv\Env;
use Sil\Psr3Adapters\Psr3SamlLogger;

$stateId = filter_input(INPUT_POST, 'StateId') ?? null;
$stateId = $stateId ?? filter_input(INPUT_GET, 'StateId');
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_PROMPT);
$mfaOptions = $state['mfaOptions'] ?? [];

$logger = new Psr3SamlLogger();

/*
 * Check for "Remember me for 30 days" cookies and if valid bypass mfa prompt
 */
$cookieHash = filter_input(INPUT_COOKIE, 'c1') ?? ''; // hashed string
$expireDate = filter_input(INPUT_COOKIE, 'c2') ?? 0;  // expiration timestamp
if (Mfa::isRememberMeCookieValid(base64_decode($cookieHash), $expireDate, $mfaOptions, $state)) {
    $logger->warning(json_encode([
        'event' => 'MFA skipped due to valid remember-me cookie',
        'employeeId' => $state['employeeId'],
    ]));
    
    //unset($state['Attributes']['mfa']);
    // This condition should never return
    SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
    throw new \Exception('Failed to resume processing auth proc chain.');
}

$mfaId = filter_input(INPUT_GET, 'mfaId');

if (empty($mfaId)) {
    $logger->critical(json_encode([
        'event' => 'MFA ID missing in URL. Choosing one and doing a redirect.',
        'employeeId' => $state['employeeId'],
    ]));
    
    // Pick an MFA ID and do a redirect to put that into the URL.
    $mfaOption = Mfa::getMfaOptionToUse($mfaOptions);
    $moduleUrl = SimpleSAML_Module::getModuleURL('mfa/prompt-for-mfa.php', [
        'mfaId' => $mfaOption['id'],
        'StateId' => $stateId,
    ]);
    SimpleSAML_Utilities::redirect($moduleUrl);
    return;
}
$mfaOption = Mfa::getMfaOptionById($mfaOptions, $mfaId);

// If the user has submitted their MFA value...
if (filter_has_var(INPUT_POST, 'submitMfa')) {
    $mfaSubmission = filter_input(INPUT_POST, 'mfaSubmission');
    if (substr($mfaSubmission, 0, 1) == '{') {
        $mfaSubmission = json_decode($mfaSubmission, true);
    }

    $rememberMe = filter_input(INPUT_POST, 'rememberMe') ?? false;
    
    // NOTE: This will only return if validation fails.
    $errorMessage = Mfa::validateMfaSubmission(
        $mfaId,
        $state['employeeId'],
        $mfaSubmission,
        $state,
        $rememberMe,
        $logger,
        $mfaOption['type']
    );
    
    $logger->warning(json_encode([
        'event' => 'MFA validation result: failed',
        'employeeId' => $state['employeeId'],
        'mfaType' => $mfaOption['type'],
        'error' => $errorMessage,
    ]));
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$mfaTemplateToUse = Mfa::getTemplateFor($mfaOption['type']);

$t = new SimpleSAML_XHTML_Template($globalConfig, $mfaTemplateToUse);
$t->data['formTarget'] = SimpleSAML_Module::getModuleURL('mfa/prompt-for-mfa.php');
$t->data['formData'] = ['StateId' => $stateId, 'mfaId' => $mfaId];
$t->data['errorMessage'] = $errorMessage ?? null;
$t->data['mfaOption'] = $mfaOption;
$t->data['mfaOptions'] = $mfaOptions;
$t->data['stateId'] = $stateId;
$t->show();

$logger->info(json_encode([
    'event' => 'Prompted user for MFA',
    'employeeId' => $state['employeeId'],
    'mfaType' => $mfaOption['type'],
]));
