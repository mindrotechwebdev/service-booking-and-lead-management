<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?error=' . urlencode('Invalid request method.'));
    exit;
}

$fullName = trim((string) ($_POST['full_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$interestedService = trim((string) ($_POST['interested_service'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($fullName === '' || $phone === '') {
    header('Location: ../index.php?error=' . urlencode('Name and phone are required for lead submission.'));
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?error=' . urlencode('Please provide a valid email address.'));
    exit;
}

try {
    $pdo = db();

    $insert = $pdo->prepare(
        'INSERT INTO leads (full_name, email, phone, interested_service, source, message)
         VALUES (:full_name, :email, :phone, :interested_service, :source, :message)'
    );

    $insert->execute([
        'full_name' => $fullName,
        'email' => $email !== '' ? $email : null,
        'phone' => $phone,
        'interested_service' => $interestedService !== '' ? $interestedService : null,
        'source' => 'website',
        'message' => $message !== '' ? $message : null,
    ]);

    header('Location: ../index.php?success=' . urlencode('Lead submitted successfully. We will call you shortly.'));
    exit;
} catch (Throwable $e) {
    header('Location: ../index.php?error=' . urlencode('Lead save failed. Please start MySQL in XAMPP and verify the existing database connection.'));
    exit;
}
