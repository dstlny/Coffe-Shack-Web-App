<?php
include '../dbcon/init.php';
//If the bregister button has been clicked, proceed
    if(isset($_POST['register'])){
        //Error_check is an array that wiull store all the errors that the user can potentially have when registering
        $error_check = array();
        //Unsetting the $-SESSION[['errors'] array.
        unset($_SESSION['errors']);
        
        //If any of these are empty we know the form hasn't been filled in, simply redirect and show an error if that is the case.
        if(empty(trim($_POST['txtUserFirst'])) && empty(trim($_POST['txtUserLast'])) && empty(trim($_POST['txtPass'])) 
        && empty(trim($_POST['txtPassRe'])) && empty(trim($_POST['txtEmail']))){
            $error_check['Allempty'] = "<br><b style=\"color: red; font-size: 12px;\">All fields are empty!</b>";
            $_SESSION['errors'] = $error_check;
            header("location: register-form.php");
            exit();
        } 
        
        else { 

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
            
            
            else {
                // Prepare a select statement
                $sql = "SELECT * FROM CUSTOMER WHERE User_Forname = ? AND User_Surname = ?";
                
                if($stmt = mysqli_prepare($connection, $sql)){
                    // Bind variables to the prepared statement as parameters and set parameters
                    $firstname=$_POST['txtUserFirst'];
                    $lastname=$_POST['txtUserLast'];
                    // Bind the variables
                    mysqli_stmt_bind_param($stmt, "ss", $firstname, $lastname);
                    //Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        //Storing results in the $stmt variuable
                        mysqli_stmt_store_result($stmt);
                         //Checking if we have any hits from the DB, if we do a user exists with that email, else they don't. Erro as according.
                        if(mysqli_stmt_num_rows($stmt) > 0){
                              $error_check['firsecname'] = "<b style=\"color: red; font-size: 12px;\">A user with this frst and second name already exists!</b>";
                              $_SESSION['errors'] = $error_check;
                              header("location: register-form.php");
                              exit();
                        }
                    } else {
                         $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b>";
                         $_SESSION['errors'] = $error_check;
                         header("location: register-form.php");
                         exit();
                    }
                } else{
                    $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b>";
                    $_SESSION['errors'] = $error_check;
                    header("location: register-form.php");
                    exit();
                }
               }
        
            // Validate password
            $password = trim($_POST['txtPass']);
            if(empty(trim($_POST['txtPass']))){
                $error_check['pass'] = "<b style=\"color: red; font-size: 12px;\">Password canot be empty!</b>";
            } elseif(!preg_match('((?=.*[A-Z])(?=.*[a-z])(?=.*\d).{7,21})', $password)){
                $error_check['pass'] = "<b style=\"color: red; font-size: 12px;\">Password is invalid. Password must include atleast ONE capital letter, a number and a symbol!</b>";
            }
            
             // Validate password confirmation
            $confirm_password = trim($_POST["txtPassRe"]);
            if(empty(trim($_POST["txtPassRe"]))){
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
                $sql = "SELECT * FROM CUSTOMER WHERE EmailAddress = ?";
                if($stmt = mysqli_prepare($connection, $sql)){
                    // Set parameters
                    $param_email = $_POST['txtEmail'];
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_email);
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        /* store result */
                        mysqli_stmt_store_result($stmt);
                        //Checking if we have any hits from the DB, if we do a user exists with that email, else they don't. Erro as according.
                        if(mysqli_stmt_num_rows($stmt)  > 0){
                             $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email aleady registered!</b>";
                             $email = trim($_POST['txtEmail']);
                             $_SESSION['email'] = $email;
                             $_SESSION['errors'] = $error_check;
                             header("location: register-form.php");
                             exit();
                        } else {
                            $email = trim($_POST['txtEmail']);
                            $_SESSION['email'] = $email;
                        }
                    } else {
                         $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b>";
                          $_SESSION['errors'] = $error_check;
                         header("location: register-form.php");
                         exit();
                    }
                }
            }
        
                // Check input errors before inserting in database
                //This makes sure that the user doesn't get through if they have errors outstanding.
                if(empty($error_check)){
                    $userID = ' ';
                    // Prepare an insert statement
                    $sql = "INSERT INTO CUSTOMER (User_ID, User_Forname, User_Surname, EmailAddress, Password) VALUES (?,?,?,?,?)";
            
                    if($stmt = mysqli_prepare($connection, $sql)){
                        // Bind variables to the prepared statement as parameters
                        $param_id = $userID;
                        $param_password = hash('sha512', $password).hash('sha384', $password).hash('whirlpool', $password);
                        $param_email = $email;
      
                        mysqli_stmt_bind_param($stmt, "sssss", $param_id,$_POST['txtUserFirst'], $_POST['txtUserLast'], $param_email, $param_password);
                        $_SESSION['userName']=$param_email;
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            $error_check['registration'] = "<b style=\"color: green; font-size: 12px;\">Registration was a success</b>";
                            $_SESSION['errors'] = $error_check;
                            header("location: register-form.php");
                            exit();
                        } else{
                             $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b>";
                             $_SESSION['errors'] = $error_check;
                             header("location: register-form.php");
                             exit();
                        }
                    }
                    //When all is done close the connection to the database and redirect the user to the appropriate page
                    mysqli_stmt_close($stmt);
                    header("location: register-form.php");
                    exit();
                } else {
                    //If error_check is not empty, this triggers.
                    //Session errors inheritfs the errors from the erro_check array.
                    //user is redirected as appropriate
                    $_SESSION['errors'] = $error_check;
                    header("location: register-form.php");
                    exit();  
               }
        }
    }
    