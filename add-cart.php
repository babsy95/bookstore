<?php


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
        session_start();
        $_SESSION["books_added"][] = [
                'book_id' => $_POST['data'],
                'count' => 1];


        echo count($_SESSION["books_added"]);
    }
?>

