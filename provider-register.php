<?php

declare(strict_types=1);
session_start();

require_once __DIR__ . '/backend/config/database.php';

if (isset($_SESSION['provider_id'])) {
    header('Location: provider-dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $primaryService = trim((string) ($_POST['primary_service'] ?? ''));
    $city = trim((string) ($_POST['city'] ?? ''));
    $experienceYears = (int) ($_POST['experience_years'] ?? 0);
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($fullName === '' || $email === '' || $phone === '' || $primaryService === '' || $city === '' || $password === '' || $confirmPassword === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif ($experienceYears < 0) {
        $error = 'Experience years cannot be negative.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo = db();
            $check = $pdo->prepare('SELECT id FROM service_providers WHERE email = :email LIMIT 1');
            $check->execute(['email' => $email]);

            if ($check->fetch()) {
                $error = 'This provider email is already registered.';
            } else {
                $insert = $pdo->prepare(
                    'INSERT INTO service_providers
                    (full_name, email, phone, primary_service, city, experience_years, password_hash, status)
                    VALUES
                    (:full_name, :email, :phone, :primary_service, :city, :experience_years, :password_hash, :status)'
                );

                $insert->execute([
                    'full_name' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'primary_service' => $primaryService,
                    'city' => $city,
                    'experience_years' => $experienceYears,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'status' => 'active',
                ]);

                header('Location: provider-login.php');
                exit;
            }
        } catch (Throwable $e) {
            $error = 'Provider registration could not connect. Please start MySQL in XAMPP and verify the existing database connection.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Provider Register | ServiceBook</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <main class="auth-shell">
    <div class="auth-card">
      <section class="auth-side auth-provider">
        <span class="eyebrow"><i class="bi bi-person-plus"></i> Join as provider</span>
        <h1>Register your service business on the platform.</h1>
        <p>Providers can sign up, receive new booking requests, manage jobs, and review their work history from one dashboard.</p>
        <ul class="auth-points">
          <li><i class="bi bi-check2-circle"></i><span>Set your primary service specialization.</span></li>
          <li><i class="bi bi-check2-circle"></i><span>Track jobs in progress and completion updates.</span></li>
          <li><i class="bi bi-check2-circle"></i><span>Build a structured provider workflow like the reference chart.</span></li>
        </ul>
      </section>

      <section class="auth-form">
        <h2>Provider Registration</h2>
        <p>Create a partner account to access requests and earnings history.</p>

        <?php if ($error !== ''): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" name="full_name" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Primary Service</label>
              <input type="text" name="primary_service" class="form-control" placeholder="AC Repair, Plumbing, Cleaning..." required />
            </div>
            <div class="col-md-6">
              <label class="form-label">City</label>
              <input type="text" name="city" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Experience Years</label>
              <input type="number" name="experience_years" class="form-control" min="0" value="1" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control" required />
            </div>
            <div class="col-12">
              <button type="submit" class="btn-booking">Create Provider Account</button>
            </div>
          </div>
        </form>

        <hr />
        <a class="btn btn-outline-primary w-100 mb-2" href="provider-login.php">Already registered? Provider Login</a>
        <a class="btn btn-link px-0" href="index.php">Back to Home</a>
      </section>
    </div>
  </main>
</body>
</html>
