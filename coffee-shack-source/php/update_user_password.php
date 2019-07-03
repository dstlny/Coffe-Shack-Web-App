<?php
if(isset($_POST['sub_pass'])){
    
    if($user_object->_admin){
        $query = "SELECT Password FROM STAFF WHERE Email_Address=? AND Staff_ID=?";
    } else {
        $query  = "SELECT Password FROM CUSTOMER WHERE EmailAddress=? AND User_ID=?";
    }

    if($stmt = $sqli->prepare($query)){

        $stmt->bind_param("ss", $user_object->_mailuid, $user_object->_userID);

        if($stmt->execute()){

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if(password_verify($_POST['Current'], $row['Password'])){

                if($_POST['New_Confirm_Password'] != $_POST['New_Pass']){
                    echo '<p style="color:red">New password and new password confirmation do not match!</p>';
                } else {

                    $hash = password_hash($_POST['New_Pass'], 1);
                
                    if($user_object->_admin){
                        $query = "UPDATE STAFF SET Password = '$hash' WHERE Email_Address = '$user_object->_mailuid' AND Staff_ID = $user_object->_userID;";
                    } else {
                        $query = "UPDATE CUSTOMER SET Password = '$hash' WHERE EmailAddress = '$user_object->_mailuid' AND User_ID = $user_object->_userID;";
                    }

                    $sqli->query($query);
                    $sqli->close();
                    echo '<p style="color:green;">Password updated successfully!</p>';

                }

            } else{
                echo '<p style="color:red;">The password you entered does not match the password in our database for your account!</p>';
            } 
        } else{
            echo  "<b style=\"color: red; font-size: 12px;\">An SQL error has occured!</b>";
        }
    }
}
?>