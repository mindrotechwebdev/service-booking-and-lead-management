<?php

declare(strict_types=1);
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}

require_once __DIR__ . '/backend/config/database.php';

$stats = ['services' => 0, 'bookings' => 0, 'leads' => 0, 'pending_bookings' => 0, 'new_leads' => 0, 'providers' => 0];
$bookings = [];
$leads = [];
$error = '';

try {
    $pdo = db();
    $stats['services'] = (int) $pdo->query('SELECT COUNT(*) FROM services WHERE is_active = 1')->fetchColumn();
    $stats['bookings'] = (int) $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
    $stats['pending_bookings'] = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
    $stats['leads'] = (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn();
    $stats['new_leads'] = (int) $pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn();

    try {
        $stats['providers'] = (int) $pdo->query("SELECT COUNT(*) FROM service_providers WHERE status = 'active'")->fetchColumn();
    } catch (Throwable $e) {
        $stats['providers'] = 0;
    }

    $bookings = $pdo->query(
        'SELECT b.customer_name, b.customer_phone, b.booking_date, b.booking_time, b.status, s.name AS service_name
         FROM bookings b
         INNER JOIN services s ON s.id = b.service_id
         ORDER BY b.created_at DESC
         LIMIT 10'
    )->fetchAll();

    $leads = $pdo->query(
        'SELECT full_name, phone, interested_service, source, status, created_at
         FROM leads
         ORDER BY created_at DESC
         LIMIT 10'
    )->fetchAll();
} catch (Throwable $e) {
    $error = 'Could not load dashboard data. Please start MySQL in XAMPP and verify the existing database connection.';
}

function statusBadgeClass(string $status): string
{
    return match ($status) {
        'pending' => 'badge-pending',
        'confirmed' => 'badge-confirmed',
        'completed' => 'badge-completed',
        'cancelled' => 'badge-cancelled',
        'new' => 'badge-new',
        'contacted' => 'badge-contacted',
        'qualified' => 'badge-qualified',
        'won' => 'badge-won',
        'lost' => 'badge-lost',
        default => 'badge-new',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | ServiceBook</title>
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
            <a class="btn btn-outline-primary btn-sm" href="index.php">View Site</a>
            <a class="btn btn-outline-primary btn-sm" href="provider-dashboard.php">Provider Panel</a>
            <a class="btn btn-primary btn-sm" href="admin-logout.php">Logout</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container py-4">
      <div class="hero-card mb-4">
        <div class="row align-items-center g-4">
          <div class="col-lg-8">
            <span class="eyebrow"><i class="bi bi-kanban"></i> Admin operations</span>
            <h1 class="hero-title" style="font-size: clamp(2.4rem, 4vw, 4rem);">Manage Services, Bookings, Leads, Users, And Providers</h1>
            <p class="hero-copy mb-0">
              Welcome, <?php echo htmlspecialchars((string) $_SESSION['admin_name']); ?>.
              This dashboard now follows the admin side of the system flow chart with core operational visibility.
            </p>
          </div>
          <div class="col-lg-4">
            <div class="hero-side-panel">
              <h4 class="mb-3">Admin role</h4>
              <ul class="hero-bullet-list mt-0">
                <li><i class="bi bi-person-badge"></i><span><?php echo htmlspecialchars((string) $_SESSION['admin_role']); ?></span></li>
                <li><i class="bi bi-diagram-3"></i><span>Bookings, leads, providers, and service catalog in one panel</span></li>
                <li><i class="bi bi-gear"></i><span>Aligned with the admin flow from your reference image</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <section class="dashboard-grid mb-4">
        <div class="metric-card"><span>Active Services</span><strong><?php echo $stats['services']; ?></strong></div>
        <div class="metric-card"><span>Total Bookings</span><strong><?php echo $stats['bookings']; ?></strong></div>
        <div class="metric-card"><span>Pending Bookings</span><strong><?php echo $stats['pending_bookings']; ?></strong></div>
        <div class="metric-card"><span>Total Leads</span><strong><?php echo $stats['leads']; ?></strong></div>
        <div class="metric-card"><span>New Leads</span><strong><?php echo $stats['new_leads']; ?></strong></div>
        <div class="metric-card"><span>Active Providers</span><strong><?php echo $stats['providers']; ?></strong></div>
      </section>

      <div class="row g-4">
        <div class="col-xl-7">
          <section class="soft-panel h-100">
            <h3 class="mb-3">Recent Bookings</h3>
            <div class="table-shell table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Schedule</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($bookings) === 0): ?>
                    <tr><td colspan="5" class="text-muted">No booking records.</td></tr>
                  <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                      <tr>
                        <td><?php echo htmlspecialchars((string) $booking['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars((string) $booking['customer_phone']); ?></td>
                        <td><?php echo htmlspecialchars((string) $booking['service_name']); ?></td>
                        <td><?php echo htmlspecialchars((string) $booking['booking_date']); ?><br /><span class="text-muted small"><?php echo htmlspecialchars((string) $booking['booking_time']); ?></span></td>
                        <td><span class="badge-soft <?php echo statusBadgeClass((string) $booking['status']); ?>"><?php echo htmlspecialchars((string) $booking['status']); ?></span></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div class="col-xl-5">
          <section class="soft-panel h-100">
            <h3 class="mb-3">Lead Pipeline</h3>
            <div class="table-shell table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Interested Service</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($leads) === 0): ?>
                    <tr><td colspan="3" class="text-muted">No lead records.</td></tr>
                  <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                      <tr>
                        <td>
                          <strong><?php echo htmlspecialchars((string) $lead['full_name']); ?></strong><br />
                          <span class="text-muted small"><?php echo htmlspecialchars((string) $lead['phone']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars((string) ($lead['interested_service'] ?? '-')); ?></td>
                        <td><span class="badge-soft <?php echo statusBadgeClass((string) $lead['status']); ?>"><?php echo htmlspecialchars((string) $lead['status']); ?></span></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
