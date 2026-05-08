<?php

declare(strict_types=1);

require_once __DIR__ . '/backend/config/catalog.php';

$success = (string) ($_GET['success'] ?? '');
$error = (string) ($_GET['error'] ?? '');
$selectedService = trim((string) ($_GET['service'] ?? ''));
$catalog = serviceCatalog();
$allServices = flattenedServices();
$selectedDetails = null;

foreach ($allServices as $item) {
    if (strcasecmp($item['name'], $selectedService) === 0) {
        $selectedDetails = $item;
        break;
    }
}

if ($selectedDetails === null && count($allServices) > 0) {
    $selectedDetails = $allServices[0];
    $selectedService = $selectedDetails['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Services & Booking | ServiceBook</title>
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
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#servicesNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="servicesNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
              <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link active" href="services.php">Services</a></li>
              <li class="nav-item"><a class="nav-link" href="about-website.php">System Flow</a></li>
              <li class="nav-item"><a class="nav-link" href="provider-login.php">Provider</a></li>
              <li class="nav-item"><a class="nav-link nav-pill nav-pill-primary" href="login.php">User Login</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <section class="hero-section">
        <div class="container">
          <div class="hero-card">
            <div class="row g-4 align-items-center">
              <div class="col-lg-8">
                <span class="eyebrow"><i class="bi bi-bag-check"></i> Service booking</span>
                <h1 class="hero-title">Choose the right service and schedule it with confidence</h1>
                <p class="hero-copy">
                  Review available service categories, select a preferred visit slot, and submit a structured request for confirmation.
                </p>
                <div class="hero-actions">
                  <a class="btn-main" href="#booking-form">Book Now</a>
                  <a class="btn-ghost" href="#service-board">Browse All Services</a>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="hero-side-panel">
                  <h4 class="mb-3">Selected service</h4>
                  <?php if ($selectedDetails !== null): ?>
                    <div class="service-item">
                      <div>
                        <div class="service-icon-wrap">
                          <i class="bi bi-<?php echo htmlspecialchars($selectedDetails['icon']); ?>"></i>
                        </div>
                        <strong><?php echo htmlspecialchars($selectedDetails['name']); ?></strong>
                        <span><?php echo htmlspecialchars($selectedDetails['category']); ?></span>
                      </div>
                      <div class="price">Rs <?php echo htmlspecialchars((string) $selectedDetails['price']); ?></div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section-block" id="service-board">
        <div class="container">
          <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
          <?php endif; ?>
          <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <div class="section-header">
            <span class="kicker">Browse Services</span>
            <h2>Explore service options across every core category</h2>
            <p>Each service group is organized for quick comparison and direct handoff into the booking form below.</p>
          </div>

          <div class="service-board">
            <?php foreach ($catalog as $category): ?>
              <section class="category-panel">
                <div class="category-heading">
                  <span class="category-badge <?php echo htmlspecialchars($category['badge_class']); ?>">
                    <?php echo htmlspecialchars($category['title']); ?>
                  </span>
                </div>
                <div class="service-grid">
                  <?php foreach ($category['items'] as $item): ?>
                    <a class="service-item" href="services.php?service=<?php echo urlencode($item['name']); ?>#booking-form">
                      <div>
                        <div class="service-icon-wrap">
                          <i class="bi bi-<?php echo htmlspecialchars($item['icon']); ?>"></i>
                        </div>
                        <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                        <span><?php echo htmlspecialchars($item['tag']); ?></span>
                      </div>
                      <div class="price">Rs <?php echo htmlspecialchars((string) $item['price']); ?></div>
                    </a>
                  <?php endforeach; ?>
                </div>
              </section>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section-block" id="booking-form">
        <div class="container">
          <div class="section-header">
            <span class="kicker">Booking Form</span>
            <h2>Submit a complete booking request</h2>
            <p>Capture the service, schedule, location, and payment preference in one professional booking flow.</p>
          </div>

          <div class="booking-layout">
            <section class="soft-panel">
              <h3 class="mb-3">Booking Details</h3>
              <form action="backend/booking.php" method="post">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="customer_phone" class="form-control" required />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="customer_email" class="form-control" required />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Service</label>
                    <select name="service_name" class="form-select" required>
                      <?php foreach ($allServices as $item): ?>
                        <option value="<?php echo htmlspecialchars($item['name']); ?>" <?php echo strcasecmp($selectedService, $item['name']) === 0 ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($item['name'] . ' - ' . $item['category']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Preferred Date</label>
                    <input type="date" name="booking_date" class="form-control" required />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Preferred Time</label>
                    <input type="time" name="booking_time" class="form-control" required />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Payment Mode</label>
                    <select name="payment_mode" class="form-select" required>
                      <option value="Online Payment">Online Payment</option>
                      <option value="Wallet">Wallet</option>
                      <option value="Cash on Delivery">Cash on Delivery</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Coupon Code</label>
                    <input type="text" name="coupon_code" class="form-control" placeholder="Optional" />
                  </div>
                  <div class="col-12">
                    <label class="form-label">Service Address</label>
                    <input type="text" name="address" class="form-control" required />
                  </div>
                  <div class="col-12">
                    <label class="form-label">Notes / Requirements</label>
                    <textarea name="notes" rows="4" class="form-control" placeholder="Tell us any access notes, issue details, or preferred timing window"></textarea>
                  </div>
                  <div class="col-12">
                    <button class="btn-booking" type="submit">Submit Booking Request</button>
                  </div>
                </div>
              </form>
            </section>

            <aside class="booking-summary-card">
              <h3>What happens next</h3>
              <p class="mb-0">This summary gives the customer a clear view of the booking process after submission.</p>
              <ul class="booking-summary-list">
                <li><span>1. Service Selected</span><strong><?php echo htmlspecialchars($selectedService); ?></strong></li>
                <li><span>2. Slot Capture</span><strong>Date, Time, Address</strong></li>
                <li><span>3. Payment Mode</span><strong>Online / Wallet / COD</strong></li>
                <li><span>4. Booking Status</span><strong>Pending to Confirmed</strong></li>
                <li><span>5. Notifications</span><strong>User, Provider, Admin</strong></li>
              </ul>

              <div class="info-strip">
                <span class="info-chip">Easy reschedule</span>
                <span class="info-chip">Provider assignment</span>
                <span class="info-chip">Admin tracking</span>
              </div>
            </aside>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer-bar">
      <div class="container d-flex flex-wrap justify-content-between gap-2">
        <span>&copy; 2026 ServiceBook. Fast booking, provider coordination, and admin management.</span>
        <span><a href="index.php">Home</a> | <a href="about-website.php">System flow</a></span>
      </div>
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
