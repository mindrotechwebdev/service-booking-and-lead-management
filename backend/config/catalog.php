<?php

declare(strict_types=1);

function serviceCatalog(): array
{
    return [
        [
            'slug' => 'home-appliance',
            'title' => 'Home Appliance Repair & Service',
            'badge_class' => 'category-blue',
            'items' => [
                ['name' => 'AC Repair', 'price' => 499, 'tag' => 'Air conditioning diagnostics and repair', 'icon' => 'snow'],
                ['name' => 'AC Installation', 'price' => 799, 'tag' => 'Split and window AC installation', 'icon' => 'tools'],
                ['name' => 'Refrigerator Repair', 'price' => 549, 'tag' => 'Cooling and compressor support', 'icon' => 'box-seam'],
                ['name' => 'Washing Machine Repair', 'price' => 599, 'tag' => 'Front-load and top-load machine repair', 'icon' => 'droplet-half'],
                ['name' => 'Microwave Repair', 'price' => 449, 'tag' => 'Heating and control panel fixes', 'icon' => 'cpu'],
                ['name' => 'RO Water Purifier Service', 'price' => 399, 'tag' => 'Filter change and maintenance', 'icon' => 'droplet'],
                ['name' => 'Geyser Repair', 'price' => 449, 'tag' => 'Heating element and thermostat repair', 'icon' => 'thermometer-half'],
                ['name' => 'Chimney Cleaning', 'price' => 699, 'tag' => 'Kitchen chimney cleaning and service', 'icon' => 'wind'],
                ['name' => 'TV Repair', 'price' => 599, 'tag' => 'LED, LCD, and smart TV repair', 'icon' => 'tv'],
            ],
        ],
        [
            'slug' => 'plumbing',
            'title' => 'Plumbing Services',
            'badge_class' => 'category-green',
            'items' => [
                ['name' => 'Tap Repair', 'price' => 249, 'tag' => 'Tap and faucet repair service', 'icon' => 'water'],
                ['name' => 'Pipe Leakage Fix', 'price' => 349, 'tag' => 'Leak detection and sealing', 'icon' => 'wrench-adjustable-circle'],
                ['name' => 'Bathroom Fitting', 'price' => 549, 'tag' => 'Basin and fixture fitting', 'icon' => 'house-door'],
                ['name' => 'Water Tank Cleaning', 'price' => 999, 'tag' => 'Overhead and underground tank cleaning', 'icon' => 'droplet'],
                ['name' => 'Drain Cleaning', 'price' => 399, 'tag' => 'Drain line unclogging service', 'icon' => 'bezier2'],
                ['name' => 'Toilet Repair', 'price' => 499, 'tag' => 'Flush and seat repair', 'icon' => 'life-preserver'],
                ['name' => 'Motor Installation', 'price' => 899, 'tag' => 'Water motor and fitting support', 'icon' => 'gear-wide-connected'],
                ['name' => 'Water Pump Repair', 'price' => 799, 'tag' => 'Domestic pump servicing', 'icon' => 'fan'],
            ],
        ],
        [
            'slug' => 'electrical',
            'title' => 'Electrical Services',
            'badge_class' => 'category-amber',
            'items' => [
                ['name' => 'Switch Board Repair', 'price' => 249, 'tag' => 'Socket and switch board repair', 'icon' => 'lightning-charge'],
                ['name' => 'Fan Installation', 'price' => 349, 'tag' => 'Ceiling and exhaust fan fitting', 'icon' => 'fan'],
                ['name' => 'Light Installation', 'price' => 299, 'tag' => 'Decorative and LED light fitting', 'icon' => 'lightbulb'],
                ['name' => 'Wiring Work', 'price' => 699, 'tag' => 'Room and flat wiring jobs', 'icon' => 'plugin'],
                ['name' => 'MCB Repair', 'price' => 399, 'tag' => 'MCB and distribution board service', 'icon' => 'battery-charging'],
                ['name' => 'Inverter Wiring', 'price' => 699, 'tag' => 'Power backup wiring setup', 'icon' => 'battery-charging'],
                ['name' => 'Door Bell Installation', 'price' => 249, 'tag' => 'Bell unit setup and replacement', 'icon' => 'bell'],
                ['name' => 'Generator Service', 'price' => 999, 'tag' => 'Generator inspection and service', 'icon' => 'cpu-fill'],
            ],
        ],
        [
            'slug' => 'carpenter',
            'title' => 'Carpenter Services',
            'badge_class' => 'category-violet',
            'items' => [
                ['name' => 'Door Repair', 'price' => 399, 'tag' => 'Door alignment and latch repair', 'icon' => 'door-closed'],
                ['name' => 'Window Repair', 'price' => 449, 'tag' => 'Frame and sliding repair', 'icon' => 'window-sidebar'],
                ['name' => 'Furniture Assembly', 'price' => 699, 'tag' => 'On-site furniture assembly', 'icon' => 'tools'],
                ['name' => 'Modular Kitchen Repair', 'price' => 899, 'tag' => 'Kitchen cabinets and channel repair', 'icon' => 'grid-3x3-gap'],
                ['name' => 'Wardrobe Repair', 'price' => 599, 'tag' => 'Hinges, handles, and rails', 'icon' => 'safe2'],
                ['name' => 'Bed Repair', 'price' => 549, 'tag' => 'Bed frame and storage repair', 'icon' => 'bounding-box'],
            ],
        ],
        [
            'slug' => 'painting',
            'title' => 'Painting & Interior Services',
            'badge_class' => 'category-pink',
            'items' => [
                ['name' => 'Wall Painting', 'price' => 1299, 'tag' => 'Fresh wall paint for rooms and flats', 'icon' => 'brush'],
                ['name' => 'Texture Painting', 'price' => 1499, 'tag' => 'Decorative texture finishes', 'icon' => 'palette'],
                ['name' => 'Waterproofing', 'price' => 1799, 'tag' => 'Wall seepage and terrace waterproofing', 'icon' => 'shield-check'],
                ['name' => 'Wallpaper Installation', 'price' => 999, 'tag' => 'Wallpaper fitting and finishing', 'icon' => 'layers'],
                ['name' => 'POP Design', 'price' => 1599, 'tag' => 'Ceiling POP work and repairs', 'icon' => 'pentagon'],
            ],
        ],
        [
            'slug' => 'cleaning',
            'title' => 'Cleaning Services',
            'badge_class' => 'category-teal',
            'items' => [
                ['name' => 'Sofa Cleaning', 'price' => 599, 'tag' => 'Fabric and leather sofa cleaning', 'icon' => 'house'],
                ['name' => 'Carpet Cleaning', 'price' => 499, 'tag' => 'Dry and wet carpet cleaning', 'icon' => 'border-style'],
                ['name' => 'Bathroom Deep Cleaning', 'price' => 699, 'tag' => 'Hard stain and sanitization service', 'icon' => 'droplet-half'],
                ['name' => 'Kitchen Deep Cleaning', 'price' => 899, 'tag' => 'Grease removal and hygiene treatment', 'icon' => 'cup-hot'],
                ['name' => 'Full Home Deep Cleaning', 'price' => 1999, 'tag' => 'Complete home cleaning package', 'icon' => 'house-check'],
            ],
        ],
        [
            'slug' => 'vehicle',
            'title' => 'Vehicle Services',
            'badge_class' => 'category-orange',
            'items' => [
                ['name' => 'Car Wash', 'price' => 349, 'tag' => 'Exterior and interior car wash', 'icon' => 'car-front'],
                ['name' => 'Bike Repair', 'price' => 449, 'tag' => 'Basic bike service and repair', 'icon' => 'bicycle'],
                ['name' => 'Car AC Service', 'price' => 899, 'tag' => 'Cooling, gas, and vent service', 'icon' => 'car-front-fill'],
                ['name' => 'Battery Replacement', 'price' => 999, 'tag' => 'Battery check and replacement', 'icon' => 'battery-full'],
            ],
        ],
        [
            'slug' => 'digital',
            'title' => 'Digital / Tech Services',
            'badge_class' => 'category-cyan',
            'items' => [
                ['name' => 'Laptop Repair', 'price' => 799, 'tag' => 'Hardware and software troubleshooting', 'icon' => 'laptop'],
                ['name' => 'CCTV Installation', 'price' => 1499, 'tag' => 'Camera installation and setup', 'icon' => 'camera-video'],
                ['name' => 'WiFi Setup', 'price' => 499, 'tag' => 'Router and extender installation', 'icon' => 'wifi'],
                ['name' => 'Printer Repair', 'price' => 549, 'tag' => 'Inkjet and laser printer repair', 'icon' => 'printer'],
            ],
        ],
    ];
}

function flattenedServices(): array
{
    $items = [];

    foreach (serviceCatalog() as $category) {
        foreach ($category['items'] as $item) {
            $items[] = $item + [
                'category' => $category['title'],
                'category_slug' => $category['slug'],
                'badge_class' => $category['badge_class'],
            ];
        }
    }

    return $items;
}

function serviceHighlights(): array
{
    return [
        ['icon' => 'shield-check', 'title' => 'Verified & Trained Experts'],
        ['icon' => 'clock-history', 'title' => 'On-Time Service'],
        ['icon' => 'currency-rupee', 'title' => 'Transparent Pricing'],
        ['icon' => 'patch-check', 'title' => 'Safe & Reliable Service'],
        ['icon' => 'hand-thumbs-up', 'title' => '100% Customer Satisfaction'],
        ['icon' => 'headset', 'title' => '24/7 Customer Support'],
        ['icon' => 'calendar2-check', 'title' => 'Easy Booking & Reschedule'],
        ['icon' => 'wallet2', 'title' => 'Multiple Payment Options'],
    ];
}

function journeySteps(): array
{
    return [
        ['number' => '1', 'title' => 'Landing Page', 'copy' => 'Home page with services, trust badges, and quick call-back CTA.', 'class' => 'journey-start'],
        ['number' => '2', 'title' => 'User Login / Sign Up', 'copy' => 'New users register, existing users login, then continue into their dashboard.', 'class' => 'journey-auth'],
        ['number' => '3', 'title' => 'Dashboard', 'copy' => 'Browse services, offers, bookings, and account activity in one place.', 'class' => 'journey-dashboard'],
        ['number' => '4', 'title' => 'Select Service & Book', 'copy' => 'Pick date, time, address, and optional coupon while booking.', 'class' => 'journey-book'],
        ['number' => '5', 'title' => 'Payment', 'copy' => 'Choose online payment, wallet, or cash on delivery.', 'class' => 'journey-pay'],
        ['number' => '6', 'title' => 'Booking Confirmed', 'copy' => 'Booking ID is generated and confirmation is sent instantly.', 'class' => 'journey-confirm'],
        ['number' => '7', 'title' => 'Notification', 'copy' => 'Users, service providers, and admin all receive updates.', 'class' => 'journey-notify'],
    ];
}

function adminFlow(): array
{
    return [
        'Admin Login',
        'Dashboard',
        'Manage Services',
        'Manage Bookings',
        'Manage Users & Providers',
        'Reports & Analytics',
        'Settings',
    ];
}

function providerFlow(): array
{
    return [
        'Provider Login / Register',
        'Provider Dashboard',
        'New Booking Request',
        'Accept / Reject Booking',
        'Service In Progress',
        'Mark as Completed',
        'Earnings & History',
    ];
}
