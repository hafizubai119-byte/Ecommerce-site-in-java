CREATE DATABASE IF NOT EXISTS deskside CHARACTER SET utf8mb4;
USE deskside;
DROP TABLE IF EXISTS order_items, orders, products, categories;
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL,
  slug VARCHAR(60) NOT NULL UNIQUE
);
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  emoji VARCHAR(8) DEFAULT '📦',
  color VARCHAR(7) DEFAULT '#E8ECFF',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  address TEXT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);
INSERT INTO categories (name, slug) VALUES ('Writing','writing'),('Desk gear','desk-gear'),('Bags','bags'),('Tech','tech');
INSERT INTO products (category_id,name,description,price,stock,emoji,color) VALUES
(1,'Gel Pen Set (12)','Smooth 0.5mm gel pens in twelve colours. Quick-dry ink that will not smudge on notes.',9.50,80,'🖊️','#FFF1B8'),
(1,'A5 Dot Grid Notebook','160 pages of 100gsm paper. Lies flat, perfect for code sketches and lecture notes.',12.00,60,'📓','#DDE6FF'),
(1,'Sticky Tab Pack','Five neon tab colours for marking chapters and revision points.',4.25,120,'🔖','#FFD6E0'),
(2,'Adjustable Laptop Stand','Aluminium stand with six height settings. Folds flat to 1.5 cm.',29.99,25,'💻','#D8F5EA'),
(2,'LED Desk Lamp','Three colour temperatures, touch dimmer and a USB charging port.',24.50,30,'💡','#FFF1B8'),
(2,'Desk Mat XL','80 x 40 cm water-resistant mat with a stitched edge.',15.75,45,'🟦','#DDE6FF'),
(3,'Commuter Backpack','Fits a 15.6 inch laptop. Padded back, hidden zip pocket, rain cover.',44.00,18,'🎒','#FFD6E0'),
(3,'Canvas Pencil Case','Zip case that holds 40 pens and opens fully flat.',7.80,70,'✏️','#D8F5EA'),
(4,'Mechanical Keyboard','Compact 75% layout, hot-swappable switches, USB-C.',59.00,12,'⌨️','#DDE6FF'),
(4,'Wireless Mouse','Quiet clicks, 18 month battery, works with three devices.',18.90,40,'🖱️','#FFF1B8'),
(4,'USB-C Hub (7-in-1)','HDMI, SD card, two USB-A, USB-C power pass-through.',27.00,22,'🔌','#D8F5EA'),
(4,'Noise-Cancelling Headphones','Over-ear, 30 hour battery, foldable.',79.00,0,'🎧','#FFD6E0');
