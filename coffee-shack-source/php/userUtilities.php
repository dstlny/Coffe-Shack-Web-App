<?php
/*
  This class represents the user
*/
class user {
    
    private $_mailuid;
    private $_pass; 
    private $_confirm_pass; 
    private $_logged_in;
    private $_userName;
    private $_firstName;
    private $_secondName;
    private $_userID;
    private $_admin;

    function setUser($user){
        $this->_userName = $user;
    }

    function setUserFirstName($user){
        $this->_firstName = $user;
    }

    function setUserSecondName($user){
        $this->_secondName = $user;
    }

    function getUserFirstName(){
        return $this->_firstName;
    }

    function getUserSecondName(){
        return $this->_secondName;
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

    function setConfirmPass($user){
        $this->_confirm_pass = $user;
    }

    function getConfirmPass(){
        return $this->_confirm_pass;
    }

    function setLoggedIn($user){
        $this->_logged_in = $user;
    }

    /*
      Returns an object which represents the user and their data.
    */
    function getUserObject(){
        return (object)['_mailuid' => $this->_mailuid,'_logged_in' => $this->_logged_in,'_userID' => $this->_userID,'_admin' => $this->_admin];
    }

    function checkIfDataNotEmpty($email, $password){

        if(empty($email) && empty($password)){
            $_SESSION['errors']['loginEmpty'] = "<br><b style=\"color: red; font-size: 12px;\">Login fields cannot be empty!</b>";
            header("location: ../pages/home.php");
            exit();
        } elseif(empty($email)){
            $_SESSION['errors']['email'] = "<b style=\"color: red; font-size: 12px;\">Please enter a email address!</b>";
            header("location: ../pages/home.php");
            exit();
        } elseif(empty($password)){
            $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Please enter a password!</b>";
            header("location: ../pages/home.php");
            exit();
        } else {
            return TRUE;
        }
    }

    function checkUser($_user, $pass){
        include '../dbcon/init.php';

        $query  = "SELECT EmailAddress, User_ID, Password FROM CUSTOMER WHERE EmailAddress=?";
        
        if($stmt = $sqli->prepare($query)){

            $stmt->bind_param("s", $_user);

            if($stmt->execute()){

                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if($row == NULL || $row->num_rows == 0){

                    return FALSE;

                } else {

                    if(password_verify($pass, $row['Password'])){

                        $this->setLoggedIn(TRUE);
                        $this->setUser($row['EmailAddress']);
                        $this->setID($row['User_ID']);
                        $this->setAdmin(false);

                        return TRUE;

                    } else{

                        $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                        header("location: ../pages/home.php");
                        exit();

                    } 

                }
            } else{

                $_SESSION['errors']['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error has occured!</b>";
                header("location: ../pages/home.php");
                exit();

            }
        }
    }

    function checkAdmin($_user, $pass){
        require '../dbcon/init.php';

        /*
            This means the details the user fed us aren't in the CUSTOMER database, 
            thus check the staff database next to see if we get a hit.
        */
        $query = "SELECT Email_Address, Staff_ID, Password FROM STAFF WHERE Email_Address=?";

        if($stmt = $sqli->prepare($query)){

            $stmt->bind_param("s", $_user);

            if($stmt->execute()){

                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if($row == NULL || $row->num_rows == 0){

                    return FALSE;

                } else{

                    if(password_verify($pass, $row['Password'])){

                        $this->setLoggedIn(TRUE);
                        $this->setUser($row['Email_Address']);
                        $this->setID($row['Staff_ID']);
                        $this->setAdmin(TRUE);
                        
                        return TRUE;

                    } else{

                        $_SESSION['errors']['pass'] = "<b style=\"color: red; font-size: 12px;\">Password doesn't match!</b>";
                        header("location: ../pages/home.php");
                        exit();

                    } 

                }
            } else{

                $_SESSION['errors']['sqlError'] = "<b style=\"color: red; font-size: 12px;\">An SQL error has occured!</b>";
                header("location: ../pages/home.php");
                exit();
            }
        }

    }
}
?>