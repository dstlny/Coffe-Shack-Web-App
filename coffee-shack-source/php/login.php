<?php
/*
Include our connection to the database.
*/
include '../dbcon/init.php';

/*
This class represents the user
*/
class user {
    private $_mailuid;
    private $_pass; 
    private $_logged_in;
    private $_userName;
    private $_userID;
    private $_admin;

    function setUser($user){
        $this->_userName = $user;
    }

    function getUser(){
        return $this->_userName;
    }

    function setEmail($user){
        $this->_mailuid = $user;
    }

    function getEmail(){
        return $this->_mailuid;
    }

    function setID($user){
        $this->_userID = $user;
    }

    function getID(){
        return $this->$_userID;
    }

    function setAdmin($user){
        $this->_admin = $user;
    }

    function getAdmin(){
        return $this->$_admin;
    }

    function setPass($user){
        $this->_pass = $user;
    }

    function getPass(){
        return $this->_pass;
    }

    function setLoggedIn($user){
        $this->_logged_in = $user;
    }

    function getLoggedIn(){
        return $this->_logged_in;
    }

    /*
      Returns an object which represents the user and their data.
    */
    function getUserObject(){
        return (object)['_mailuid' => $this->_mailuid,'_logged_in' => $this->_logged_in,'_userID' => $this->_userID,'_admin' => $this->_admin];
    }
}

if (isset($_POST['subLogin'])) {
    $user = new user();
    $user->setEmail($_POST['txtLogEmail']);
    $user->setPass($_POST['txtLogPass']);

    $_SESSION['errors'] = array();
    $hash = hash('sha512',$user->getPass()).hash('sha384',$user->getPass()).hash('whirlpool',$user->getPass());

    //Check if email/pass are empty.
    if(empty($user->getEmail()) && empty($user->getPass())){
        $_SESSION['errors']['loginEmpty'] = "<br><b style=\"color: red; font-size: 12px;\">Login fields cannot be empty!</b>";
        header("location: ../pages/home.php");
        exit();
    } else {
        //Check if email is empty.
        if(empty($user->getEmail())){
            $_SESSION['errors']['email'] = "<b style=\"color: red; font-size: 12px;\">Please enter a email address!</b>";
            header("location: ../pages/home.php");
            exit();
         //Check if password is empty.
        } elseif(empty($user->getPass())){
            $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Please enter a password!</b>";
            header("location: ../pages/home.php");
            exit();
        } else {
         $query  = 'SELECT EmailAddress, User_ID, Password FROM CUSTOMER WHERE EmailAddress = ?';
         $stmt = mysqli_stmt_init($connection);
            
         /*
           Since email nor password are empty, we can assume all details are present, 
           thus query the database and see if we got a hit.
         */
         if(mysqli_stmt_prepare($stmt, $query)){
             mysqli_stmt_bind_param($stmt,'s',$user->getEmail());
             mysqli_stmt_execute($stmt);
             $result = mysqli_stmt_get_result($stmt);
             if($row = mysqli_fetch_assoc($result)){
                 if($hash == $row['Password']){
                    $user->setLoggedIn(TRUE);
                    $user->setUser($row['EmailAddress']);
                    $user->setID($row['User_ID']);
                    $user->setAdmin(FALSE);
                    
                    $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
                    $_SESSION['user'] = serialize($user->getUserObject());
                    header("location: ../pages/home.php");
                    exit();
                 } else{
                     $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                     header("location: ../pages/home.php");
                     exit();
                 }
             } else{
                /*
                   This means the details the user fed us aren't in the CUSTOMER database, 
                   thus check the staff database next to see if we get a hit.
                */
                $query  = "SELECT Email_Address, Staff_ID, Password FROM STAFF WHERE Email_Address = ?";
                $stmt = mysqli_stmt_init($connection);
                if(mysqli_stmt_prepare($stmt, $query)){
                   mysqli_stmt_bind_param($stmt,'s',$user->getEmail());
                   if(mysqli_stmt_execute($stmt)){
                       $result = mysqli_stmt_get_result($stmt);
                       if($row = mysqli_fetch_assoc($result)){
                          if($hash == $row['Password']){
                            $user->setLoggedIn(TRUE);
                            $user->setUser($row['Email_Address']);
                            $user->setID($row['Staff_ID']);
                            $user->setAdmin(TRUE);

                            $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
                            $_SESSION['user'] = serialize($user->getUserObject());

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
