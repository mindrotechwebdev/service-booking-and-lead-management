<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../services.php?error=' . urlencode('Invalid request method.'));
    exit;
}

$name = trim((string) ($_POST['customer_name'] ?? ''));
$email = trim((string) ($_POST['customer_email'] ?? ''));
$phone = trim((string) ($_POST['customer_phone'] ?? ''));
$serviceName = trim((string) ($_POST['service_name'] ?? ''));
$bookingDate = trim((string) ($_POST['booking_date'] ?? ''));
$bookingTime = trim((string) ($_POST['booking_time'] ?? ''));
$paymentMode = trim((string) ($_POST['payment_mode'] ?? ''));
$couponCode = trim((string) ($_POST['coupon_code'] ?? ''));
$address = trim((string) ($_POST['address'] ?? ''));
$notes = trim((string) ($_POST['notes'] ?? ''));

if (
    $name === '' ||
    $email === '' ||
    $phone === '' ||
    $serviceName === '' ||
    $bookingDate === '' ||
    $bookingTime === '' ||
    $paymentMode === '' ||
    $address === ''
) {
    header('Location: ../services.php?error=' . urlencode('Please fill all required booking fields.') . '#booking-form');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../services.php?error=' . urlencode('Please enter a valid email address.') . '#booking-form');
    exit;
}

try {
    $pdo = db();

    $serviceStmt = $pdo->prepare('SELECT id, price FROM services WHERE name = :name LIMIT 1');
    $serviceStmt->execute(['name' => $serviceName]);
    $service = $serviceStmt->fetch();

    if (!$service) {
        $insertService = $pdo->prepare(
            'INSERT INTO services (name, description, price, duration_minutes, is_active)
             VALUES (:name, :description, :price, :duration_minutes, :is_active)'
        );

        $insertService->execute([
            'name' => $serviceName,
            'description' => 'Auto-created from service catalog booking flow.',
            'price' => 0,
            'duration_minutes' => 60,
            'is_active' => 1,
        ]);

        $serviceId = (int) $pdo->lastInsertId();
    } else {
        $serviceId = (int) $service['id'];
    }

    $compiledNotes = $notes;
    $flowMeta = 'Payment Mode: ' . $paymentMode;

    if ($couponCode !== '') {
        $flowMeta .= ' | Coupon: ' . $couponCode;
    }

    $compiledNotes = $compiledNotes !== '' ? $compiledNotes . PHP_EOL . $flowMeta : $flowMeta;

    $insert = $pdo->prepare(
        'INSERT INTO bookings (service_id, customer_name, customer_email, customer_phone, booking_date, booking_time, address, notes, status)
         VALUES (:service_id, :customer_name, :customer_email, :customer_phone, :booking_date, :booking_time, :address, :notes, :status)'
    );

    $insert->execute([
        'service_id' => $serviceId,
        'customer_name' => $name,
        'customer_email' => $email,
        'customer_phone' => $phone,
        'booking_date' => $bookingDate,
        'booking_time' => $bookingTime,
        'address' => $address,
        'notes' => $compiledNotes,
        'status' => 'pending',
    ]);

    $bookingId = (int) $pdo->lastInsertId();
    $bookingCode = 'SB-' . str_pad((string) $bookingId, 5, '0', STR_PAD_LEFT);
    $successMessage = 'Booking submitted successfully. Booking ID: ' . $bookingCode . '. Confirmation will be shared shortly.';

    header('Location: ../services.php?service=' . urlencode($serviceName) . '&success=' . urlencode($successMessage) . '#booking-form');
    exit;
} catch (Throwable $e) {
    header('Location: ../services.php?service=' . urlencode($serviceName) . '&error=' . urlencode('Failed to save booking. Please start MySQL in XAMPP and verify the existing database connection.') . '#booking-form');
    exit;
}
