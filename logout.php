<?php

    include('includes/func.php');

    session_start();  
    if (isset($_SESSION['is_user'])) {
        $_SESSION = []; 
        //session_destroy(); 
        unset($_SESSION["is_user"]);
        redirect_user('login.php');

    }

?>