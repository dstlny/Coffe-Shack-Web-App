<?php
class myFunctions {
    
    public function step1($id,$total,$current_timestamp,$complete,$userID,$staffID){
        include '../dbcon/init.php';
        $query = "INSERT INTO ORDERS (Order_ID, Order_Total, Order_Timestamp, Order_Complete, fk1_User_ID, fk2_Staff_ID) VALUES ('',$total,'$current_timestamp', 'N', $userID, NULL)";
        mysqli_query($connection, $query);
    }
    
    public function step2($id,$qty,$prod_id,$order_id){
        include '../dbcon/init.php';
        $query = "INSERT INTO ORDER_ITEMS (Order_Items_ID, Quantity, fk1_Product_ID, fk2_Order_ID) VALUES ('',$qty,$prod_id, $order_id)";
        mysqli_query($connection, $query);
    }
    
    public function printItems($category){
        include '../dbcon/init.php';
        $query  = "SELECT * FROM PRODUCT WHERE Product_Cat = '$category';";
        $result = mysqli_query($connection, $query);
        $i = 0;
        echo "\n\n     <!-- TABLE HTML CODE START -->";
        echo "\n       <!-- TABLE HEADER START -->";
        echo "\n".'        <table><tr><th>Name</th><th>Price</th></tr>'."\n";
        echo "         <!-- TABLE HEADER END -->\n\n";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "\n           <!-- TABLE ROW ".$i." START -->\n";
            echo '              <tr><td>' . $row['Product_Name'] .'</td>'."\n".'              <td>' .'&pound;'.$row['Price'] . '</td>'."\n";
            echo "              <td><img align="."middle"." src='../images/" . $row['Product_Image'] . "'/></td>"."\n";
            if($category == "Bakery"){
                echo '<td><form method="get" action="?id='.$_GET['bkID'].'&qty='.$_GET['bkQty'].'">
                          <select name="bkQty">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                          </select><input type="hidden" name="bkID" value="'.$row['Product_ID'].'">'; 
                echo '    <button type="submit" class="buttonAsLink">Add to basket</button></form></td>';
                echo "\n           <!-- TABLE ROW ".$i." END -->\n";$i++;
            } else {
                echo '<td><form method="get" action="?id='.$_GET['id'].'&qty='.$_GET['qty'].'">
                          <select name="qty">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                          </select><input type="hidden" name="id" value="'.$row['Product_ID'].'">'; 
                echo '              <button type="submit" class="buttonAsLink">Add to basket</button></form></td>';
                echo "\n           <!-- TABLE ROW ".$i." END -->\n";$i++;
            }
        }
        echo "\n     <!-- TABLE FOOTER START -->\n";
        echo '              </table>';
        echo "\n       <!-- TABLE FOOTER END -->\n";
        echo "     <!-- TABLE HTML CODE END -->\n\n";
    }
    
    public function getUserDetails($email){
        include '../dbcon/init.php';
        $query  = "SELECT * FROM CUSTOMER WHERE EmailAddress = '$email'";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $rowcount = mysqli_num_rows($result);
        
        if($rowcount == 0){
            $query  = "SELECT * FROM STAFF WHERE Email_Address = '$email'";
            $result = mysqli_query($connection, $query);
            $row = mysqli_fetch_assoc($result);
            echo '<div class="userDetails"><form><fieldset><legend>Stored details</legend><label for="Email">Staff Email</label><input type="text" name="Email" value="'; 
            echo $row['Email_Address'].'" readonly></form></div>';
            echo  '<center><p style="font-size:13px;">Want to logout? <a href="../php/logout.php" style="font-size:13px;">Logout</a></p></center>';
        } else {
            echo '<div class="userDetails"><form><fieldset><legend>Stored details</legend><label for="Email">Email</label><input type="text" name="Email" value="'; 
            echo $row['EmailAddress'].'" readonly><br><hr><label for="name">Fullname</label><input type="text" name="name" value="'; 
            echo $row['User_Forname'].' '.$row['User_Surname'].'" readonly></fieldset></form>';
            echo  '<center><p style="font-size:13px;">Want to logout? <a href="../php/logout.php" style="font-size:13px;">Logout</a></p></center></div>';
        }
    }
    
    public function printBasket(){
        include '../dbcon/init.php';
        
        echo '<div class="basket"><div>';
        if(!empty($_SESSION['mainOrder'])){
            for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
                foreach($_SESSION['mainOrder'][$i] as $key=>$value){
                       $query = "SELECT * FROM PRODUCT WHERE Product_ID='$key'";
                       $result = mysqli_query($connection, $query);
                       while ($row = mysqli_fetch_assoc($result)) {
                              echo '<p>'.$value.' x '. $row['Product_Name']. '&emsp;&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['Price'], 2). '</p>';
                              $total += $row['Price'] * $value;
                       }
                }
           }
        }
    
        if(!empty($_SESSION['sideOrder'])){
            for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
                foreach($_SESSION['sideOrder'][$i] as $key=>$value){
                     $query = "SELECT * FROM PRODUCT WHERE Product_ID='$key'";
                     $result = mysqli_query($connection, $query);
                         while ($row = mysqli_fetch_assoc($result)) {
                             echo '<p>'.$value.' x '. $row['Product_Name']. '&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['Price'], 2). '</p>';
                             $total += $row['Price'] * $value;
                         }
                }
            }
        }
        $_SESSION['orderTotal'] = $total;
        echo '<center><a style="color: black;" href="?ClearAll'.$row['Product_ID'].'">Remove all</a></center><p style="font-size: 15px;"><b>Total: £'.number_format($total, 2).'</b></p><a href="../pages/payment.php">Pay now!</a><br><br>';
        echo '</div></div>';
    }
    
     public function printFinal(){
        include '../dbcon/init.php';
        
        echo '<div class="basket"><div>';
        
       if(!empty($_SESSION['mainOrder'])){
            for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
                foreach($_SESSION['mainOrder'][$i] as $key=>$value){
                       $query = "SELECT * FROM PRODUCT WHERE Product_ID='$key'";
                       $result = mysqli_query($connection, $query);
                       while ($row = mysqli_fetch_assoc($result)) {
                              echo '<p>'.$value.' x '. $row['Product_Name']. '&emsp;&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['Price'], 2). '</p>';
                              $total += $row['Price'] * $value;
                       }
                }
           }
        }
    
        if(!empty($_SESSION['sideOrder'])){
            for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
                foreach($_SESSION['sideOrder'][$i] as $key=>$value){
                     $query = "SELECT * FROM PRODUCT WHERE Product_ID='$key'";
                     $result = mysqli_query($connection, $query);
                         while ($row = mysqli_fetch_assoc($result)) {
                             echo '<p>'.$value.' x '. $row['Product_Name']. '&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['Price'], 2). '</p>';
                             $total += $row['Price'] * $value;
                         }
                }
            }
        }
        echo '<p style="font-size: 15px;"><b>Total: £'.number_format($total, 2).'</b></p>';
        echo '</div></div>';
    }
    
    public function printCustomerOrders(){
        include '../dbcon/init.php';
        $query = "SELECT * FROM ORDERS WHERE Order_Complete='N';";
        $result = mysqli_query($connection, $query);
        echo '<div class="staff-wrapper">';
        while($row = mysqli_fetch_assoc($result)) {
                echo '<div id="staff-div">
                           <form method="post" action="../php/updateOrder.php">
                               <input type="hidden" name="orderID" value='.$row['Order_ID'].'>
                               <input type="hidden" name="staffID" value='.$_SESSION['adminID'].'>
                           <h3 style="font-size: 16px; font-weight: bold;">Order '.$row['Order_ID'].'&emsp;&emsp;Total: £'.number_format($row['Order_Total'],2).'<br>['.$row['Order_Complete'].'ot complete]</h3>
                           <hr>
                           <div>';
                                $this->getOrderItemsFromOrderID($row['Order_ID']);
                    echo '<button type="submit" style="font-size:16px; margin-bottom:15px;" name="subOrder" class="buttonAsLink">Complete Order</button></form></div>
                      </div>';
        }
        echo '</div>';
    }
    
    public function getOrderItemsFromOrderID($id){
        include '../dbcon/init.php';
        $query = "SELECT * FROM ORDER_ITEMS WHERE FK2_ORDER_ID = $id;";
        $result = mysqli_query($connection, $query);
        while($row = mysqli_fetch_assoc($result)) {
            echo '<p style="line-height: 0.5;   font-size: 10pt" >'.$row['Quantity'].' x '. $this->getProductFromForeignKey($row['fk1_Product_ID']).'</p>';
        }

    }
    
    public function getProductFromForeignKey($id){
        include '../dbcon/init.php';
        $query = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = $id;";
        $result = mysqli_query($connection, $query);
        while($row = mysqli_fetch_assoc($result)) {
            return $row['Product_Name'];
        }
        $this->printCustomerOrders();
    }
    
}
?>