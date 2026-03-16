CREATE DATABASE gearlog;
USE gearlog;

CREATE TABLE categories(
id INT AUTO_INCREMENT PRIMARY KEY,
hardware_type VARCHAR(100)
);

CREATE TABLE assets (
id INT AUTO_INCREMENT PRIMARY KEY,
serial_number VARCHAR(100) UNIQUE,
device_name VARCHAR(100),
price DECIMAL(7,2),
status ENUM('in_use','available','maintenance'),
category_id INT,
FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO categories (hardware_type) VALUES
('Laptops'),
('Desktops'),
('Monitors'),
('Servers'),
('Printers'),
('Routers'),
('Switches'),
('External Storage'),
('Keyboards'),
('Mice');


INSERT INTO assets (serial_number, device_name, price, status, category_id) VALUES
('SN-LT-1001', 'Dell Latitude 7420', 1200.00, 'in_use', 1),
('SN-LT-1002', 'HP EliteBook 840 G8', 1150.50, 'in_use', 1),
('SN-LT-1003', 'MacBook Pro 16 M2', 10500.00, 'in_use', 1),
('SN-LT-1004', 'Lenovo ThinkPad X1 Carbon', 9800.00, 'available', 1),
('SN-DT-2001', 'Lenovo ThinkCentre M720', 850.00, 'available', 2),
('SN-DT-2002', 'HP ProDesk 600 G5', 900.00, 'in_use', 2),
('SN-DT-2003', 'Dell OptiPlex 7090', 950.00, 'maintenance', 2),
('SN-MN-3001', 'Dell UltraSharp U2720Q', 430.75, 'in_use', 3),
('SN-MN-3002', 'LG 27UK850 Monitor', 399.99, 'available', 3),
('SN-MN-3003', 'Samsung Odyssey G7', 620.00, 'available', 3),
('SN-SR-4001', 'Dell PowerEdge R740', 15400.00, 'in_use', 4),
('SN-SR-4002', 'HP ProLiant DL380 Gen10', 17200.00, 'in_use', 4),
('SN-SR-4003', 'Lenovo ThinkSystem SR650', 14850.00, 'maintenance', 4),
('SN-PR-5001', 'HP LaserJet Pro M404', 220.00, 'maintenance', 5),
('SN-PR-5002', 'Canon imageCLASS MF445dw', 310.00, 'available', 5),
('SN-RT-6001', 'Cisco RV340 Router', 310.00, 'in_use', 6),
('SN-RT-6002', 'MikroTik RB4011 Router', 420.00, 'available', 6),
('SN-SW-7001', 'Netgear GS308 Switch', 95.50, 'available', 7),
('SN-SW-7002', 'Cisco Catalyst 9200', 10800.00, 'in_use', 7),
('SN-EX-8001', 'Seagate 2TB External HDD', 89.99, 'available', 8);

