<?php

    require("./includes/func.php");
    require_once('includes/mysqli_oop_connect.php');
    checkIfUser();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
      
        if (isset($_SESSION['books_added'])) 
        {      
            $count = $_POST['count'];
            $bookId = $_POST['id'];
            $index =  $_POST['index'];

            $_SESSION['books_added'][$index] = [
                'book_id' => $bookId,
                'count' => $count
            ];
            
            
            $total = 0;
            foreach($_SESSION['books_added'] as $bk) {
              $count = $bk['count'];
              $bookId = $bk['book_id'];
              $prodQuery = "SELECT SUM($count * price) as total FROM bookInventory WHERE id= $bookId";          
              $result = $mysqli->query($prodQuery);
              if ($result) {
                $amount = $result->fetch_assoc();   
                $total = $total + $amount['total'];
              }        
            }

            echo $total;
        }
    }

?>