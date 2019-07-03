<?php
    require_once '../pages/header.php';
?>
    <div class="register-container" style="text-align: center;">
        <div style="display:inline-block;">
            <form method="post" action="../pages/home.php">
                <p style="padding-right: 10px;font-size: 12px; float: left;">Already have an account?<br> If so, login</p><button style="width: 80px; font-size:12px; padding:4px; margin-top:15px; height: 30px;" type="submit" name="login" class="register">Login</button>
            </form>
            <form method="post" action="../php/register.php" autocomplete="off">
                <fieldset>
                    <legend>Account Registration</legend>
                    <input type="text" placeholder="Email" name="txtEmail" class="registration-input"><?php
                        echo (isset($_SESSION['errors']['email']) || isset($_SESSION['errors']['sqlError'])) ? "<b style=\"color: red; font-size: 15px;\">*</b>" : NULL;
                    ?>
                    <?php
                        /*
                        * These errors are defined within the registration script. Session variables so they can carry across and persist.
                        * Simply checking if they are set or not, if they aren't nothing is ever outputted.
                        */
                        if(isset($_SESSION['errors']['email'])){
                            echo "<br>{$_SESSION['errors']['email']}";
                            unset($_SESSION['errors']['email']);
                        } elseif(isset($_SESSION['errors']['sqlError'])){
                            echo "<br>{$_SESSION['errors']['sqlError']}";
                            unset($_SESSION['errors']['sqlError']);
                        }
                      ?><br>
                    <input type="text" placeholder="First name" name="txtUserFirst" class="registration-input"><?php
                        echo (isset($_SESSION['errors']['firname']) || isset($_SESSION['errors']['sqlError'])) ? "<b style=\"color: red; font-size: 15px;\">*</b>" : NULL;
                    ?>
                    <?php 
                        /*
                        * These errors are defined within the registration script. Session variables so they can carry across and persist.
                        * Simply checking if they are set or not, if they aren't nothing is ever outputted.
                        */
                        if(isset($_SESSION['errors']['firname'])){
                            echo "<br>{$_SESSION['errors']['firname']}";
                            unset($_SESSION['errors']['firname']);
                        } elseif(isset($_SESSION['errors']['sqlError'])){
                            echo "<br>{$_SESSION['errors']['sqlError']}";
                            unset($_SESSION['errors']['sqlError']);
                        }
                    ?><br>
                    <input type="text" placeholder="Second name" name="txtUserLast" class="registration-input">
                    <?php
                        echo (isset($_SESSION['errors']['secname']) || isset($_SESSION['errors']['sqlError']) || isset($_SESSION['errors']['firsecname'])) ? "<b style=\"color: red; font-size: 15px;\">*</b>" : NULL;
                    ?>
                    <?php
                        /*
                        * These errors are defined within the registration script. Session variables so they can carry across and persist.
                        * Simply checking if they are set or not, if they aren't nothing is ever outputted.
                        */
                        if(isset($_SESSION['errors']['secname'])){
                            echo "<br>{$_SESSION['errors']['secname']}";
                            unset($_SESSION['errors']['secname']);
                        } elseif(isset($_SESSION['errors']['sqlError'])){
                            echo "<br>{$_SESSION['errors']['sqlError']}";
                             unset($_SESSION['errors']['firname']);
                        }
                    ?><br>
                    <input type="password" placeholder="Password" name="txtPass" class="registration-input"><?php
                        echo (isset($_SESSION['errors']['pass']) || isset($_SESSION['errors']['sqlError'])) ? "<b style=\"color: red; font-size: 15px;\">*</b>" : NULL;
                    ?>
                   <?php
                        /*
                        * These errors are defined within the registration script. Session variables so they can carry across and persist.
                        * Simply checking if they are set or not, if they aren't nothing is ever outputted.
                        */
                        if(isset($_SESSION['errors']['pass'])){
                            echo "<br>{$_SESSION['errors']['pass']}";
                            unset($_SESSION['errors']['pass']);
                        }
                    ?><br>
                    <input type="password" placeholder="Confirm Password" name="txtPassRe" class="registration-input"><?php
                        echo (isset($_SESSION['errors']['passRe'])) ? "<b style=\"color: red; font-size: 15px;\">*</b>" : NULL;
                    ?>
                    <?php
                        /*
                        * These errors are defined within the registration script. Session variables so they can carry across and persist.
                        * Simply checking if they are set or not, if they aren't nothing is ever outputted.
                        */
                        if(isset($_SESSION['errors']['passRe'])){
                            echo "<br>{$_SESSION['errors']['passRe']}";
                            unset($_SESSION['errors']['passRe']);
                        }
                    ?><br>
                    <button type="submit" name="register" value="submit" class="registration-input">Register Account</button>
                    <?php
                        /*
                        * These errors are defined within the registration script. Session variables so they can carry across and persist.
                        * Simply checking if they are set or not, if they aren't nothing is ever outputted.
                        */
                        if(isset($_SESSION['errors']['registration'])){
                           echo "<br>{$_SESSION['errors']['registration']}";
                           echo '<p style="font-size:12px;" class="loading">Redirecting to login<span>.</span><span>.</span><span>.</span></p>';
                           echo "<meta http-equiv='Refresh' content='2; URL=../pages/home.php'>";
                           unset($_SESSION['errors']['registration']);
                        } elseif(isset($_SESSION['errors']['Allempty'])){
                           echo $_SESSION['errors']['Allempty'];
                           unset($_SESSION['errors']['Allempty']);
                        } 
                    ?>
                </fieldset>
            </form>
        </div>
    </div>
<?php
    require_once '../pages/footer.php';
?>