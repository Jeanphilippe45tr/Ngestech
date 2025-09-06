-- Outboard Motors Sales Database Schema

CREATE DATABASE IF NOT EXISTS outboard_sales2;
USE outboard_sales2;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    zip_code VARCHAR(10),
    country VARCHAR(50),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Brands table
CREATE TABLE brands (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    description TEXT,
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table (outboard motors)
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    model VARCHAR(100) NOT NULL,
    brand_id INT NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    description TEXT,
    specifications TEXT,
    horsepower INT,
    stroke ENUM('2-stroke', '4-stroke') NOT NULL,
    fuel_type ENUM('gasoline', 'diesel', 'electric') DEFAULT 'gasoline',
    shaft_length ENUM('short', 'long', 'extra-long') DEFAULT 'long',
    weight DECIMAL(8,2),
    displacement DECIMAL(8,2),
    cylinders INT,
    cooling_system ENUM('water-cooled', 'air-cooled') DEFAULT 'water-cooled',
    starting_system ENUM('manual', 'electric', 'both') DEFAULT 'manual',
    stock_quantity INT DEFAULT 0,
    min_stock_level INT DEFAULT 5,
    sku VARCHAR(100) UNIQUE,
    status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    main_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Product images table
CREATE TABLE product_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Shopping cart table
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    session_id VARCHAR(255),
    item_id INT NOT NULL, -- Can be product_id or accessory_id
    item_type ENUM('product', 'accessory') NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX(session_id)
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_cost DECIMAL(8,2) DEFAULT 0,
    tax_amount DECIMAL(8,2) DEFAULT 0,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded', 'cancelled', 'completed') DEFAULT 'pending',
    payment_method VARCHAR(50),
    payment_transaction_id VARCHAR(255), -- PayPal transaction/capture ID
    payment_details JSON, -- Store PayPal response data
    shipping_address TEXT NOT NULL,
    billing_address TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_payment_transaction (payment_transaction_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_order_status (status)
);

-- Order items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    item_id INT NOT NULL, -- Can be product_id or accessory_id
    item_type ENUM('product', 'accessory') NOT NULL,
    quantity INT NOT NULL,
    price VARCHAR(50) NOT NULL, -- Using VARCHAR to align with product/accessory tables
    total VARCHAR(50) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Wishlist table
CREATE TABLE wishlist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

-- Reviews table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    review_text TEXT,
    verified_purchase BOOLEAN DEFAULT FALSE,
    helpful_count INT DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscriptions table
CREATE TABLE newsletter_subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100),
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PayPal transactions table for detailed tracking
CREATE TABLE paypal_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    paypal_order_id VARCHAR(255) NOT NULL,
    paypal_capture_id VARCHAR(255),
    status ENUM('created', 'approved', 'captured', 'failed', 'cancelled') DEFAULT 'created',
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payer_email VARCHAR(255),
    payer_name VARCHAR(255),
    transaction_fee DECIMAL(8,2),
    paypal_response JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    UNIQUE KEY unique_paypal_order (paypal_order_id),
    INDEX idx_paypal_capture (paypal_capture_id),
    INDEX idx_transaction_status (status)
);

-- Insert sample data

-- Insert categories
INSERT INTO categories (name, description) VALUES
('Portable Outboards', 'Lightweight outboard motors perfect for small boats and dinghies'),
('Mid-Range Outboards', 'Versatile outboard motors for recreational and fishing boats'),
('High-Performance Outboards', 'Powerful outboard motors for large boats and commercial use'),
('Electric Outboards', 'Eco-friendly electric outboard motors'),
('Accessories', 'Outboard motor parts and accessories');

-- Insert brands
INSERT INTO brands (name, description, website) VALUES
('Yamaha', 'Leading manufacturer of reliable outboard motors', 'https://yamaha-motor.com'),
('Mercury', 'Premium outboard motors with cutting-edge technology', 'https://mercurymarine.com'),
('Honda', 'Innovative and fuel-efficient outboard motors', 'https://marine.honda.com'),
('Suzuki', 'High-performance outboard motors built to last', 'https://suzukimarine.com'),
('Tohatsu', 'Dependable outboard motors for all applications', 'https://tohatsu.com'),
('Evinrude', 'Traditional American outboard motor manufacturer', 'https://evinrude.com');

-- Insert admin user (password: gonzilaib)
INSERT INTO users (username, email, password, first_name, last_name, role) VALUES
('gonzila', 'gonzila@gmail.com', '$2y$10$f1mjxKljo3i2zBwNaePEB.CulIWS0PVOAkIlLucYPWnJoTRaGYOx6', 'Admin', 'User', 'admin');

-- Insert sample products
INSERT INTO products (name, model, brand_id, category_id, price, description, horsepower, stroke, fuel_type, shaft_length, weight, stock_quantity, sku, featured) VALUES
('Yamaha F25LMHB', 'F25', 1, 1, 3299.00, 'Reliable 25HP 4-stroke outboard motor perfect for small to medium boats', 25, '4-stroke', 'gasoline', 'long', 61.0, 15, 'YAM-F25-LMH', TRUE),
('Mercury 60ELPT', '60EL', 2, 2, 7899.00, 'Powerful 60HP 4-stroke outboard with excellent fuel efficiency', 60, '4-stroke', 'gasoline', 'long', 109.0, 8, 'MER-60EL-PT', TRUE),
('Honda BF90DK2XB', 'BF90', 3, 2, 9999.00, 'Advanced 90HP 4-stroke outboard with VTEC technology', 90, '4-stroke', 'gasoline', 'extra-long', 168.0, 5, 'HON-BF90-DK2', FALSE),
('Suzuki DF140APX', 'DF140A', 4, 3, 14299.00, 'High-performance 140HP 4-stroke outboard motor', 140, '4-stroke', 'gasoline', 'extra-long', 188.0, 3, 'SUZ-DF140A-PX', TRUE),
('Tohatsu MFS15C', 'MFS15C', 5, 1, 2199.00, 'Compact and lightweight 15HP 4-stroke outboard', 15, '4-stroke', 'gasoline', 'short', 45.0, 20, 'TOH-MFS15C-S', FALSE);

-- Accessories table
CREATE TABLE accessories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price VARCHAR(50) NOT NULL, -- Using VARCHAR to accommodate text like 'Call for price'
    sale_price VARCHAR(50),
    image VARCHAR(255),
    category VARCHAR(100),
    rating DECIMAL(3,1),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample accessories
INSERT INTO accessories (name, description, price, sale_price, image, category, rating) VALUES
('PowerWave Premium Propeller Set', 'High-performance stainless steel propellers designed for maximum efficiency and durability.', '299.99', NULL, 'images/accessories/propeller-set.jpg', 'Propellers', 4.8),
('Marine Engine Oil - Synthetic Blend', 'Premium synthetic blend oil specifically formulated for PowerWave outboard motors.', '89.99', '69.99', 'images/accessories/engine-oil.jpg', 'Maintenance', 4.9),
('Outboard Motor Cover - Waterproof', 'Heavy-duty waterproof cover to protect your outboard motor from the elements.', '149.99', NULL, 'images/accessories/motor-cover.jpg', 'Protection', 4.7),
('PowerWave Digital Gauge Kit', 'Advanced digital gauge system with RPM, fuel flow, and engine diagnostics.', '899.99', NULL, 'images/accessories/gauge-kit.jpg', 'Electronics', 4.6),
('Fuel Water Separator Filter', 'Essential fuel system component that removes water and contaminants from fuel.', '45.99', '34.99', 'images/accessories/fuel-filter.jpg', 'Maintenance', 4.8),
('Outboard Motor Stand - Heavy Duty', 'Adjustable heavy-duty stand for secure storage and maintenance of outboard motors.', '199.99', NULL, 'images/accessories/motor-stand.jpg', 'Storage', 4.5),
('PowerWave Remote Control Kit', 'Complete remote control system for convenient motor operation from anywhere on your boat.', '549.99', '499.99', 'images/accessories/remote-control.jpg', 'Controls', 4.7),
('Stainless Steel Trim Tabs', 'Premium stainless steel trim tabs for improved boat performance and fuel efficiency.', '379.99', NULL, 'images/accessories/trim-tabs.jpg', 'Performance', 4.6);

