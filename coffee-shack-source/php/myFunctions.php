<?php
/**
 * Represents the Functions which are available to maintainers of the site.
 * @copyright  2019 Luke Elam
 **/ 
class myFunctions {
    
    /**
     * Returns the next avaialble Order ID.
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return string $row['AUTO_INCREMENT'] which represents the next Order ID.
     **/  
    public function returnNextID(){
        include '../dbcon/init.php';
        $query = "SELECT AUTO_INCREMENT 
                  FROM information_schema.tables
                  WHERE table_name = 'ORDERS'
                  and table_schema = 'c3518706';";

        $result = $sqli->query($query);
        $row = $result->fetch_assoc();
        $sqli->close();
        return $row['AUTO_INCREMENT'];
    }
 
    /**
     * Returns the status of a given Order ID.
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return string $row['ORDER_COMPLETE'] - Y or N
     **/ 
    public function getOrderStatus($id){
       include '../dbcon/init.php';
       $query = "SELECT ORDER_COMPLETE FROM ORDERS WHERE ORDER_ID = $id";
       $result = $sqli->query($query);
       $row = $result->fetch_assoc();
       $sqli->close();
       return $row['ORDER_COMPLETE'];
   }
    
    /**
     * Inserts users order into ORDERS table
     * 
     * @param float $total - orders total
     * @param int $table - table selected by the user
     * @param timestamp $current_timestamp - timestamp of when the order was created
     * @param int $userID - Users unique ID
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function insertOrders($total,$table,$current_timestamp,$userID){
        include '../dbcon/init.php';
        $query = "INSERT INTO ORDERS (Order_ID, Order_Total, Table_No, Order_Timestamp, Order_Complete, fk1_User_ID, fk2_Staff_ID) VALUES ('',$total,$table,'$current_timestamp', 'N', $userID, NULL)";
        $sqli->query($query);
        $sqli->close();
    }
    
    /**
     * Inserts users order items into ORDER_ITEMS table
     * 
     * @param int $qty - quantity of a given item
     * @param int $prod_id - ID of a given product
     * @param int $order_id - Orders unique ID
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function insertOrderItems($qty,$prod_id,$order_id){
        include '../dbcon/init.php';
        $query = "INSERT INTO ORDER_ITEMS (Order_Items_ID, Quantity, fk1_Product_ID, fk2_Order_ID) VALUES ('',$qty,$prod_id, $order_id)";
        $sqli->query($query);
        $sqli->close();
    }
    
    /**
     * Prints the items in a given category
     * 
     * @param string $category - items category
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function printItems($category){
        include '../dbcon/init.php';
        $query  = "SELECT * FROM PRODUCT WHERE Product_Cat = '$category';";
        $result = $sqli->query($query);
        $i = 0;
        echo "\n\n<!-- TABLE HTML CODE START -->\n<!-- TABLE HEADER START -->\n<table><tr><th>Name</th><th>Price</th></tr>\n<!-- TABLE HEADER END -->\n\n";
        
        while ($row = $result->fetch_assoc()) {
            echo "\n<!-- TABLE ROW {$i} START -->\n<tr><td>{$row['Product_Name']}</td>\n<td>&pound;{$row['Price']}</td>\n<td><img align=\"middle\" src=\"../images/{$row['Product_Image']}\"/></td>\n";
            if($category == "Bakery"){
                echo "<td><form method=\"get\" action=\"?bkID={$_GET['bkID']}&bkQTY={$_GET['bkQty']}\"><select name=\"bkQty\"><option value=\"1\">1</option>
                            <option value=\"2\">2</option>
                            <option value=\"3\">3</option>
                            <option value=\"4\">4</option>
                      </select><input type=\"hidden\" name=\"bkID\" value=\"{$row['Product_ID']}\"><button type=\"submit\" class=\"buttonAsLink\">Add to basket</button></form></td>\n<!-- TABLE ROW {$i} END -->\n";$i++;
            } else {
                echo "<td><form method=\"get\" action=\"?id={$_GET['id']}&qty={$_GET['qty']}\">
                          <select name=\"qty\">
                              <option value=\"1\">1</option>
                              <option value=\"2\">2</option>
                              <option value=\"3\">3</option>
                              <option value=\"4\">4</option>
                          </select><input type=\"hidden\" name=\"id\" value=\"{$row['Product_ID']}\"><button type=\"submit\" class=\"buttonAsLink\">Add to basket</button></form></td>\n<!-- TABLE ROW {$i} END -->\n";$i++;
            }
        }
        echo "\n<!-- TABLE FOOTER START -->\n</table>\n<!-- TABLE FOOTER END -->\n<!-- TABLE HTML CODE END -->\n\n";
        $sqli->close();
    }
    
    /**
     * Retrieves the users details
     * 
     * @param string $email - users email
     * @param boolean $admin - whether the user is an admin or not.
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function getUserDetails($email, $admin){
        include '../dbcon/init.php';

        if($admin){
            $query  = "SELECT Email_Address FROM STAFF WHERE Email_Address = '$email';";
            $result = $sqli->query($query);
            $row = $result->fetch_assoc();
            echo "<div class=\"userDetails\"><form><fieldset><legend>Stored details</legend><label for=\"Email\">Staff Email</label><input type=\"text\" name=\"Email\" value=\"{$row['Email_Address']}\" readonly></form></div><center><p style=\"font-size:13px;\">Want to logout? <a href=\"../php/logout.php\" style=\"font-size:13px;\">Logout</a></p></center>";
            $result->close();
        } else{
            $query  = "SELECT EmailAddress, User_Forname, User_Surname FROM CUSTOMER WHERE EmailAddress = '$email';";
            $result = $sqli->query($query);
            $row = $result->fetch_assoc();
            echo "<div class=\"userDetails\"><form><fieldset><legend>Stored details</legend><label for=\"Email\">Email</label><input type=\"text\" name=\"Email\" 
            value=\"{$row['EmailAddress']}\" readonly><br><hr><label for=name\">Fullname</label><input type=\"text\" name=\"name\" value=\"{$row['User_Forname']} {$row['User_Surname']}\" readonly></fieldset></form><center><p style=\"font-size:13px;\">Want to logout? <a href=\"../php/logout.php\" style=\"font-size:13px;\">Logout</a></p></center></div>";
            $result->close();
        }
        $sqli->close();
    }
    
    /**
     * Prints the users current basket
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function printBasket(){
        include '../dbcon/init.php';

        if(!isset($_SESSION['orderID'])){
            $_SESSION['orderID'] = $this->returnNextID();
        }

        echo "<div class=\"userDetails\" style=\"margin-top: 0px;\"><div><p><p style=\"font-size: 15px;\">Order ID: {$_SESSION['orderID']}</p><br>Please select your table number: </p><form method=\"post\" action=\"../pages/payment.php\"><select name=\"tblNo\">";
        for($i=1; $i < 15; $i++){
            echo "<option value=\"{$i}\">{$i}</option>";
        }
        echo '</select>'; 
            
        if(!empty($_SESSION['mainOrder'])){
            for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
                $query = "SELECT * FROM PRODUCT WHERE Product_ID={$_SESSION['mainOrder'][$i]['_product_id']};";
                $result = $sqli->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<p>{$_SESSION['mainOrder'][$i]['_product_qty']} x {$row['Product_Name']}&emsp;&emsp;&emsp;&pound;".number_format($_SESSION['mainOrder'][$i]['_product_qty'] * $row['Price'], 2)."</p>";
                    $total += $row['Price'] * $_SESSION['mainOrder'][$i]['_product_qty'];
                }
           }
           $result->close();
        }
    
        if(!empty($_SESSION['sideOrder'])){
            for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
                $query = "SELECT * FROM PRODUCT WHERE Product_ID={$_SESSION['sideOrder'][$i]['_product_id']};";
                $result = $sqli->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<p>{$_SESSION['sideOrder'][$i]['_product_qty']} x {$row['Product_Name']}&emsp;&emsp;&emsp;&emsp;&pound;".number_format($_SESSION['sideOrder'][$i]['_product_qty'] * $row['Price'], 2)."</p>";
                    $total += $row['Price'] * $_SESSION['sideOrder'][$i]['_product_qty'];
                }
            }
            $result->close();
        }
        $_SESSION['orderTotal'] = $total;
        echo '<br><center><a style="color: black;" href="?ClearAll">Remove all</a></center><p style="font-size: 15px; border-right: 1px solid black; border-left: 1px solid black;"><b>Total: £'.number_format($total, 2).'</b></p><button type="submit" class="buttonAsLink">Pay Now!</button><br><br></div></div>';
        $sqli->close();
    }
    
    /**
     * Prints the users FINAL order
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function printFinal(){
        include '../dbcon/init.php';
        
        echo '<div class="basket"><div>';
        
        if(!empty($_SESSION['mainOrder'])){
            for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
                $query = "SELECT * FROM PRODUCT WHERE Product_ID={$_SESSION['mainOrder'][$i]['_product_id']};";
                $result = $sqli->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<p>{$_SESSION['mainOrder'][$i]['_product_qty']} x {$row['Product_Name']}&emsp;&emsp;&emsp;&pound;".number_format($_SESSION['mainOrder'][$i]['_product_qty'] * $row['Price'], 2)."</p>";
                    $total += $row['Price'] * $_SESSION['mainOrder'][$i]['_product_qty'];
                }
           }
           $result->close();
        }
    
        if(!empty($_SESSION['sideOrder'])){
            for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
                $query = "SELECT * FROM PRODUCT WHERE Product_ID={$_SESSION['sideOrder'][$i]['_product_id']};";
                $result = $sqli->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<p>{$_SESSION['sideOrder'][$i]['_product_qty']} x {$row['Product_Name']}&emsp;&emsp;&emsp;&emsp;&pound;".number_format($_SESSION['sideOrder'][$i]['_product_qty'] * $row['Price'], 2)."</p>";
                    $total += $row['Price'] * $_SESSION['sideOrder'][$i]['_product_qty'];
                }
            }
            $result->close();
        }
        echo '<p style="font-size: 15px;"><b>Total: £'.number_format($total, 2).'</b></p>';
        echo '</div></div>';
        $sqli->close();
    }
    
    /**
     * Checks to see if an orders are outstanding.
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return TRUE if there is
     * @return FALSE if there isn't
     **/
    public function checkOrders(){
        include '../dbcon/init.php';
        $query = "SELECT * FROM ORDERS WHERE Order_Complete='N';";
        $result = $sqli->query($query);
        return $result->num_rows == 0 ? FALSE : TRUE;
    }
    
    /**
     * Prints all customer orders on the staff page.
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function printCustomerOrders(){
        include '../dbcon/init.php';
        $query = "SELECT Order_ID, Order_Total FROM ORDERS WHERE Order_Complete='N';";
        $result = $sqli->query($query);
        $user_object = unserialize($_SESSION['user']);
       
        echo '<div class="staff-wrapper">';
            while ($row = $result->fetch_assoc()) {
                    echo "<div id=\"staff-div\">
                            <form method=\"post\" action=\"../php/updateOrder.php\">
                                <input type=\"hidden\" name=\"orderID\" value=\"{$row['Order_ID']}\">
                                <input type=\"hidden\" name=\"staffID\" value=\"{$user_object->_userID}\">
                            <h3 style=\"font-size: 16px; font-weight: bold;\">Order {$row['Order_ID']}&emsp;&emsp;Total: &pound;".number_format($row['Order_Total'],2)."<br>[Not complete]</h3>
                            <hr>
                            <div>";
                                    $this->getOrderItemsFromOrderID($row['Order_ID']);
                        echo '<button type="submit" style="font-size:16px; margin-bottom:15px;" name="subOrder" class="buttonAsLink">Complete Order</button></form></div>
                        </div>';
            }
            echo '</div>';
        $result->close();
        $sqli->close();
    }
    
    /**
     * Retrieves all the items which correlate to an Order ID.
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return nothing
     **/
    public function getOrderItemsFromOrderID($id){
        include '../dbcon/init.php';
        $query = "SELECT fk1_Product_ID, Quantity FROM ORDER_ITEMS WHERE fk2_Order_ID = $id;";
        $result = $sqli->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<p style=\"line-height: 0.5; font-size: 10pt\">{$row['Quantity']} x {$this->getProductFromForeignKey($row['fk1_Product_ID'])}</p>";
        }
        $result->close();
        $sqli->close();
    }
    
    /**
     * Retrieves the products name which correlates to the order.
     * 
     * @param int $id - products ID
     * 
     * @author dstlny <https://github.com/dstlny>
     * @return string $row['Product_Name'] - the products name from a given Product ID.
     **/
    public function getProductFromForeignKey($id){
        include '../dbcon/init.php';
        $query = "SELECT Product_Name FROM PRODUCT WHERE Product_ID = $id;";
        $result = $sqli->query($query);
        $row = $result->fetch_assoc();
        $result->close();
        $sqli->close();
        return $row['Product_Name'];
    }   
}
?>
