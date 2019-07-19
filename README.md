# Project-Management-Web-Application
- Login System (using parameterised queries/prepared statements, hashed passwords, etc.)
  - Users are Objects - to access the users [stored] data, you can use the following syntax:
    ```php
    $user_object = unserialize($_SESSION['user']);

    $user_object->_mailuid; //this contains the users Email Address.
    $user_object->_logged_in; //this contains whether or not the users is logged in (TRUE/FALSE).
    $user_object->_userName; //this contains the users username.
    $user_object->_userID; //this contains the users User ID from the database.
    $user_object->_admin; //this contains whether or not a users it an Admin, or not.
    /*
      DISCLAIMER: These variables are set when the user logs in to the website, and cannot be changed.
    */
    ```
- Customer table selection
- Order-completion notification for the user
- Registration System.
- Shopping-cart System.
  - Users can only access this basket/cart system if they have saved ONE or MORE products, otherwise they'll be redirected to Index.
- Admin/Staff system
- Account page
  - Users can change their passsword here and check the details the restraunt curently has stored about them.
- If the user has ordered, they can go back to their order through the bottom nav-bar.
- Languages used: PHP (OOP and Procedural), CSS, JS, JQuery, HTML, SQL.

## Function definitions.
  - `returnNextID()` - returns largest OrderID incremented by 1.
  - `getOrderStatus($id)` - pass-through Order ID (`$id`), returns `Y` if an order is marked as complete and `N` if not.
  - `insertOrders($total,$table,$current_timestamp,$userID)` - pass through the required arguments, inserts that data into the database.
  - `insertOrderItems($qty,$prod_id,$order_id)` - pass through the required arguments, inserts that data into the database.
  - `printItems($category)` - pass through a product category and it return everything in that given category.
  - `getUserDetails($email, $admin)` - `$email` is the users email, `$admin` is `TRUE` or `FALSE`. Retrieves that users details.
  - `printBasket()` - Prints the users current basket., which is stored respectively in two `$_SESSION[]` arrays (`$_SESSION['mainOrder']` and `$_SESSION['sideOrder']`)
  - `printFinal()` - Same as above, but used for Payment page.
  - `checkOrders()` - checks if any orders are available for staff members to process. Returns `TRUE` if there is, `FALSE` if there isn't. If `TRUE`, triggers the next function.
  - `printCustomerOrders()` - prints any and all orders which are available and haven't been processed already. Queries `ORDERS` table.
  - `getOrderItemsFromOrderID($id)` - pass through `$id` of item in users basket, details of that item are then returned. Queries `ORDER_ITEMS` table.
  - `getProductFromForeignKey($id)` - pass through `$id` of item in users basket, details of that item are then returned. Queries `PRODUCT` table.
  - `returnCurrID()` - returns the largest OrderID (current).
  - `checkOrders()` - returns `true` if incomplete orders exist, `false` if not
  
 ##  Example database structure:
 ```sql
-- Database: c3518706
--
CREATE DATABASE IF NOT EXISTS c3518706 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE c3518706;

-- --------------------------------------------------------
--
-- Table structure for table customer
--

CREATE TABLE customer (
  User_ID int(11) NOT NULL,
  User_Forname varchar(30) NOT NULL,
  User_Surname varchar(30) NOT NULL,
  EmailAddress varchar(50) NOT NULL,
  Password varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Table structure for table orders
--

CREATE TABLE orders (
  Order_ID int(11) NOT NULL,
  Order_Total decimal(10,2) NOT NULL,
  Order_Timestamp timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  Order_Complete char(1) NOT NULL,
  Table_No int(2) NOT NULL,
  fk1_User_ID int(11) NOT NULL,
  fk2_Staff_ID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Table structure for table order_items
--

CREATE TABLE order_items (
  Order_Items_ID int(11) NOT NULL,
  Quantity int(11) NOT NULL,
  fk1_Product_ID int(11) NOT NULL,
  fk2_Order_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Table structure for table product
--

CREATE TABLE product (
  Product_ID int(11) NOT NULL,
  Product_Name varchar(30) NOT NULL,
  Product_Cat varchar(20) NOT NULL,
  Price decimal(6,2) NOT NULL,
  Product_Image varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table product
--

INSERT INTO product VALUES
(1, 'Caffe Latte', 'Coffee_Latte', '3.50', 'Latte1-min.jpg'),
(2, 'Flat White', 'Coffee_Latte', '4.00', 'Latte2-min.jpg'),
(3, 'Caffe Mocha', 'Coffee_Mocha', '3.50', 'Mocha1-min.jpg'),
(4, 'White Chocolate Mocha', 'Coffee_Mocha', '3.50', 'Mocha2-min.jpg'),
(5, 'Latte Macchiato', 'Coffee_Macchiato', '3.20', 'Macchiato1-min.jpg'),
(6, 'Caramel Macchiato', 'Coffee_Macchiato', '2.40', 'Macchiato2-min.jpg'),
(7, 'Cappuccino', 'Coffee_Cappuccino', '3.00', 'Cappucino1-min.jpg'),
(8, 'Caffe Americano', 'Coffee_Americano', '3.30', 'Americano1-min.jpg'),
(9, 'Cortado', 'Coffee_Espresso', '3.20', 'Espresso1-min.jpg'),
(10, 'Espresso', 'Coffee_Espresso', '4.50', 'Espresso2-min.jpg'),
(11, 'Espresso Macchiato', 'Coffee_Espresso', '3.40', 'Espresso3-min.jpg'),
(12, 'Banana Bread', 'Bakery', '1.20', 'Bakery1-min.jpg'),
(13, 'Blueberry Muffin', 'Bakery', '1.00', 'Bakery2-min.jpg'),
(14, 'Caramel Shortbread', 'Bakery', '0.90', 'Bakery3-min.jpg'),
(15, 'Carrot Cake', 'Bakery', '1.20', 'Bakery4-min.jpg'),
(16, 'Lemon Loaf', 'Bakery', '0.90', 'Bakery6-min.jpg'),
(17, 'Triple Choc Muffin', 'Bakery', '0.70', 'Bakery7-min.jpg');

-- --------------------------------------------------------
--
-- Table structure for table staff
--

CREATE TABLE staff (
  Staff_ID int(11) NOT NULL,
  Email_Address varchar(30) NOT NULL,
  Password varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table staff
--
-- Default password = DefaultPassword01, though can be changed through account page.
INSERT INTO staff VALUES
(0, 'admin@coffeeshack.org', '$2y$10$bpVsjeSQDyWrOe5e8eLYb.uJQME5XtRMgbvvvCbMSk5ju7WhBjlMy');

-- --------------------------------------------------------
--
-- Indexes for dumped tables
--

--
-- Indexes for table customer
--
ALTER TABLE customer
  ADD UNIQUE KEY User_ID (User_ID);

--
-- Indexes for table orders
--
ALTER TABLE orders
  ADD UNIQUE KEY Order_ID (Order_ID);

--
-- Indexes for table order_items
--
ALTER TABLE order_items
  ADD UNIQUE KEY Order_Items_ID (Order_Items_ID);

--
-- Indexes for table product
--
ALTER TABLE product
  ADD UNIQUE KEY Product_ID (Product_ID);

--
-- Indexes for table staff
--
ALTER TABLE staff
  ADD UNIQUE KEY Staff_ID (Staff_ID);
  
-- --------------------------------------------------------
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table customer
--
ALTER TABLE customer
  MODIFY User_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table orders
--
ALTER TABLE orders
  MODIFY Order_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table order_items
--
ALTER TABLE order_items
  MODIFY Order_Items_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
```
  
## **Oracle source code has been implemented.**
- You can find this in the `coffee-shack-source-oracle` folder, however i no longer have access to an installation of Oracle Apex - as this was hosted locally at my University, thus i am not able to work on that specific code-base without being able to debug/work on the database.

## Disclaimer 
This website template is not endorsed by, directly affiliated with, maintained, authorized, or sponsored by any of the products that i have used as placeholders for this website template. All product and company names are the registered trademarks of their original owners. The use of any trade name or trademark is for identification and reference purposes only and does not imply any association with the trademark holder of their product brand.
##
### Initial commit: 21/11/2018 by Luke Elam (@dstlny)
### Latest commit: 01/07/2019 by Luke Elam (@dstlny)
  
## Feature Screenshots.
![Login Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image01.PNG)
![Menu Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image02.PNG)
![Account Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image03.PNG)
![Basket Section](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image04.PNG)
![Payment Section](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image05.PNG)
![Staff Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image06.PNG)
![Order completion message](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image07.PNG)
![Current basket message](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image08.PNG)
