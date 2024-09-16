DELIMITER $$

CREATE DEFINER = 'root'@'localhost' TRIGGER TR_ValidateStock
BEFORE INSERT ON Details
FOR EACH ROW
BEGIN
    DECLARE current_stock INT;
    
    -- Get the current stock of the product
    SELECT stock INTO current_stock FROM Products WHERE id = NEW.product_id;

    -- Validate that the stock is sufficient
    IF current_stock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock available for this product.';
    END IF;

END $$

DELIMITER $$

CREATE DEFINER = 'root'@'localhost' TRIGGER TR_UpdateStock
AFTER INSERT ON Details
FOR EACH ROW
BEGIN
    -- Decrease the product stock
    UPDATE Products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END $$

DELIMITER $$

CREATE DEFINER = 'root'@'localhost' TRIGGER TR_ValidateShipmentDate
BEFORE INSERT OR UPDATE ON Shipments
FOR EACH ROW
BEGIN
    -- Validate that the delivery date is not earlier than the shipping date
    IF NEW.delivery_date < NEW.shipping_date THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'The delivery date cannot be earlier than the shipping date.';
    END IF;
END $$

DELIMITER $$

CREATE DEFINER = 'root'@'localhost' TRIGGER TR_UpdateOrderStatus
AFTER INSERT ON Payments
FOR EACH ROW
BEGIN
    -- Update the order status to 'Completed' or 'Paid'
    UPDATE Orders
    SET status_id = (SELECT id FROM Statuses WHERE name = 'Completed')
    WHERE id = NEW.order_id;
END $$

DELIMITER ;