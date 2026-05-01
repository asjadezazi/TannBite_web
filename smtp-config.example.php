<?php
/**
 * Deployment: copy this file to smtp-config.php on the server (same folder as submit-form.php)
 * and set your mailbox password. Do not commit smtp-config.php.
 */
declare(strict_types=1);

return [
    'host' => 'smtp.hostinger.com',
    'port' => 465,
    'username' => 'contact@tapnbite.com',
    'password' => 'YOUR_MAILBOX_PASSWORD',
    // Usually ssl on 465; use tls + port 587 if your provider requires it
    'encryption' => 'ssl',
];
