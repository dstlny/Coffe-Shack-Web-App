<?php
class myFunctions {

   public function returnNextID(){
       include '../dbcon/init.php';
       $stid = oci_parse($connection, "SELECT MAX(ORDER_ID)+1 AS MAXID FROM ORDERS");
       oci_execute($stid);
       $row = oci_fetch_assoc($stid);
       return $row['MAXID'];
   }

   public function getOrderStatus($id){
      include '../dbcon/init.php';
      $stid = oci_parse($connection, "SELECT * FROM ORDERS WHERE ORDER_ID = $id");
      oci_execute($stid);
      $row = oci_fetch_assoc($stid);
      return $row['ORDER_COMPLETE'];
  }

  public function returnCurrID(){
      include '../dbcon/init.php';
      $stid = oci_parse($connection, "SELECT MAX(ORDER_ID) AS MAXID FROM ORDERS");
      oci_execute($stid);
      $row = oci_fetch_assoc($stid);
      return $row['MAXID'];
  }

  public function step1($id,$total,$current_timestamp,$userID,$table,$staffID){
      include '../dbcon/init.php';
      $userID = $_SESSION['userID'];
      $query = "INSERT INTO ORDERS VALUES(ORDERS_SEQ.nextval,$table,$total,TIMESTAMP '$current_timestamp','N',$userID,NULL)";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
  }

  public function step2($id,$qty,$prod_id,$order_id){
      include '../dbcon/init.php';
      $query = "INSERT INTO ORDER_ITEMS VALUES (ORDER_ITEMS_SEQ.nextval,$qty,$prod_id, ORDERS_SEQ.currval)";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
  }

  public function printItems($category){
      include '../dbcon/init.php';
      $query  = "SELECT * FROM PRODUCT WHERE PRODUCT_CAT = '$category'";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
      $i = 0;
      echo "\n\n     <!-- TABLE HTML CODE START -->";
      echo "\n       <!-- TABLE HEADER START -->";
      echo "\n".'        <table><tr><th>Name</th><th>Price</th></tr>'."\n";
      echo "         <!-- TABLE HEADER END -->\n\n";

      while ($row = oci_fetch_assoc($stid)) {
        echo "\n           <!-- TABLE ROW ".$i." START -->\n";
        echo '              <tr><td>' . $row['PRODUCT_NAME'] .'</td>'."\n".'              <td>' .'&pound;'.number_format($row['PRICE'], 2). '</td>'."\n";
        echo "              <td><img src='../images/" . $row['PRODUCT_IMAGE'] . "'/></td>"."\n";
        if($category == "Bakery"){
          echo '<td><form method="get" action="#">
          <select name="bkQty">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          </select><input type="hidden" name="bkID" value="'.$row['PRODUCT_ID'].'">'; 
          echo '    <button type="submit" class="buttonAsLink">Add to basket</button></form></td>';
          echo "\n           <!-- TABLE ROW ".$i." END -->\n";$i++;
        } else {
          echo '<td><form method="get"  action="#">
          <select name="qty">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          </select><input type="hidden" name="id" value="'.$row['PRODUCT_ID'].'">'; 
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
      $query = "SELECT * FROM CUSTOMER WHERE EMAILADDRESS = '$email'";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
      $row = oci_fetch_assoc($stid);
      $rowcount = oci_num_rows($stid);

      if($rowcount == 0){
        $query  = "SELECT * FROM STAFF WHERE EMAIL_ADDRESS = '$email'";
        $stid = oci_parse($connection, $query);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        echo '<div class="userDetails"><form><fieldset><legend>Stored details</legend><label for="Email">Staff Email</label><input type="text" name="Email" value="'; 
        echo $row['EMAIL_ADDRESS'].'" readonly></form></div>';
        echo  '<center><p style="font-size:13px;">Want to logout? <a href="../php/logout.php" style="font-size:13px;">Logout</a></p></center>';
      } else{
        echo '<div class="userDetails"><form><fieldset><legend>Stored details</legend><label for="Email">Email</label><input type="text" name="Email" value="'; 
        echo $row['EMAILADDRESS'].'" readonly><br><hr><label for="name">Fullname</label><input type="text" name="name" value="'; 
        echo $row['USER_FORNAME'].' '.$row['USER_SURNAME'].'" readonly></fieldset></form>';
        echo  '<center><p style="font-size:13px;">Want to logout? <a href="../php/logout.php" style="font-size:13px;">Logout</a></p></center></div>';
      }

      oci_free_statement($stid);
      oci_close($connection);
  }

  public function printBasket(){
      include '../dbcon/init.php';

      if(!isset($_SESSION['orderID'])){
          $_SESSION['orderID'] = $this->returnNextID();
      }

      echo '<div class="userDetails" style="margin-top: 0px;"><div>';
      echo '<p><p style="font-size: 15px;">Order ID: '.$_SESSION['orderID'].'</p><br>Please select your table number: </p><form method="post" action="../pages/payment.php">
      <select name="tblNo">';
      for($i=1; $i < 15; $i++){
        echo '<option value="'.$i.'">'.$i.'</option>';
      }

      echo '</select>'; 
      for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
        foreach($_SESSION['mainOrder'][$i] as $key=>$value){
         $query = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = $key";
         $stid = oci_parse($connection, $query);
         oci_execute($stid);
          while($row = oci_fetch_assoc($stid)) {
            echo '<p style="border-right: 1px solid black; border-left: 1px solid black;">'.$value.' x '. $row['PRODUCT_NAME']. '&emsp;&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['PRICE'], 2). '</p><hr>';
            $total += $row['PRICE'] * $value;
          }
        }
      }

      for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
        foreach($_SESSION['sideOrder'][$i] as $key=>$value){
         $query = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = $key";
         $stid = oci_parse($connection, $query);
         oci_execute($stid);
           while($row = oci_fetch_assoc($stid)) {
             echo '<p style="border-right: 1px solid black; border-left: 1px solid black;">'.$value.' x '. $row['PRODUCT_NAME']. '&emsp;&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['PRICE'], 2). '</p><hr>';
             $total += $row['PRICE'] * $value;
           }
        }
      }
      $_SESSION['orderTotal'] = $total;
      echo '<br><center><a style="color: black;" href="?ClearAll'.$row['PRODUCT_ID'].'">Remove all</a></center><p style="font-size: 15px; border-right: 1px solid black; border-left: 1px solid black;"><b>Total: £'.number_format($total, 2).'</b></p><button type="submit" class="buttonAsLink">Pay Now!</button><br><br>';
      echo '</div></div>';
  }

  public function printFinal(){
      include '../dbcon/init.php';

      echo '<div class="basket"><div>';
      for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
        foreach($_SESSION['mainOrder'][$i] as $key=>$value){
         $query = "SELECT * FROM PRODUCT WHERE PRODUCT_ID  = $key";
         $stid = oci_parse($connection, $query);
         oci_execute($stid);
           while($row = oci_fetch_assoc($stid)) {
              echo '<p style="border-right: 1px solid black; border-left: 1px solid black;">'.$value.' x '. $row['PRODUCT_NAME']. '&emsp;&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['PRICE'], 2). '</p><hr>';
              $total += $row['PRICE'] * $value;
           }
        }
      }

      for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
        foreach($_SESSION['sideOrder'][$i] as $key=>$value){
         $query = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = $key";
         $stid = oci_parse($connection, $query);
         oci_execute($stid);
           while($row = oci_fetch_assoc($stid)) {
             echo '<p style="border-right: 1px solid black; border-left: 1px solid black;">'.$value.' x '. $row['PRODUCT_NAME']. '&emsp;&emsp;&emsp;&emsp;&pound;'.number_format($value*$row['PRICE'], 2). '</p><hr>';
             $total += $row['PRICE'] * $value;
           }
        }
      }
    echo '<p style="font-size: 15px; border-right: 1px solid black; border-left: 1px solid black;"><b>Total: £'.number_format($total, 2).'</b></p>';
    echo '</div></div>';
  }

  public function printCustomerOrders(){
      include '../dbcon/init.php';

      $query = "SELECT * FROM ORDERS WHERE ORDER_COMPLETE = 'N'";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
      echo '<div class="staff-wrapper">';
      while($row = oci_fetch_assoc($stid)) {
        			//echo '<center><p style="color: red; font-size: 20px;">Currently no customer orders to process!<br>Click <a href="../pages/menu.php">here</a> to go to the menu!</p></center>';
        echo '<div id="staff-div">
        <form method="post" action="../php/updateOrder.php">
        <input type="hidden" name="orderID" value="'.$row['ORDER_ID'].'">
        <input type="hidden" name="staffID" value="'.$_SESSION['adminID'].'">
        <h3 style="font-size: 16px; font-weight: bold;">Order '.$row['ORDER_ID'].'&emsp;&emsp;Total: £'.number_format($row['ORDER_TOTAL'],2).'<br>['.$row['ORDER_COMPLETE'].'ot complete]</h3>
        <hr>
        <div>';
        $this->getOrderItemsFromOrderID($row['ORDER_ID']);
        echo '<button type="submit" style="font-size:16px; margin-bottom:15px;" name="subOrder" class="buttonAsLink">Complete Order</button></form></div>
        </div>';
      }
      echo '</div>';
  }

  public function getOrderItemsFromOrderID($id){
      include '../dbcon/init.php';
      $query = "SELECT * FROM ORDER_ITEMS WHERE FK2_ORDER_ID = $id";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
      while($row = oci_fetch_assoc($stid)) 
        echo '<p style="line-height: 0.5; font-size: 10pt" >'.$row['QUANTITY'].' x '. $this->getProductFromForeignKey($row['FK1_PRODUCT_ID']).'</p>';
  }

  public function getProductFromForeignKey($id){
      include '../dbcon/init.php';
      $query = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = $id";
      $stid = oci_parse($connection, $query);
      oci_execute($stid);
      while($row = oci_fetch_assoc($stid)) 
        return $row['PRODUCT_NAME'];

      $this->printCustomerOrders();
  }
	
}
?>
