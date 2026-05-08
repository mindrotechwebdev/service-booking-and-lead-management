<?php

declare(strict_types=1);

require_once __DIR__ . '/backend/config/catalog.php';

$journey = journeySteps();
$adminFlow = adminFlow();
$providerFlow = providerFlow();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>System Flow | ServiceBook</title>
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
            <a class="btn btn-outline-primary btn-sm" href="index.php">Home</a>
            <a class="btn btn-outline-primary btn-sm" href="services.php">Services</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container py-4">
      <div class="hero-card mb-4">
        <span class="eyebrow"><i class="bi bi-diagram-3"></i> System flow reference</span>
        <h1 class="hero-title" style="font-size: clamp(2.5rem, 4vw, 4.3rem);">Service Booking Website System Flow</h1>
        <p class="hero-copy mb-0">
          This page translates your first image into an on-site flow view so the project clearly shows how customer,
          admin, and provider journeys connect from landing page to database and notifications.
        </p>
      </div>

      <div class="flow-layout">
        <section class="flow-column">
          <div class="flow-title">Admin Flow</div>
          <div class="flow-stack">
            <?php foreach ($adminFlow as $step): ?>
              <div class="flow-node admin-node">
                <i class="bi bi-shield-check"></i>
                <div>
                  <strong><?php echo htmlspecialchars($step); ?></strong>
                  <span>Admin operations mapped from the chart.</span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="journey-column">
          <div class="flow-title text-center">User Journey</div>
          <div class="journey-track">
            <?php foreach ($journey as $step): ?>
              <article class="journey-step <?php echo htmlspecialchars($step['class']); ?>">
                <span class="journey-badge"><?php echo htmlspecialchars($step['number']); ?></span>
                <strong><?php echo htmlspecialchars($step['title']); ?></strong>
                <span><?php echo htmlspecialchars($step['copy']); ?></span>
              </article>
            <?php endforeach; ?>
          </div>
          <div class="database-pill">Database connects users, providers, services, bookings, leads, and status updates.</div>
        </section>

        <section class="flow-column">
          <div class="flow-title">Provider Flow</div>
          <div class="flow-stack">
            <?php foreach ($providerFlow as $step): ?>
              <div class="flow-node provider-node">
                <i class="bi bi-tools"></i>
                <div>
                  <strong><?php echo htmlspecialchars($step); ?></strong>
                  <span>Provider workflow mapped from the chart.</span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      </div>
    </main>
  </div>
</body>
</html>
