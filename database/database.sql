CREATE DATABASE IF NOT EXISTS mentaiYa;
USE mentaiYa;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('Customer', 'Staff', 'Admin'));

CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS menu_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    table_number INT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id) ON DELETE CASCADE,
    CONSTRAINT chk_status CHECK (status IN ('Pending', 'Completed'))
);

INSERT INTO users (username, email, password, role) VALUES 
('Admin', 'admin@mentaiya.com', '$2y$12$xcbZ1QqEBRI2WgswqUPCfuczXzlotHwa662cwxgKmVP3n5OyiFg6W', 'Admin'),
('Staff', 'staff@mentaiya.com', '$2y$12$xcbZ1QqEBRI2WgswqUPCfuczXzlotHwa662cwxgKmVP3n5OyiFg6W', 'Staff');

INSERT INTO categories (category_id, category_name) VALUES 
(1, '拉面 · Ramen'),
(2, '寿司 · Sushi & Sashimi'),
(3, '小吃 · Appetizers'),
(4, '饮品 · Beverages');

INSERT INTO menu_items (item_name, price, category_id) VALUES 
('Tonkotsu Ramen (豚骨拉面)', 14.50, 1),
('Spicy Miso Ramen (辣味味噌拉面)', 15.00, 1),
('Salmon Sashimi 5pcs (三文鱼刺身)', 12.80, 2),
('Dragon Roll (火龙卷寿司)', 16.00, 2),
('Chicken Karaage (日式炸鸡)', 8.50, 3),
('Takoyaki 6pcs (章鱼小丸子)', 7.00, 3),
('Matcha Green Tea (宇治抹茶)', 4.50, 4),
('Asahi Super Dry Beer (朝日啤酒)', 6.50, 4);