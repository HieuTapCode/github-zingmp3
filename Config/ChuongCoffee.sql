-- Create the database and use it
CREATE DATABASE chuongcoffeeDb;
USE chuongcoffeeDb;

-- Create Users table
CREATE TABLE `Users` (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('employee', 'admin') NOT NULL DEFAULT 'employee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Products table
CREATE TABLE `Products` (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Tables table
CREATE TABLE `Tables` (
    table_id INT PRIMARY KEY AUTO_INCREMENT,
    name_table VARCHAR(255) NOT NULL,
    table_number INT NOT NULL DEFAULT 4,
    number_customer_id INT NOT NULL DEFAULT 0,
    status ENUM('free', 'booked') NOT NULL DEFAULT 'free'
);

-- Create OrderTables table 
CREATE TABLE `OrderTables` (
    order_table_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    table_id INT NOT NULL,
    phone_number VARCHAR(255) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (table_id) REFERENCES `Tables` (table_id) ON DELETE SET NULL
);

-- Create OrderDrinks table
CREATE TABLE `OrderDrinks` (
    order_drink_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    order_table_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (order_table_id) REFERENCES `OrderTables` (order_table_id) ON DELETE SET NULL
);

-- Create OrderDrinkDetails table
CREATE TABLE `OrderDrinkDetails` (
    order_detail_id INT PRIMARY KEY AUTO_INCREMENT,
    order_drink_id INT NOT NULL,
    drink_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    price DECIMAL(10, 0) NOT NULL CHECK (price >= 0),
    FOREIGN KEY (order_drink_id) REFERENCES `OrderDrinks` (order_drink_id) ON DELETE CASCADE,
    FOREIGN KEY (drink_id) REFERENCES `Products` (product_id) ON DELETE CASCADE
);

-- Create Payments table for VNPay and other payments
CREATE TABLE `Payments` (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_drink_id INT NOT NULL,
    txn_ref VARCHAR(255) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL CHECK (amount > 0),
    order_info VARCHAR(255) DEFAULT NULL,
    response_code VARCHAR(10) DEFAULT NULL,
    transaction_no VARCHAR(255) DEFAULT NULL,
    bank_code VARCHAR(50) DEFAULT NULL,
    pay_date DATETIME DEFAULT NULL,
    status ENUM('success', 'failed') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_drink_id) REFERENCES `OrderDrinks` (order_drink_id) ON DELETE CASCADE
);
