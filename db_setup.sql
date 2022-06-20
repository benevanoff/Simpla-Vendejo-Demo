CREATE DATABASE eshop;
USE eshop;
CREATE TABLE auth (username VARCHAR(75) PRIMARY KEY, password VARCHAR(64) NOT NULL);
CREATE TABLE orders (order_id INT PRIMARY KEY, details JSON, customer_name VARCHAR(50), shipping_addr VARCHAR(150), contact VARCHAR(30), shipped DATE);
CREATE TABLE listings (prod_id INT PRIMARY KEY, name VARCHAR(35) NOT NULL, description TEXT, price FLOAT NOT NULL, img_name VARCHAR(100));