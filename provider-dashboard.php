<?php

declare(strict_types=1);
session_start();

if (!isset($_SESSION['provider_id'])) {
    header('Location: provider-login.php');
    exit;
}

require_once __DIR__ . '/backend/config/database.php';

$stats = [
    'new_requests' => 0,
    'accepted_jobs' => 0,
    'in_progress' => 0,
    'completed' => 0,
];
$bookings = [];
$provider = [];
$error = '';

try {
    $pdo = db();
    $providerStmt = $pdo->prepare(
        'SELECT full_name, email, phone, primary_service, city, experience_years
         FROM service_providers
         WHERE id = :id
         LIMIT 1'
    );
    $providerStmt->execute(['id' => (int) $_SESSION['provider_id']]);
    $provider = $providerStmt->fetch() ?: [];

    $stats['new_requests'] = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
    $stats['accepted_jobs'] = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn();
    $stats['in_progress'] = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn();
    $stats['completed'] = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'completed'")->fetchColumn();

    $bookings = $pdo->query(
        'SELECT b.customer_name, b.customer_phone, b.booking_date, b.booking_time, b.status, s.name AS service_name, b.address
         FROM bookings b
         INNER JOIN services s ON s.id = b.service_id
         ORDER BY b.created_at DESC
         LIMIT 10'
    )->fetchAll();
} catch (Throwable $e) {
    $error = 'Could not load provider dashboard. Please start MySQL in XAMPP and verify the existing database connection.';
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
  <title>Provider Dashboard | ServiceBook</title>
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
            <a class="btn btn-outline-primary btn-sm" href="services.php">View Services</a>
            <a class="btn btn-outline-primary btn-sm" href="dashboard.php">Admin Dashboard</a>
            <a class="btn btn-primary btn-sm" href="provider-logout.php">Logout</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container py-4">
      <div class="hero-card mb-4">
        <div class="row align-items-center g-4">
          <div class="col-lg-8">
            <span class="eyebrow"><i class="bi bi-person-workspace"></i> Provider dashboard</span>
            <h1 class="hero-title" style="font-size: clamp(2.5rem, 4vw, 4rem);">Handle New Booking Requests From One Workspace</h1>
            <p class="hero-copy mb-0">
              Welcome, <?php echo htmlspecialchars((string) ($_SESSION['provider_name'] ?? 'Provider')); ?>.
              This area follows the provider flow in the reference image: request review, accept or reject, service progress, completion, and history.
            </p>
          </div>
          <div class="col-lg-4">
            <div class="hero-side-panel">
              <h4 class="mb-3">Provider details</h4>
              <ul class="hero-bullet-list mt-0">
                <li><i class="bi bi-tools"></i><span><?php echo htmlspecialchars((string) ($provider['primary_service'] ?? ($_SESSION['provider_service'] ?? 'Primary service'))); ?></span></li>
                <li><i class="bi bi-geo-alt"></i><span><?php echo htmlspecialchars((string) ($provider['city'] ?? ($_SESSION['provider_city'] ?? 'City'))); ?></span></li>
                <li><i class="bi bi-briefcase"></i><span><?php echo htmlspecialchars((string) ($provider['experience_years'] ?? 0)); ?> years experience</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <section class="dashboard-grid mb-4">
        <div class="metric-card"><span>New Booking Requests</span><strong><?php echo $stats['new_requests']; ?></strong></div>
        <div class="metric-card"><span>Accepted Jobs</span><strong><?php echo $stats['accepted_jobs']; ?></strong></div>
        <div class="metric-card"><span>Service In Progress</span><strong><?php echo $stats['in_progress']; ?></strong></div>
        <div class="metric-card"><span>Completed Jobs</span><strong><?php echo $stats['completed']; ?></strong></div>
      </section>

      <div class="row g-4">
        <div class="col-lg-8">
          <section class="soft-panel h-100">
            <h3 class="mb-3">Latest Booking Requests</h3>
            <div class="table-shell table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Schedule</th>
                    <th>Address</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($bookings) === 0): ?>
                    <tr><td colspan="5" class="text-muted">No booking requests available yet.</td></tr>
                  <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                      <tr>
                        <td>
                          <strong><?php echo htmlspecialchars((string) $booking['customer_name']); ?></strong><br />
                          <span class="text-muted small"><?php echo htmlspecialchars((string) $booking['customer_phone']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars((string) $booking['service_name']); ?></td>
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
            <h3 class="mb-3">Provider Flow Checklist</h3>
            <div class="flow-stack">
              <div class="flow-node provider-node"><i class="bi bi-envelope-open"></i><div><strong>P1. New Booking Request</strong><span>Open the queue and review customer details.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-check2-square"></i><div><strong>P2. Accept / Reject</strong><span>Respond to assignments based on availability.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-hourglass-split"></i><div><strong>P3. Service In Progress</strong><span>Track work after job acceptance.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-patch-check"></i><div><strong>P4. Mark Completed</strong><span>Close completed visits and update status.</span></div></div>
              <div class="flow-node provider-node"><i class="bi bi-cash-stack"></i><div><strong>P5. Earnings & History</strong><span>Review completed service history and payout visibility.</span></div></div>
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
