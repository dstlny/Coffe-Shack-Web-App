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
  
## Feature Screenshots.
![Login Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image01.PNG)
![Menu Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image02.PNG)
![Account Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image03.PNG)
![Basket Section](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image04.PNG)
![Payment Section](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image05.PNG)
![Staff Page](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image06.PNG)
![Order completion message](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image07.PNG)
![Current basket message](https://raw.githubusercontent.com/dstlny/Project-Management-Web-App/master/images/image08.PNG)


## **Oracle source code has been implemented.**
- You can find this in the `coffee-shack-source-oracle` folder, however i no longer have access to an installation of Oracle Apex - as this was hosted locally at my University, thus i am not able to work on that specific code-base without being able to debug/work on the database.

## Disclaimer 
This website template is not endorsed by, directly affiliated with, maintained, authorized, or sponsored by any of the products that i have used as placeholders for this website template. All product and company names are the registered trademarks of their original owners. The use of any trade name or trademark is for identification and reference purposes only and does not imply any association with the trademark holder of their product brand.
##
### Initial commit: 21/11/2018 by Luke Elam (@dstlny)
### Latest commit: 01/07/2019 by Luke Elam (@dstlny)
