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
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } else {
        try {
            $stmt = db()->prepare(
                'SELECT id, full_name, email, primary_service, city, password_hash
                 FROM service_providers
                 WHERE email = :email AND status = :status
                 LIMIT 1'
            );
            $stmt->execute([
                'email' => $email,
                'status' => 'active',
            ]);
            $provider = $stmt->fetch();

            if ($provider && password_verify($password, (string) $provider['password_hash'])) {
                $_SESSION['provider_id'] = (int) $provider['id'];
                $_SESSION['provider_name'] = (string) $provider['full_name'];
                $_SESSION['provider_email'] = (string) $provider['email'];
                $_SESSION['provider_service'] = (string) $provider['primary_service'];
                $_SESSION['provider_city'] = (string) $provider['city'];

                header('Location: provider-dashboard.php');
                exit;
            }

            $error = 'Invalid provider credentials.';
        } catch (Throwable $e) {
            $error = 'Provider module could not connect. Please start MySQL in XAMPP and verify the existing database connection.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Provider Login | ServiceBook</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <main class="auth-shell">
    <div class="auth-card">
      <section class="auth-side auth-provider">
        <span class="eyebrow"><i class="bi bi-tools"></i> Service provider flow</span>
        <h1>Accept jobs. Track work. Grow earnings.</h1>
        <p>Provider login gives access to booking requests, service progress, completion status, and earnings history.</p>
        <ul class="auth-points">
          <li><i class="bi bi-check2-circle"></i><span>Login or register as a verified service partner.</span></li>
          <li><i class="bi bi-check2-circle"></i><span>Review incoming booking requests from the platform.</span></li>
          <li><i class="bi bi-check2-circle"></i><span>Track progress from accepted job to completed service.</span></li>
        </ul>
      </section>

      <section class="auth-form">
        <h2>Provider Login</h2>
        <p>Use your partner account to access the provider dashboard.</p>

        <?php if ($error !== ''): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required />
          </div>
          <button class="btn-booking" type="submit">Login as Provider</button>
        </form>

        <div class="info-strip">
          <span class="info-chip">Demo Email: provider@servicebook.local</span>
          <span class="info-chip">Demo Password: admin123</span>
        </div>

        <hr />
        <a class="btn btn-outline-primary w-100 mb-2" href="provider-register.php">Create Provider Account</a>
        <a class="btn btn-link px-0" href="index.php">Back to Home</a>
      </section>
    </div>
  </main>
</body>
</html>
