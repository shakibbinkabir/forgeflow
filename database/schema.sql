CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role VARCHAR(50) DEFAULT 'Team Member',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS customers (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    customer_id INTEGER NOT NULL,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    material VARCHAR(100),
    color VARCHAR(100),
    price DECIMAL(10,2),
    description TEXT,
    design_file VARCHAR(255),
    reference_image VARCHAR(255),
    tracking_number VARCHAR(255),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    order_id INTEGER NOT NULL,
    user_id INTEGER,
    customer_name VARCHAR(255),
    message TEXT NOT NULL,
    is_from_customer BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_status_history (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    order_id INTEGER NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by INTEGER,
    changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
);

-- Insert default admin user (password: admin123)
DELETE FROM users WHERE id = 1;
INSERT INTO users (id, name, email, password, role) VALUES 
(1, 'Administrator', 'admin@forgeflow.com', '$2y$10$K/c7rPawgHcPIOVjbyFvQu42paPlTYRFPLaA9MDK7cs5WLHBwT5lS', 'Admin');

-- Insert sample data
INSERT IGNORE INTO customers (name, email, phone, address) VALUES 
('John Doe', 'john@example.com', '123-456-7890', '123 Main St, City, State 12345'),
('Jane Smith', 'jane@example.com', '098-765-4321', '456 Oak Ave, Town, State 67890');

INSERT IGNORE INTO orders (customer_id, order_number, status, material, color, price, description) VALUES 
(1, 'FF-2024-001', 'Pending', 'PLA', 'Blue', 25.99, 'Custom phone case'),
(2, 'FF-2024-002', 'Processing', 'ABS', 'Red', 45.50, 'Mechanical part prototype'),
(1, 'FF-2024-003', 'Printing', 'PETG', 'White', 35.00, 'Display stand');