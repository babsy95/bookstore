<?php
    require_once('includes/mysqli_oop_connect.php');
    require("./includes/func.php");
    checkIfUser();
    require("./includes/header.php");
  

    $bookQuery = 'SELECT * FROM bookInventory WHERE quantity > 0';
    $stmt = $mysqli->prepare($bookQuery);
    $stmt->execute();
    $result = $stmt->get_result(); 

    //unset($_SESSION["books_added"]);
      //print_r(($_SESSION["books_added"][0]['book_id'])); die();
    $addedBooks = [];
    if (isset($_SESSION['books_added'])) 
    { 
        //$addedBooks = $_SESSION['books_added']; 
        $addedBooks = array_column($_SESSION['books_added'], 'book_id'); 
        //print_r( $addedBooks); die();
    }
?>

<div class="container list-display">
    
        <div class="row">
            <?php if($result) 
            while ($book = $result->fetch_assoc()) {   ?>
            <div  class="col-md-3">
                <div class="card mb-4 p-0">
                    <a href="detail.php?id=<?php echo $book['id'] ?>">
                        <img class="card-img-top" src="<?php echo "uploads/".$book['image_url']?>" alt="Card image" style="width:100%; height:300px">
                    </a>
                    <div class="card-body">           
                        <a class="text-dark" href="detail.php?id=<?php echo $book['id'] ?>" style="text-decoration: none;"> <h4 class="card-title"> <?php echo ucfirst($book['book_title']) ?></h4> </a>
                        <span>
                            <h4 class="text-danger"> <?php echo "$".$book['price'] ?> </h4> 
                            <!-- <h4> <?php echo $book['quantity'] ?> </h4> -->
                        </span>
                        <p class="card-text"> 
                            <?php 
                               
                                if (strlen($book['description']) > 100) {
                                    $des = substr($book['description'], 0, 70) . '...';
                                    echo $des;
                                } else {
                                     echo $book['description'];
                                }

                            ?>
                        </p>
                       

                        <?php
                            if (in_array($book['id'], $addedBooks)) { 
                        ?>

                            <a href="#" class="btn btn-danger" data-bookid=<?php echo $book['id'];  ?>>Go to cart</a>

                        <?php 
                           } else {                        
                        ?>
                            <a href="#" class="btn btn-danger buy-btn" data-bookid=<?php echo $book['id'];  ?>> Add to cart</a>

                        <?php } ?>

                        
                    </div>             
                </div>
            </div>
            <?php } 
            
            $mysqli->close(); ?>
        </div>

    </div>
</div>

</div>



<?php
 require("./includes/footer.php");
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    
    $(".buy-btn").click(function(){ 
        var bookid = $(this).data("bookid");
        
        $(this).bind();
        $(this).html("GO TO CART");
        $(this).removeClass("buy-btn");
        //$(this).attr("href","cart-list.php");
        $.ajax({
            method: "POST",
            url: "add-cart.php",         
            data: {data:bookid},
            success: function (response) {
                console.log("sucess");
                console.log(response);
                $(".cart_count").text('CART('+response+')');
            }
        });


    });
  
</script>
