-- Service Booking and Lead Management
-- Apply inside the existing XAMPP database only.

USE service_booking_lead_management;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('super_admin','admin') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  phone VARCHAR(25) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE service_providers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  phone VARCHAR(25) NOT NULL,
  primary_service VARCHAR(120) NOT NULL,
  city VARCHAR(120) NOT NULL,
  experience_years INT UNSIGNED NOT NULL DEFAULT 1,
  password_hash VARCHAR(255) NOT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  duration_minutes INT UNSIGNED NOT NULL DEFAULT 60,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_id INT UNSIGNED NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  customer_email VARCHAR(180) NOT NULL,
  customer_phone VARCHAR(25) NOT NULL,
  booking_date DATE NOT NULL,
  booking_time TIME NOT NULL,
  address VARCHAR(255),
  notes TEXT,
  status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_booking_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE leads (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(180),
  phone VARCHAR(25),
  interested_service VARCHAR(120),
  source VARCHAR(100) NOT NULL DEFAULT 'website',
  message TEXT,
  status ENUM('new','contacted','qualified','won','lost') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO admins (full_name, email, password_hash, role) VALUES
('Super Admin', 'admin@servicebook.local', '$2y$12$7oxRF./77xfqqIPs4Rtzl.i1k06f9eEmZPbh35refURZtaMLnIFnK', 'super_admin');

INSERT INTO users (full_name, email, phone, password_hash, status) VALUES
('Demo User', 'user@servicebook.local', '9999999999', '$2y$12$Y5Uo3kF8A/17wxNlbj3HqO5sO4YXZP289uc2LbmgTC4I86CSK7MGu', 'active');

INSERT INTO service_providers (full_name, email, phone, primary_service, city, experience_years, password_hash, status) VALUES
('Demo Provider', 'provider@servicebook.local', '9876543201', 'AC Repair', 'Noida', 4, '$2y$12$7oxRF./77xfqqIPs4Rtzl.i1k06f9eEmZPbh35refURZtaMLnIFnK', 'active');

INSERT INTO services (name, description, price, duration_minutes, is_active) VALUES
('Cleaning Service', 'Home and office deep cleaning by trained professionals.', 499.00, 90, 1),
('Plumbing Service', 'Leak fixes, fitting replacement, and pipe maintenance.', 799.00, 60, 1),
('Electrical Service', 'Wiring checks, repairs, and electrical safety support.', 999.00, 75, 1),
('Painting Service', 'Interior and exterior painting with premium finish.', 1499.00, 180, 1),
('AC Repair Service', 'AC service, gas refill, and installation support.', 899.00, 120, 1),
('Carpentry Service', 'Furniture repairs and custom carpentry work.', 649.00, 90, 1),
('Pest Control Service', 'Safe and effective pest treatment for home and office.', 1199.00, 90, 1),
('Sofa Cleaning Service', 'Deep shampoo and stain removal for sofas and upholstery.', 1299.00, 120, 1),
('Water Tank Cleaning', 'Underground and overhead water tank cleaning and disinfection.', 1599.00, 150, 1),
('Bathroom Deep Cleaning', 'Complete bathroom sanitization with hard stain removal.', 699.00, 75, 1),
('Kitchen Deep Cleaning', 'Grease removal and full kitchen hygiene treatment.', 999.00, 120, 1),
('Refrigerator Repair', 'Cooling issue diagnosis and repair for all major brands.', 749.00, 60, 1),
('Washing Machine Repair', 'Fully automatic and semi-automatic washing machine service.', 849.00, 75, 1),
('RO Water Purifier Service', 'Filter replacement, cleaning, and purification checkup.', 699.00, 60, 1),
('Microwave Repair', 'Heating and control panel repair for microwave ovens.', 799.00, 60, 1),
('Geyser Repair Service', 'Heating element and thermostat troubleshooting and repair.', 899.00, 75, 1),
('CCTV Installation', 'Home and office CCTV setup with basic training.', 2499.00, 180, 1),
('Computer/Laptop Repair', 'Hardware diagnostics, upgrade, and software optimization.', 1099.00, 90, 1),
('Door Lock Installation', 'Digital and manual lock installation and replacement.', 699.00, 60, 1),
('TV Wall Mount Installation', 'Safe TV wall mounting and cable management.', 999.00, 90, 1),
('Interior Handyman Service', 'General home maintenance for minor fixes and fittings.', 899.00, 120, 1);

INSERT INTO leads (full_name, email, phone, interested_service, source, message, status) VALUES
('Ravi Kumar', 'ravi@example.com', '9876543210', 'Cleaning Service', 'website', 'Need cleaning for 2BHK this weekend.', 'new'),
('Priya Sharma', 'priya@example.com', '9811112233', 'Electrical Service', 'instagram', 'Need urgent switchboard repair.', 'contacted');

INSERT INTO bookings (service_id, customer_name, customer_email, customer_phone, booking_date, booking_time, address, notes, status) VALUES
(1, 'Aman Verma', 'aman@example.com', '9898989898', '2026-04-20', '10:00:00', 'Sector 62, Noida', 'Call before arrival', 'pending'),
(3, 'Neha Singh', 'neha@example.com', '9876501234', '2026-04-22', '14:30:00', 'Raj Nagar, Ghaziabad', 'Check complete wiring', 'confirmed');
