<?php
/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */

use Sil\PhpEnv\Env;

$idpHostAndPort = 'mfaidp' . Env::get('TEST_IDP_PORT');

/*
 * Guest IdP. allows users to sign up and register. Great for testing!
 */
$metadata['http://' . $idpHostAndPort] = [
	'SingleSignOnService'  => 'http://' . $idpHostAndPort . '/saml2/idp/SSOService.php',
	'SingleLogoutService'  => 'http://' . $idpHostAndPort . '/saml2/idp/SingleLogoutService.php',
	'certData' =>'MIIDXTCCAkWgAwIBAgIJAM9xjgDMzRW7MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNVBAYTAlVTMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwHhcNMTcwNjA2MTQzNTQzWhcNMjcwNjA2MTQzNTQzWjBFMQswCQYDVQQGEwJVUzETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoGg03EBTojHpWhUJgHcYqzeVLgAT/Xm2ksaHHf6SMhuZoSMf7auB/C09UP/ml2M/Kv0aVAAbYPmaOmuKhidO5RIKpSw+wltLoP5Ib/x5FWHpd8qqGPvafH2C6ofJkOt1aGjQ3rH0yRDp9l8sTjBfgFQdzRD2pfbM5Ux5o5eSp4XBM8VeeMJRiSb9Yw4cRPTiS5PSeixtSa3ppix7LjF43M6yKKkpodCO26fEX2rppAt9qTs6OjaysZTgVVNq3k89QWz/WPNTSJ+J2HZH9DwWjeEkiKvPE75SsdGWvL7s0dSbrOWJ0pcg+NH6B4PwhyEwvtCkcxr5yBwFYIzXjKP0twIDAQABo1AwTjAdBgNVHQ4EFgQUDgbZG2ts36J3TgRIlnrMS1Pn3j0wHwYDVR0jBBgwFoAUDgbZG2ts36J3TgRIlnrMS1Pn3j0wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAZIeI5Qe5Np6RFQQm2qMa8XwVMpitbVYCIux6STAw1s5wvSudmuJwpJBDCt0f/fb/xoUChQdmlYZ8rGVxmmTQTl2RW6W++8jaUfiUqf0ACLug0gOV5nLj+MPV7Qj3xzVTJRHR+xHtKgMX/ArCasAv+nCBRXVXjQmZw6Ub9k5aHHURDjLYq+KtO1DuC6HN08dkQhfeb3IJCCKNTu/i0ZBVvEEifJYKwq+5tLrMhVKQYoAr1JN6DCGJ2qAuyxNX9Ab7ngWUjtXG/Wxe75dA804S42hR1GPl2Qshhy5ZnjfswwDPWnCyJg3QsxGKa24wTQi+KOt83ev+Zrur9umBdaJLqA==',
];

