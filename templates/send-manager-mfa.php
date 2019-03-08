<?php
$this->data['header'] = 'Send manager backup code';
$this->includeAtTemplateBase('includes/header.php');

?>
<p>
  You can send a backup code to your manager to serve as an
  additional 2-Factor Authentication option.
  The email address on file (masked for privacy) is <?= $this->data['managerEmail'] ?>
</p>
<form method="post">
    <button name="send" style="padding: 4px 8px;">
        Send
    </button>
</form>
<?php
$this->includeAtTemplateBase('includes/footer.php');
