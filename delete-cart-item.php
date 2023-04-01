    
    <?php
        require("./includes/func.php");
        require_once('includes/mysqli_oop_connect.php');
        checkIfUser();

        $books = [];

        if (isset($_SESSION['books_added'])) 
        { 
            
            unset($_SESSION['books_added'][$_POST['data']]);

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
  
            echo json_encode(['count' => count($_SESSION["books_added"]), 'total' => $total]);
        }

    ?>