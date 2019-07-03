<?php $user_object = unserialize($_SESSION['user']);?>
<div class="register-container" style="text-align: center;">
    <div style="display:inline-block;">
        <form method="post" action="../php/register-form.php">
            <p style="padding-right: 10px;font-size: 12px; float: left;">Don't have an account?<br>Don't worry, sign up!</p><button style="width: 80px; font-size:12px; padding:4px; margin-top:15px; height: 30px;" type="submit" name="register" class="register">Register</button>
        </form>
        <form method="post" action="../php/login.php">
            <fieldset>
                <legend>Login</legend>
                <input type="text" placeholder="Email" name="txtLogEmail" class="registration-input"  autocomplete="username" value="<?=(isset($user_object->_mailuid)) ? $user_object->_mailuid : NULL;?>">
                    <?=(isset($_SESSION['errors']['email']) || isset($_SESSION['errors']['loginEmpty'])) ? "<b style=\"color: red; font-size: 13px;\">*</b>" : NULL;?><br>
                    <?php
                        if(isset($_SESSION['errors']['email'])){
                            echo $_SESSION['errors']['email'].'<br>';
                            unset($_SESSION['errors']['email']);
                        }
                    ?>
                <input type="password" placeholder="Password" name="txtLogPass" class="registration-input">
                    <?=(isset($_SESSION['errors']['pass']) || isset($_SESSION['errors']['loginEmpty'])) ? "<b style=\"color: red; font-size: 13px;\">*</b>" : NULL;?><br>
                    <?php
                        if(isset($_SESSION['errors']['pass'])){
                            echo $_SESSION['errors']['pass'].'<br>';
                            unset($_SESSION['errors']['pass']);
                        }
                    ?>
                <button type="submit" name="subLogin" value="submit" class="registration-input">Login</button>
                    <?php
                        if(isset($_SESSION['errors']['loginEmpty'])){
                            echo "<br>{$_SESSION['errors']['loginEmpty']}";
                            unset($_SESSION['errors']['loginEmpty']);
                        } elseif(isset($_SESSION['errors']['sqlError'])){
                            echo "<br>{$_SESSION['errors']['sqlError']}";
                            unset($_SESSION['errors']['sqlError']);
                        } elseif(isset($_SESSION['errors']['noRecog'])){
                            echo "<br>{$_SESSION['errors']['noRecog']}";
                            unset($_SESSION['errors']['noRecog']);
                        } elseif(isset($_SESSION['errors']['success'])){
                            echo $_SESSION['errors']['success'];
                            echo '<p style="font-size:12px;" class="loading">Redirecting to menu<span>.</span><span>.</span><span>.</span></p>';
                            echo "<meta http-equiv='Refresh' content='2; URL=../pages/menu.php'>";
                            unset($_SESSION['errors']['success']);
                        }
                    ?>
            </fieldset>
        </form>
    </div>
</div>  
