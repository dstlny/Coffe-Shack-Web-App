<?php
include '../dbcon/init.php';
if (isset($_POST['subLogin'])) {
    
    $mailuid = $_POST['txtLogEmail'];
    $pass = $_POST['txtLogPass'];
    $_SESSION['errors'] = array();
    $hash = hash('sha512', $pass).hash('sha384', $pass).hash('whirlpool', $pass);
    
    if(empty($mailuid) && empty($pass)){
        $_SESSION['errors']['loginEmpty'] = "<br><b style=\"color: red; font-size: 12px;\">Login fields cannot be empty!</b>";
        oci_free_statement($result);
		oci_close($connection);
		header("location: ../pages/home.php");
        exit();
    } else{
        if(empty($mailuid)){
            $_SESSION['errors']['email'] = "<b style=\"color: red; font-size: 12px;\">Please enter a email address!</b>";
            oci_free_statement($result);
			oci_close($connection);
			header("location: ../pages/home.php");
            exit();
        } elseif(empty($pass)){
            $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Please enter a password!</b>";
            oci_free_statement($result);
			oci_close($connection);
			header("location: ../pages/home.php");
            exit();
        } else {
        
         //check customer exists
         $query  = "SELECT * FROM CUSTOMER WHERE EmailAddress = '$mailuid'";
         $result = oci_parse($connection, $query);
		 
         if(oci_execute($result)){
             if($row = oci_fetch_assoc($result)){
                 if($hash == $row['PASSWORD']){
                    
                    $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
                    $_SESSION['loggedIn'] = TRUE;
                    $_SESSION['userName'] = $row['EMAILADDRESS'];
                    $_SESSION['userID'] = $row['USER_ID'];
					oci_free_statement($result);
					oci_close($connection);
                    header("location: ../pages/home.php");
                    exit();
                    
                 } else{
                     
                    $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                    oci_free_statement($result);
					oci_close($connection);
					header("location: ../pages/home.php");
                    exit();
                    
                 }
             } else{
                //check staff/admin exists
                $query  = "SELECT * FROM STAFF WHERE Email_Address = '$mailuid'";
                $result = oci_parse($connection, $query);
                
                if(oci_execute($result)){
                    if($row = oci_fetch_assoc($result)){
                        if($hash == $row['PASSWORD']){
                            $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
                            $_SESSION['adminLoggedIn'] = TRUE;
                            $_SESSION['adminUserName'] = $row['EMAIL_ADDRESS'];
                            $_SESSION['adminID'] = $row['STAFF_ID'];
        					oci_free_statement($result);
        					oci_close($connection);
                            header("location: ../pages/home.php");
                            exit();
                        } else{
                            $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                            oci_free_statement($result);
        					oci_close($connection);
        					header("location: ../pages/home.php");
                            exit();
                         }
                    } else{
                        //Customer nor Staff exists with this email
                        $_SESSION['errors']['noRecog'] = "<b style=\"color: red; font-size: 12px;\">Account not recognised!</b>";
                        oci_free_statement($result);
        				oci_close($connection);
        				header("location: ../pages/home.php");
                        exit();
                    }
                } else{
                    $_SESSION['errors']['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error has occured!</b>";
                    oci_free_statement($result);
        			oci_close($connection);
        			header("location: ../pages/home.php");
                    exit();
                }
             }
           } else{
               $_SESSION['errors']['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error has occured!</b>";
               oci_free_statement($result);
			   oci_close($connection);
			   header("location: ../pages/home.php");
               exit();
           }
        }
    }
} else{
    oci_free_statement($result);
	oci_close($connection);
	header("location: ../pages/home.php");
    exit();
}
?>