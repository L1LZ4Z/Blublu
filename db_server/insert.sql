-- Insertar Roles
INSERT INTO Roles (id, name) 
VALUES 
(1, 'Customer'),
(2, 'Seller');

-- Insertar Países
INSERT INTO Countries (id, name) 
VALUES 
(1, 'Peru'),
(2, 'United States'),
(3, 'Germany'),
(4, 'Brazil'),
(5, 'Japan');

-- Insertar Estados
INSERT INTO Statuses (id, name) 
VALUES 
(1, 'Pending'),
(2, 'Paid'),
(3, 'Shipped'),
(4, 'Delivered'),
(5, 'Completed'),
(6, 'Cancelled');

-- Insertar Métodos de Pago
INSERT INTO Payment_Methods (id, name) 
VALUES 
(1, 'Credit Card'),
(2, 'Debit Card'),
(3, 'PayPal'),
(4, 'Bank Transfer'),
(5, 'Cash on Delivery');

-- Insertar Impuestos
INSERT INTO Taxes (tax_id, country_id, tax_rate) 
VALUES 
(1, 1, 18.0),  -- Perú (IGV)
(2, 2, 7.0),   -- United States (General Sales Tax)
(3, 3, 19.0),  -- Germany (VAT)
(4, 4, 17.0),  -- Brazil (ICMS)
(5, 5, 10.0);  -- Japan (Consumption Tax)

-- Usuarios
-- 1: hashed_password_cliente
-- 2: hashed_password_vendedor
INSERT INTO Users 
(id, role_id, country_id, name, email, address, registration_date, password)
VALUES 
(1, 1, 1, 'Juan Pérez', 'juan.perez@example.com', 'Calle Falsa 123, Ciudad', CURDATE(), '$2y$10$2u8ijM4WPO9axtUQbtpRKeLlcdDyP8YvFIlEnyr5vU9wAjm2kibZ6'),
(2, 2, 1, 'Ana Gómez', 'ana.gomez@example.com', 'Avenida Siempre Viva 456, Ciudad', CURDATE(), '$2y$10$3d168li5bpoTosrWaD6PseIfQpDnsijQ60DYD1a3uW6xQ8D5w1zxG');
