<?php
    require_once('includes/mysqli_oop_connect.php');
    require("./includes/func.php");
    checkIfUser();
    require("./includes/header.php");


   // print_r($_GET['id']); die();

    if (! empty($_GET['id'])) {
        $query = 'SELECT * FROM bookinventory WHERE id = ?';
        $stmt = $mysqli->prepare($query);

        $stmt->bind_param('i', $_GET['id']);  
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $book = $result->fetch_assoc();

        if (empty($book)) {
            redirect_user('user-home.php');
        } 

        $addedBooks = [];
        if (isset($_SESSION['books_added'])) 
        { 
            $addedBooks = $_SESSION['books_added']; 
        }
    }
  
?>
<div class="container list-display">
    <div class="row">
        <div class="col-md-10">
            <div class="card detail-card bg-light" style="margin-bottom: 40px; padding: 40px">
                <div class="row no-gutters">
                    <!-- <div class="col-auto"> -->
                    <div class="col-md-4">
                        <img class="img-fluid" src="<?php echo "uploads/".$book['image_url']?>"  style="width: 350px; height: 250px" />
                    </div>
                    <div class="col-md-8">
                        <div class="card-block px-4">
                            <h3 class="card-title mt-0 font-weight-bold text-uppercase">
                                <?php echo $book['book_title']?>    
                            </h3>
                            <p class="card-text">By 
                                <b> 
                                <?php echo $book['author']?>   </b>
                            </p>
                            <hr />
                            <h4 class="price-list mt-0"><b><?php echo "$".$book['price']?></b></h4>
                            <p class="h6"> <b>Published On: </b> <?php echo $book['publish_date']?></p>
                                                                                   
                            <?php
                                if (in_array($book['id'], $addedBooks)) { 
                            ?>

                                <a href="#" class="btn btn-danger" data-bookid=<?php echo $book['id'];  ?>>Go to cart</a>

                            <?php 
                            } else {                        
                            ?>
                                <a href="#" class="btn btn-danger buy-btn" data-bookid=<?php echo $book['id'];  ?>> Add to cart</a>

                            <?php } ?>
                            <div>
                                                        
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters mt-5">
                    <h4> About </h4>
                    <p> <?php echo $book['description'] ?></p>
                </div>
                
            </div>
    </div>
</div>
</div>

<?php
    require("./includes/footer.php");
?>

<script>
    
    $(".buy-btn").click(function(){ 
        var bookid = $(this).data("bookid");
        
        $(this).bind();
        $(this).html("GO TO CART");
        $(this).removeClass("buy-btn");
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