<?php

declare(strict_types=1);
session_start();

require_once __DIR__ . '/backend/config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
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
            $stmt = db()->prepare('SELECT id, full_name, email, password_hash, role FROM admins WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, (string) $admin['password_hash'])) {
                $_SESSION['admin_id'] = (int) $admin['id'];
                $_SESSION['admin_name'] = (string) $admin['full_name'];
                $_SESSION['admin_email'] = (string) $admin['email'];
                $_SESSION['admin_role'] = (string) $admin['role'];

                header('Location: dashboard.php');
                exit;
            }

            $error = 'Invalid admin credentials.';
        } catch (Throwable $e) {
            $error = 'Database not connected. Please start MySQL in XAMPP and verify the existing database connection.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | ServiceBook</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <main class="auth-shell">
    <div class="auth-card">
      <section class="auth-side auth-admin">
        <span class="eyebrow"><i class="bi bi-shield-lock"></i> Admin flow</span>
        <h1>Control services, bookings, leads, and platform operations.</h1>
        <p>The admin entry point now reflects the flow chart with access to the dashboard, service management, booking oversight, users, providers, and reports.</p>
        <ul class="auth-points">
          <li><i class="bi bi-check2-circle"></i><span>Secure login for admin-only access.</span></li>
          <li><i class="bi bi-check2-circle"></i><span>Visibility into booking volume and lead pipeline.</span></li>
          <li><i class="bi bi-check2-circle"></i><span>One place to manage the service platform.</span></li>
        </ul>
      </section>

      <section class="auth-form">
        <h2>Admin Login</h2>
        <p>Sign in to access the admin operations dashboard.</p>

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
          <button class="btn-booking" type="submit">Login as Admin</button>
        </form>

        <div class="info-strip">
          <span class="info-chip">Demo Email: admin@servicebook.local</span>
          <span class="info-chip">Demo Password: admin123</span>
        </div>

        <hr />
        <a class="btn btn-link px-0" href="index.php">Back to Home</a>
      </section>
    </div>
  </main>
</body>
</html>
