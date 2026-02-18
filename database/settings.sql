CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` TEXT,
    `group` VARCHAR(100) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`, `group`) VALUES
('site_title', 'Micro MVC Framework', 'general'),
('site_description', 'Modern ve hızlı PHP MVC framework deneyimi.', 'general'),
('site_email', 'admin@example.com', 'general'),
('maintenance_mode', '0', 'system'),
('allow_registration', '1', 'auth');
