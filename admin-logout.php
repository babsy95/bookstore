<?php

    include('includes/func.php');

    session_start();  
    if (isset($_SESSION['is_admin'])) {
        $_SESSION = []; 
        unset($_SESSION["is_admin"]);
        redirect_user('admin-login.php');

    }

?>