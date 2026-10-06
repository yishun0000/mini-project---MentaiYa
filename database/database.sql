/* =========================================
   1. DATABASE CREATION & SELECTION
   ========================================= */
CREATE DATABASE IF NOT EXISTS mentaiYa;
USE mentaiYa;


/* =========================================
   2. TABLE STRUCTURES
   ========================================= */

-- Users Table: Stores admin, staff, and customer account credentials
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Constraint: Restrict roles to predefined user types
ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('Customer', 'Staff', 'Admin'));

-- Categories Table: Menu categories (e.g., Ramen, Beverages)
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

-- Menu Items Table: Dish details and foreign key link to category
CREATE TABLE IF NOT EXISTS menu_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- Orders Table: Customer order requests linked to users and menu items
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

/* =========================================
   3. SEED DATA / INITIAL RECORDS
   ========================================= */

-- Insert Default Admin and Staff Accounts
INSERT INTO users (username, email, password, role) VALUES 
('Admin', 'admin@mentaiya.com', '$2y$12$xcbZ1QqEBRI2WgswqUPCfuczXzlotHwa662cwxgKmVP3n5OyiFg6W', 'Admin'),
('Staff', 'staff@mentaiya.com', '$2y$12$xcbZ1QqEBRI2WgswqUPCfuczXzlotHwa662cwxgKmVP3n5OyiFg6W', 'Staff');

-- Insert Initial Food & Beverage Categories
INSERT INTO categories (category_id, category_name) VALUES 
(1, '拉面 · Ramen'),
(2, '寿司 · Sushi & Sashimi'),
(3, '小吃 · Appetizers'),
(4, '饮品 · Beverages');

-- Insert Initial Menu Items
INSERT INTO menu_items (item_name, price, category_id, image_url) VALUES
('Tonkotsu Ramen (豚骨拉面)', 14.50, 1, 'https://tse3.mm.bing.net/th/id/OIP.GNrqKrP2gKMOICvbkXl81gHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3'),
('Spicy Miso Ramen (辣味味噌拉面)', 15.00, 1, 'https://www.halfbakedharvest.com/wp-content/uploads/2021/01/30-Minute-Spicy-Miso-Chicken-Katsu-Ramen-1.jpg'),
('Salmon Sashimi 5pcs (三文鱼刺身)', 12.80, 2, 'https://tse4.mm.bing.net/th/id/OIP.w0nZeqMqKYsWS0SM7rva2AHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3'),
('Dragon Roll (火龙卷寿司)', 16.00, 2, 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=400&q=80'),
('Chicken Karaage (日式炸鸡)', 8.50, 3, 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=400&q=80'),
('Takoyaki 6pcs (章鱼小丸子)', 7.00, 3, 'https://tse4.mm.bing.net/th/id/OIP.obGlqDFmCHfUNTSKPkovPQHaHa?r=0&rs=1&pid=ImgDetMain&o=7&rm=3'),
('Matcha Green Tea (宇治抹茶)', 4.50, 4, 'https://www.natalieshealth.com/wp-content/uploads/2021/02/Matcha-Grean-Tea-Latte-6.jpg'),
('Asahi Super Dry Beer (朝日啤酒)', 6.50, 4, 'https://tse3.mm.bing.net/th/id/OIP.vcm3-WhcRnsqC5PSy3H6qAHaDj?r=0&rs=1&pid=ImgDetMain&o=7&rm=3');