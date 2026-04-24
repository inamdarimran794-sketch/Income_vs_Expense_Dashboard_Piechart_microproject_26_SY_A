-- Create database (run this in phpMyAdmin or MySQL)
CREATE DATABASE IF NOT EXISTS income_expense;
USE income_expense;

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT (PRIMARY KEY),NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    
);

-- Sample data
INSERT INTO transactions (type, amount, description) VALUES
('income', 5000.00, 'Salary'),
('income', 1200.00, 'Freelance'),
('expense', 800.00, 'Rent'),
('expense', 350.00, 'Groceries'),
('expense', 120.00, 'Utilities');