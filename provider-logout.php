<?php

declare(strict_types=1);
session_start();

unset(
    $_SESSION['provider_id'],
    $_SESSION['provider_name'],
    $_SESSION['provider_email'],
    $_SESSION['provider_service'],
    $_SESSION['provider_city']
);

header('Location: provider-login.php');
exit;
