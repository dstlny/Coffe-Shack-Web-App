<?php
include '../dbcon/init.php';
if (isset($_POST['subLogin'])) {
    $mailuid=$_POST['txtLogEmail'];
    $pass=$_POST['txtLogPass'];
    $_SESSION['errors'] = array();
    $hash = hash('sha512', $pass).hash('sha384', $pass).hash('whirlpool', $pass);
    if(empty($mailuid) && empty($pass)){
        $_SESSION['errors']['loginEmpty'] = "<br><b style=\"color: red; font-size: 12px;\">Login fields cannot be empty!</b>";
        header("location: ../pages/home.php");
        exit();
    } else {
        if(empty($mailuid)){
            $_SESSION['errors']['email'] = "<b style=\"color: red; font-size: 12px;\">Please enter a email address!</b>";
            header("location: ../pages/home.php");
            exit();
        } elseif(empty($pass)){
            $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Please enter a password!</b>";
            header("location: ../pages/home.php");
            exit();
        } else {
         $query  = 'SELECT * FROM CUSTOMER WHERE EmailAddress = ?';
         $stmt = mysqli_stmt_init($connection);
         if(mysqli_stmt_prepare($stmt, $query)){
             mysqli_stmt_bind_param($stmt,'s',$mailuid);
             mysqli_stmt_execute($stmt);
             $result = mysqli_stmt_get_result($stmt);
             if($row = mysqli_fetch_assoc($result)){
                 if($hash == $row['Password']){
                    $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
                    $_SESSION['loggedIn'] = TRUE;
                    $_SESSION['userName'] = $row['EmailAddress'];
                    $_SESSION['userID'] = $row['User_ID'];
                    header("location: ../pages/home.php");
                    exit();
                 } else{
                     $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                     header("location: ../pages/home.php");
                     exit();
                 }
             } else{
                //check staff/admin exists
                $query  = "SELECT * FROM STAFF WHERE EMAIL_ADDRESS = ?";
                $stmt = mysqli_stmt_init($connection);
                if(mysqli_stmt_prepare($stmt, $query)){
                   mysqli_stmt_bind_param($stmt,'s',$mailuid);
                   if(mysqli_stmt_execute($stmt)){
                       $result = mysqli_stmt_get_result($stmt);
                       if($row = mysqli_fetch_assoc($result)){
                          if($hash == $row['Password']){
                             $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
                             $_SESSION['adminLoggedIn'] = TRUE;
                             $_SESSION['adminUserName'] = $row['Email_Address'];
                             $_SESSION['adminID'] = $row['Staff_ID'];
                             header("location: ../pages/home.php");
                             exit();
                          } else{
                             $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                			 header("location: ../pages/home.php");
                             exit();
                         }
                       } else{
                         //Customer nor Staff exists with this email
                         $_SESSION['errors']['noRecog'] = "<b style=\"color: red; font-size: 12px;\">Account not recognised!</b>";
                		 header("location: ../pages/home.php");
                         exit();
                       }
                   } else{
                       $_SESSION['errors']['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error has occured!</b>";
                       header("location: ../pages/home.php");
                       exit();
                   }
                }
             }
         }
        }
      }
} else {
    header("location: ../pages/home.php");
    exit();
}
?>