<?php

declare(strict_types=1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/backend/config/database.php';

$userBookings = [];
$bookingCounts = ['pending' => 0, 'confirmed' => 0, 'completed' => 0];
$error = '';

try {
    $stmt = db()->prepare(
        'SELECT b.customer_name, b.customer_phone, b.booking_date, b.booking_time, b.status, s.name AS service_name, b.address
         FROM bookings b
         INNER JOIN services s ON s.id = b.service_id
         WHERE b.customer_email = :email
         ORDER BY b.created_at DESC
         LIMIT 10'
    );
    $stmt->execute(['email' => (string) $_SESSION['user_email']]);
    $userBookings = $stmt->fetchAll();

    foreach ($userBookings as $booking) {
        $status = (string) $booking['status'];
        if (isset($bookingCounts[$status])) {
            $bookingCounts[$status]++;
        }
    }
} catch (Throwable $e) {
    $error = 'Could not load your data. Please start MySQL in XAMPP and verify the existing database connection.';
}

function statusBadgeClass(string $status): string
{
    return match ($status) {
        'pending' => 'badge-pending',
        'confirmed' => 'badge-confirmed',
        'completed' => 'badge-completed',
        'cancelled' => 'badge-cancelled',
        default => 'badge-new',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard | ServiceBook</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="page-shell">
    <header class="top-nav sticky-top">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="index.php">
            <span class="logo-mark"><i class="bi bi-grid-1x2-fill"></i></span>
            ServiceBook
          </a>
          <div class="ms-auto d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-success btn-sm" href="services.php">Book Service</a>
            <a class="btn btn-outline-primary btn-sm" href="index.php">Home</a>
            <a class="btn btn-primary btn-sm" href="user-logout.php">Logout</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container py-4">
      <div class="hero-card mb-4" style="background: linear-gradient(130deg, rgba(17, 94, 89, 0.98) 0%, rgba(22, 163, 136, 0.94) 62%, rgba(91, 214, 194, 0.9) 100%);">
        <div class="row align-items-center g-4">
          <div class="col-lg-8">
            <span class="eyebrow"><i class="bi bi-person-circle"></i> User dashboard</span>
            <h1 class="hero-title" style="font-size: clamp(2.4rem, 4vw, 4rem);">Track Your Bookings And Move Through The Service Journey</h1>
            <p class="hero-copy mb-0">
              Welcome, <?php echo htmlspecialchars((string) $_SESSION['user_name']); ?>.
              Your dashboard reflects the flow chart after login: browse, book, confirm, and track status updates.
            </p>
          </div>
          <div class="col-lg-4">
            <div class="hero-side-panel">
              <h4 class="mb-3">Quick snapshot</h4>
              <ul class="hero-bullet-list mt-0">
                <li><i class="bi bi-hourglass-split"></i><span><?php echo $bookingCounts['pending']; ?> pending bookings</span></li>
                <li><i class="bi bi-patch-check"></i><span><?php echo $bookingCounts['confirmed']; ?> confirmed bookings</span></li>
                <li><i class="bi bi-stars"></i><span><?php echo $bookingCounts['completed']; ?> completed services</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <section class="dashboard-grid mb-4">
        <div class="metric-card"><span>Total Recent Bookings</span><strong><?php echo count($userBookings); ?></strong></div>
        <div class="metric-card"><span>Pending</span><strong><?php echo $bookingCounts['pending']; ?></strong></div>
        <div class="metric-card"><span>Confirmed</span><strong><?php echo $bookingCounts['confirmed']; ?></strong></div>
        <div class="metric-card"><span>Completed</span><strong><?php echo $bookingCounts['completed']; ?></strong></div>
      </section>

      <div class="row g-4">
        <div class="col-lg-8">
          <section class="soft-panel h-100">
            <h3 class="mb-3">Your Recent Bookings</h3>
            <div class="table-shell table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Service</th>
                    <th>Schedule</th>
                    <th>Address</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($userBookings) === 0): ?>
                    <tr><td colspan="4" class="text-muted">No bookings yet. Go to the services page and place your first booking.</td></tr>
                  <?php else: ?>
                    <?php foreach ($userBookings as $booking): ?>
                      <tr>
                        <td>
                          <strong><?php echo htmlspecialchars((string) $booking['service_name']); ?></strong><br />
                          <span class="text-muted small"><?php echo htmlspecialchars((string) $booking['customer_phone']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars((string) $booking['booking_date']); ?><br /><span class="text-muted small"><?php echo htmlspecialchars((string) $booking['booking_time']); ?></span></td>
                        <td><?php echo htmlspecialchars((string) $booking['address']); ?></td>
                        <td><span class="badge-soft <?php echo statusBadgeClass((string) $booking['status']); ?>"><?php echo htmlspecialchars((string) $booking['status']); ?></span></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div class="col-lg-4">
          <section class="soft-panel h-100">
            <h3 class="mb-3">Your Flow Steps</h3>
            <div class="flow-stack">
              <div class="flow-node provider-node"><i class="bi bi-grid"></i><div><strong>Browse Services</strong><span>Explore the 50+ service catalog.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-calendar2-week"></i><div><strong>Choose Slot</strong><span>Book with date, time, address, and payment preference.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-bell"></i><div><strong>Get Updates</strong><span>Track pending, confirmed, and completed bookings.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-arrow-repeat"></i><div><strong>Rebook Anytime</strong><span>Use the service board for your next request.</span></div></div>
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
