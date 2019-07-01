<?php
include '../dbcon/init.php';
include '../php/userUtilities.php';

//If the register button has been clicked, proceed
if(isset($_POST['register'])){
    $user_details = new user();
    $user_details->setUserFirstName($_POST['txtUserFirst']);
    $user_details->setUserSecondName($_POST['txtUserLast']);
    $user_details->setPass($_POST['txtPass']);
    $user_details->setConfirmPass($_POST['txtPassRe']);
    $user_details->setEmail($_POST['txtEmail']);
    $user_details->setID('');
    $new_user = new register_new_user();

    // Check input errors before inserting in database
    //This makes sure that the user doesn't get through if they have errors outstanding.
    if($new_user->checkAllFormData($user_details)){

        // Prepare an insert statement
        $sql = "INSERT INTO CUSTOMER (User_ID, User_Forname, User_Surname, EmailAddress, Password) VALUES (?,?,?,?,?)";

        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $user_details->getID(),$user_details->getUserFirstName(),$user_details->getUserSecondName(), $user_details->getEmail(), password_hash($user_details->getPass(), 1));
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $error_check['registration'] = "<b style=\"color: green; font-size: 12px;\">Registration was a success</b>";
                $_SESSION['errors'] = $error_check;
                mysqli_stmt_close($stmt);
                header("location: register-form.php");
                exit();
            } else{
                $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b>";
                $_SESSION['errors'] = $error_check;
                mysqli_stmt_close($stmt);
                header("location: register-form.php");
                exit();
            }
        }

        //When all is done close the connection to the database and redirect the user to the appropriate page
        mysqli_stmt_close($stmt);
        header("location: register-form.php");
        exit();

    } else {
        header("location: register-form.php");
        exit();  
    }
}    

class register_new_user {

    function checkAllFormData(user $user_details_object){

        //Error_check is an array that wiull store all the errors that the user can potentially have when registering
        $error_check = array();
        //Unsetting the $-SESSION[['errors'] array.
        unset($_SESSION['errors']);
        
        //If any of these are empty we know the form hasn't been filled in, simply redirect and show an error if that is the case.
        if(empty(trim($user_details_object->getUserFirstName())) && empty(trim($user_details_object->getUserSecondName())) && empty(trim($user_details_object->getPass())) 
        && empty(trim($user_details_object->getConfirmPass())) && empty(trim($user_details_object->getEmail()))){
            $error_check['Allempty'] = "<br><b style=\"color: red; font-size: 12px;\">All fields are empty!</b>";
            $_SESSION['errors'] = $error_check;
            header("location: register-form.php");
            exit();
        } else { 

            // Validate users first name
            if(empty(trim($user_details_object->getUserFirstName()))){
                  $error_check['firname'] = "<b style=\"color: red; font-size: 12px;\">Please enter your first name!</b>";
            } elseif(!ctype_alpha($user_details_object->getUserFirstName())){
                 $error_check['firname'] = "<b style=\"color: red; font-size: 12px;\">First name must be alphabetic characters only</b>";
            } 
            
            // Validate users second name
            if(empty(trim($user_details_object->getUserSecondName()))){
                  $error_check['secname'] = "<b style=\"color: red; font-size: 12px;\">Please enter your second name!</b>";
            } elseif(!ctype_alpha($user_details_object->getUserSecondName())){
                  $error_check['secname'] = "<b style=\"color: red; font-size: 12px;\">Second name must be alphabetic characters only</b>";
            } else {

                // Validate password
                $password = trim($user_details_object->getPass());
                if(empty(trim($password))){
                    $error_check['pass'] = "<b style=\"color: red; font-size: 12px;\">Password canot be empty!</b>";
                } elseif(!preg_match('((?=.*[A-Z])(?=.*[a-z])(?=.*\d).{7,21})', $password)){
                    $error_check['pass'] = "<b style=\"color: red; font-size: 12px;\">Password is invalid. Password must include atleast ONE capital letter, a number and a symbol!</b>";
                }
                
                // Validate password confirmation
                $confirm_password = trim($user_details_object->getConfirmPass());
                if(empty(trim($confirm_password))){
                    $error_check['passRe'] = "<b style=\"color: red; font-size: 12px;\">Please confirm your password!</b>";
                } elseif($password != $confirm_password){
                    $error_check['passRe'] = "<b style=\"color: red; font-size: 12px;\">Passwords do not match!</b>";
                }
                
                // Validate email
                if(empty($user_details_object->getEmail())){
                    $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email is empty!</b>";
                } elseif(!filter_var($user_details_object->getEmail(), FILTER_VALIDATE_EMAIL)){
                    $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email is of incorrect format!</b>";
                } else {
                        // Prepare a select statement
                    $sql = "SELECT * FROM CUSTOMER WHERE EmailAddress = ?";
                    if($stmt = mysqli_prepare($connection, $sql)){
                        // Set parameters
                        $param_email = $user_details_object->getEmail();
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "s", $param_email);
                        
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            /* store result */
                            mysqli_stmt_store_result($stmt);
                            //Checking if we have any hits from the DB, if we do a user exists with that email, else they don't. Error as according.
                            if(mysqli_stmt_num_rows($stmt)  > 0){
                                $error_check['email'] = "<b style=\"color: red; font-size: 12px;\">Email aleady registered!</b>";
                                $_SESSION['errors'] = $error_check;
                                header("location: register-form.php");
                                exit();
                            } else {
                                if(empty($error_check)){
                                    return TRUE;
                                } else {
                                    $_SESSION['errors'] = $error_check;
                                    return FALSE;
                                }
                            }
                        } else {
                            $error_check['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error occured!</b>";
                            $_SESSION['errors'] = $error_check;
                            header("location: register-form.php");
                            exit();
                        }
                    }
                }
            }
        }
    }

    function getErrors(){
        return $this->$error_check;
    }

}
?>