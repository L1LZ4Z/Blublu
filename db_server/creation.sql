CREATE TABLE Details
(
  id          INT   NOT NULL,
  product_id  INT   NOT NULL,
  order_id    INT   NOT NULL,
  quantity    INT   NULL    ,
  price       FLOAT NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE Shipments
(
  id                  INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id            INT          NOT NULL,
  country_id          INT          NOT NULL,
  address             VARCHAR(150) NULL    ,
  postal_code         VARCHAR(10)  NULL    ,
  shipping_company    VARCHAR(50)  NULL    ,
  tracking_number     VARCHAR(50)  NULL    ,
  shipping_date       DATE         NULL    ,
  delivery_date       DATE         NULL    ,
);

CREATE TABLE Statuses
(
  id     INT         NOT NULL,
  name   VARCHAR(20) NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE Taxes
(
  tax_id       INT   NOT NULL,
  country_id   INT   NOT NULL,
  tax_rate     FLOAT NULL    ,
  PRIMARY KEY (tax_id)
);

CREATE TABLE Payment_Methods
(
  id     INT         NOT NULL,
  name   VARCHAR(20) NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE Payments
(
  id                INT  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  order_id          INT  NOT NULL,
  payment_method_id INT  NOT NULL,
  payment_date      DATE NULL    ,
);

CREATE TABLE Countries
(
  id     INT         NOT NULL,
  name   VARCHAR(50) NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE Orders
(
  id         INT   NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id    INT   NOT NULL,
  status_id  INT   NOT NULL,
  order_date DATE  NULL    ,
  amount     FLOAT NULL    ,
);

CREATE TABLE Products
(
  id          INT          AUTO_INCREMENT PRIMARY KEY,
  user_id     INT          NOT NULL,
  name        VARCHAR(50)  NULL,
  description VARCHAR(250) NULL,
  price       FLOAT        NULL,
  stock       INT          NULL,
  image       BLOB         NULL,
);

CREATE TABLE Roles
(
  id     INT      NOT NULL,
  name   CHAR(20) NULL    ,
  PRIMARY KEY (id)
);

CREATE TABLE Users
(
  id             INT          NOT NULL,
  role_id        INT          NOT NULL,
  country_id     INT          NOT NULL,
  name           VARCHAR(50)  NOT NULL,
  email          VARCHAR(50)  NOT NULL,
  address        VARCHAR(150) NOT NULL,
  registration_date DATE      NOT NULL,
  password       VARCHAR(255) NULL    ,
  PRIMARY KEY (id)
);

-- Foreign Keys

ALTER TABLE Products
  ADD CONSTRAINT FK_Users_TO_Products
    FOREIGN KEY (user_id)
    REFERENCES Users (id);

ALTER TABLE Orders
  ADD CONSTRAINT FK_Users_TO_Orders
    FOREIGN KEY (user_id)
    REFERENCES Users (id);

ALTER TABLE Shipments
  ADD CONSTRAINT FK_Orders_TO_Shipments
    FOREIGN KEY (order_id)
    REFERENCES Orders (id);

ALTER TABLE Payments
  ADD CONSTRAINT FK_Orders_TO_Payments
    FOREIGN KEY (order_id)
    REFERENCES Orders (id);

ALTER TABLE Users
  ADD CONSTRAINT FK_Roles_TO_Users
    FOREIGN KEY (role_id)
    REFERENCES Roles (id);

ALTER TABLE Details
  ADD CONSTRAINT FK_Products_TO_Details
    FOREIGN KEY (product_id)
    REFERENCES Products (id);

ALTER TABLE Details
  ADD CONSTRAINT FK_Orders_TO_Details
    FOREIGN KEY (order_id)
    REFERENCES Orders (id);

ALTER TABLE Orders
  ADD CONSTRAINT FK_Statuses_TO_Orders
    FOREIGN KEY (status_id)
    REFERENCES Statuses (id);

ALTER TABLE Users
  ADD CONSTRAINT FK_Countries_TO_Users
    FOREIGN KEY (country_id)
    REFERENCES Countries (id);

ALTER TABLE Shipments
  ADD CONSTRAINT FK_Countries_TO_Shipments
    FOREIGN KEY (country_id)
    REFERENCES Countries (id);

ALTER TABLE Taxes
  ADD CONSTRAINT FK_Countries_TO_Taxes
    FOREIGN KEY (country_id)
    REFERENCES Countries (id);

ALTER TABLE Payments
  ADD CONSTRAINT FK_Payment_Methods_TO_Payments
    FOREIGN KEY (payment_method_id)
    REFERENCES Payment_Methods (id);
