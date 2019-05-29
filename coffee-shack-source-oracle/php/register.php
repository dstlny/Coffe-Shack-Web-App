<?php
include '../dbcon/init.php';
//If the register button has been clicked, proceed
    if(isset($_POST['register'])){
        //Error_check is an array that will store all the errors that the user can potentially have when registering
        $error_check = array();
        //Unsetting the $-SESSION[['errors'] array.
        unset($_SESSION['errors']);
        
        //If any of these are empty we know the form hasn't been filled in, simply redirect and show an error if that is the case.
        if(empty(trim($_POST['txtUserFirst'])) && empty(trim($_POST['txtUserLast'])) && empty(trim($_POST['txtPass'])) && empty(trim($_POST['txtPassRe'])) && empty(trim($_POST['txtEmail']))){
            
            $error_check['Allempty'] = "<br><b style=\"color: red; font-size: 12px;\">All fields are empty!</b>";
            $_SESSION['errors'] = $error_check;
            oci_free_statement($result);
			oci_close($connection);
			header("location: register-form.php");
            exit();
            
        } else { 

            // Validate users first name
            if(empty(trim($_POST['txtUserFirst']))){
                $error_check['firname'] = "<b style=\"color: red; font-size: 12px;\">Please enter your first name!</b>";
            } elseif(!ctype_alpha($_POST['txtUserFirst'])){
                $error_check['firname'] = "<b style=\"color: red; font-size: 12px;\">First name must be alphabetic characters only</b>";
            } 
            
            // Validate users second name
            if(empty(trim($_POST['txtUserLast']))){
                $error_check['secname'] = "<b style=\"color: red; font-size: 12px;\">Please enter your second name!</b>";
            } elseif(!ctype_alpha($_POST['txtUserLast'])){
                $error_check['secname'] = "<b style=\"color: red; font-size: 12px;\">Second name must be alphabetic characters only</b>";
            }

            // Validate password
            $password = trim($_POST['txtPass']);
            if(empty(trim($_POST['txtPass']))){
                $error_check['pass'] = "<b style=\"color: red; font-size: 12px;\">Password canot be empty!</b>";
            } elseif(!preg_match('((?=.*[A-Z])(?=.*[a-z])(?=.*\d).{7,21})', $password)){
                $error_check['pass'] = "<b style=\"color: red; font-size: 12px;\">Password is invalid. Password must include atleast ONE capital letter, a number and a symbol!</b>";
            }
            
             // Validate password confirmation
            $confirm_password = trim($_POST['txtPassRe']);
            if(empty(trim($_POST['txtPassRe']))){
                $error_check['passRe'] = "<b style=\"color: red; font-size: 12px;\">Please confirm your password!</b>";
            } elseif($password != $confirm_password){
                $error_check['passRe'] = "<b style=\"color: red; font-size: 12px;\">Passwords do not match!</b>";
            }
            
           
            // Validate email
            if(empty($_POST['txtEmail'])){
                $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email is empty!</b>";
            } elseif(!filter_var($_POST['txtEmail'], FILTER_VALIDATE_EMAIL)){
                $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email is of incorrect format!</b>";
            } else {
                // Prepare a select statement
                $query  = "SELECT * FROM CUSTOMER WHERE EMAILADDRESS = :email";
				$result = oci_parse($connection, $query);
				$mailuid = $_POST['txtEmail'];
				oci_bind_by_name($result, ':email', $mailuid);
				oci_execute($result);
					
				if(oci_num_rows($result) > 0){

				   $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email aleady registered!</b>";
                    $email = trim($_POST['txtEmail']);
                    $_SESSION['email'] = $email;
                    $_SESSION['errors'] = $error_check;
                    oci_free_statement($result);
				    oci_close($connection);
					header("location: register-form.php");
                    exit();

				} else {

                    $email = trim($_POST['txtEmail']);
                    $_SESSION['email'] = $email;

                }
			}
        
            // Check input errors before inserting in database
            //This makes sure that the user doesn't get through if they have errors outstanding.
            if(empty($error_check)){

                // Prepare an insert statement
				$sql = "INSERT INTO CUSTOMER VALUES (CUSTOMER_SEQ.nextval,:for,:sur,:email,:hash)";
				$result = oci_parse($connection, $sql);
                $param_password = hash('sha512', $password).hash('sha384', $password).hash('whirlpool', $password);
                $param_email = $email;
				oci_bind_by_name($result, ':for', $_POST['txtUserFirst']);
				oci_bind_by_name($result, ':sur', $_POST['txtUserLast']);
				oci_bind_by_name($result, ':email', $email);
				oci_bind_by_name($result, ':hash', $param_password);
						
				if(oci_execute($result)){
                        
					$error_check['registration'] = '<p style="font-size:12px;" class="loading">Redirecting to menu<span>.</span><span>.</span><span>.</span></p>';
                    $_SESSION['errors'] = $error_check;
                    header("location: ../pages/home.php");
                    exit();
                        
				} else{

				    $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b><br>" . 
                    $_SESSION['errors'] = $error_check;
				    header("location: register-form.php");
                    exit();

                }
		
				//When all is done close the connection to the database and redirect the user to the appropriate page
                oci_free_statement($result);
				oci_close($connection);
                header("location: register-form.php");
                exit();

            } else {

                //If error_check is not empty, this triggers.
                //Session errors inheritfs the errors from the erro_check array.
                //user is redirected as appropriate
                $_SESSION['errors'] = $error_check;
                oci_free_statement($result);
				oci_close($connection);
				header("location: register-form.php");
                exit();  

            }
        }
    } else{

		oci_free_statement($result);
		oci_close($connection);
		header("location: register-form.php");
        exit();
        
	}
?>