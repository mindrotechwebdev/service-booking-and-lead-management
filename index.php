<?php

declare(strict_types=1);

require_once __DIR__ . '/backend/config/catalog.php';

$success = (string) ($_GET['success'] ?? '');
$error = (string) ($_GET['error'] ?? '');
$catalog = serviceCatalog();
$highlights = serviceHighlights();
$journey = journeySteps();
$adminFlow = adminFlow();
$providerFlow = providerFlow();
$serviceCount = count(flattenedServices());
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ServiceBook | Service Booking Platform</title>
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
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
              <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
              <li class="nav-item"><a class="nav-link" href="about-website.php">System Flow</a></li>
              <li class="nav-item"><a class="nav-link" href="provider-login.php">Provider</a></li>
              <li class="nav-item"><a class="nav-link nav-pill nav-pill-outline" href="login.php">User Login</a></li>
              <li class="nav-item"><a class="nav-link nav-pill nav-pill-primary" href="register.php">Sign Up</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <section class="hero-section">
        <div class="container">
          <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
          <?php endif; ?>
          <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <div class="hero-card">
            <div class="row g-4 align-items-center">
              <div class="col-xl-8">
                <span class="eyebrow"><i class="bi bi-stars"></i> Trusted home and business services</span>
                <h1 class="hero-title">Professional service booking for every essential need</h1>
                <p class="hero-copy">
                  Connect customers, service partners, and operations teams through a structured booking platform with
                  clear service discovery, dependable scheduling, and end-to-end request management.
                </p>
                <div class="hero-actions">
                  <a class="btn-main" href="services.php"><i class="bi bi-calendar2-check me-2"></i>Book a Service</a>
                  <a class="btn-ghost" href="#system-flow"><i class="bi bi-diagram-3 me-2"></i>View System Flow</a>
                </div>
                <div class="hero-stats">
                  <div class="hero-stat"><strong><?php echo $serviceCount; ?>+</strong><span>Services Available</span></div>
                  <div class="hero-stat"><strong>3</strong><span>User Roles</span></div>
                  <div class="hero-stat"><strong>24/7</strong><span>Support Access</span></div>
                  <div class="hero-stat"><strong>100%</strong><span>Satisfaction Focus</span></div>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="hero-side-panel">
                  <h3 class="mb-3">Platform highlights</h3>
                  <ul class="hero-bullet-list">
                    <li><i class="bi bi-check2-circle"></i><span>Comprehensive multi-category service catalog with structured pricing.</span></li>
                    <li><i class="bi bi-check2-circle"></i><span>Clear customer, admin, and provider journeys across the platform.</span></li>
                    <li><i class="bi bi-check2-circle"></i><span>Booking workflow with schedule, location, coupon, and payment preference.</span></li>
                    <li><i class="bi bi-check2-circle"></i><span>Unified dashboards for operational visibility and service tracking.</span></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section-block">
        <div class="container">
          <div class="section-header">
            <span class="kicker">Service Catalog</span>
            <h2>Complete service coverage across every major category</h2>
            <p>Designed with the structure of your reference image, but refined into a cleaner and more professional service marketplace presentation.</p>
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
                  <?php foreach ($category['items'] as $index => $item): ?>
                    <a class="service-item" href="services.php?service=<?php echo urlencode($item['name']); ?>#booking-form">
                      <div>
                        <div class="service-icon-wrap">
                          <i class="bi bi-<?php echo htmlspecialchars($item['icon']); ?>"></i>
                        </div>
                        <strong><?php echo ($index + 1) . '. ' . htmlspecialchars($item['name']); ?></strong>
                        <span><?php echo htmlspecialchars($item['tag']); ?></span>
                      </div>
                      <div class="price">Starts at Rs <?php echo htmlspecialchars((string) $item['price']); ?></div>
                    </a>
                  <?php endforeach; ?>
                </div>
              </section>
            <?php endforeach; ?>
          </div>

          <div class="highlights-band mt-4">
            <?php foreach ($highlights as $highlight): ?>
              <div class="highlight-chip">
                <i class="bi bi-<?php echo htmlspecialchars($highlight['icon']); ?>"></i>
                <span><?php echo htmlspecialchars($highlight['title']); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="section-block" id="system-flow">
        <div class="container">
          <div class="section-header">
            <span class="kicker">Operational Flow</span>
            <h2>End-to-end booking flow for customers, providers, and admin teams</h2>
            <p>
              The platform journey is organized from landing experience through authentication, booking, confirmation,
              notifications, and role-based operations.
            </p>
          </div>

          <div class="flow-layout">
            <aside class="flow-column">
              <div class="flow-title">Admin Flow</div>
              <div class="flow-stack">
                <?php foreach ($adminFlow as $step): ?>
                  <div class="flow-node admin-node">
                    <i class="bi bi-person-workspace"></i>
                    <div>
                      <strong><?php echo htmlspecialchars($step); ?></strong>
                      <span>Admin control panel feature aligned with the flow chart.</span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </aside>

            <section class="journey-column">
              <div class="flow-title text-center">Core Booking Journey</div>
              <div class="journey-track">
                <?php foreach ($journey as $step): ?>
                  <article class="journey-step <?php echo htmlspecialchars($step['class']); ?>">
                    <span class="journey-badge"><?php echo htmlspecialchars($step['number']); ?></span>
                    <strong><?php echo htmlspecialchars($step['title']); ?></strong>
                    <span><?php echo htmlspecialchars($step['copy']); ?></span>
                  </article>
                <?php endforeach; ?>
              </div>
              <div class="database-pill">
                Database: users, providers, services, bookings, payments, reviews, and lead data
              </div>
            </section>

            <aside class="flow-column">
              <div class="flow-title">Provider Flow</div>
              <div class="flow-stack">
                <?php foreach ($providerFlow as $step): ?>
                  <div class="flow-node provider-node">
                    <i class="bi bi-tools"></i>
                    <div>
                      <strong><?php echo htmlspecialchars($step); ?></strong>
                      <span>Provider-facing workflow for handling requests and tracking earnings.</span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </aside>
          </div>
        </div>
      </section>

      <section class="section-block">
        <div class="container">
          <div class="cta-banner">
            <div class="row align-items-center g-3">
              <div class="col-lg-8">
                <h3>Need a consultation or a custom service plan?</h3>
                <p class="mb-0">
                  Share your requirements and the team can review the request, contact the customer, and convert it into a scheduled booking.
                </p>
              </div>
              <div class="col-lg-4">
                <form action="backend/leads.php" method="post" class="row g-2">
                  <div class="col-12">
                    <input type="text" name="full_name" class="form-control" placeholder="Full name" required />
                  </div>
                  <div class="col-12">
                    <input type="text" name="phone" class="form-control" placeholder="Phone number" required />
                  </div>
                  <div class="col-12">
                    <input type="email" name="email" class="form-control" placeholder="Email address" />
                  </div>
                  <div class="col-12">
                    <input type="text" name="interested_service" class="form-control" placeholder="Interested service" />
                  </div>
                  <div class="col-12">
                    <textarea name="message" class="form-control" rows="3" placeholder="Tell us what you need"></textarea>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn-booking">Submit Lead</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer-bar">
      <div class="container d-flex flex-wrap justify-content-between gap-2">
        <span>&copy; 2026 ServiceBook. Built for service booking, provider operations, and lead management.</span>
        <span><a href="services.php">Explore services</a> | <a href="about-website.php">See full flow</a></span>
      </div>
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
