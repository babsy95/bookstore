<?php
  require("./includes/func.php");
  require_once('includes/mysqli_oop_connect.php');
   
  checkIfUser();
  require("./includes/header.php");

        
  if (isset($_SESSION['books_added'])) 
  { 
      //$books = $_SESSION['books_added'];
      $addedBooks = array_column($_SESSION['books_added'], 'book_id'); 
      $bookIds = implode(',',  $addedBooks); 

      $prodQuery = "SELECT SUM(price) as total FROM bookInventory WHERE id IN ($bookIds)";          
      $result = $mysqli->query($prodQuery);
      if ($result) {
        $total = $result->fetch_assoc(); 
      }
      
       // new total amount.
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
        
  }
  $errors = false;
  if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
      
      $cardNumber = $expiry = $cvv = '';

      if (! empty($_POST['firstname'])) {
          $firstName = $mysqli->real_escape_string(strip_tags($_POST['firstname']));
      } else {
          $errFirstName = "first name is empty!";
          $errors = true;
      }

      if (! empty($_POST['lastname'])) {
          $lastName =  $mysqli->real_escape_string(strip_tags($_POST['lastname']));
      } else {
          $errLastName = "last name is empty!";
          $errors = true;
      }

      $paymentOption = $_POST['payment_option'];


      if ($paymentOption == 2) {
        if (! empty($_POST['cardNumber'])) {          
            if (strlen($_POST['cardNumber']) == 16 ) {
              $cardNumber =  $mysqli->real_escape_string(strip_tags($_POST['cardNumber']));
            } else {
              $errCardNumber = "Invalid card number, should be of length 16!";
              $errors = true;
            }
        } else {
            $errCardNumber = "Card number is empty!";
            $errors = true;
        }

        if (! empty($_POST['expiry'])) {
          $pattern = "/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/";
          if (preg_match($pattern, $_POST['expiry'])) {  
            $expiry =  $mysqli->real_escape_string(strip_tags($_POST['expiry']));           
          } else {
            $errExpiry = "Please enter the correct format!";
          }
         
        } else {
            $errExpiry = "Expiry Date is empty!";
            $errors = true;
        }

        if (! empty($_POST['cvv'])) {
          $pattern = "/^[0-9]{3,4}$/";
          if (preg_match($pattern, $_POST['cvv'])) {  
              $cvv =  $mysqli->real_escape_string(strip_tags($_POST['cvv']));
          } else {
            $errCvv = "CVV should be of 3 or 4!";
            $errors = true;
          }
          
        } else {
            $errCvv = "cvv is empty!";
            $errors = true;
        }
      }
      $queryErr = [] ;      
      // print_r( $errCardNumber); die();
      if (! $errors && isset($_SESSION['books_added']) && count($_SESSION['books_added'])> 0) {
          $books = $_SESSION['books_added'];

          // insert to the bookinventoryorder table.
          $q = 'INSERT INTO bookinventoryorder (userId, firstName, lastName, payment_method, created_on) VALUES (?, ?, ?, ?, NOW())';
          $stmt1 = $mysqli->prepare($q);
          $stmt1->bind_param('issi', $_SESSION['userid'], $firstName, $lastName, $paymentOption);
          $stmt1->execute();
           // if order placed successfully, update bookinventory table.
          if ($stmt1->affected_rows == 1) { 
            $orderId = $stmt1->insert_id;
           
            foreach($books as $book) { 
              $quantity = $book['count'];
              $bookId = $book['book_id'];
              $check = checkCount($mysqli, $bookId);
              if ($check['quantity'] > $quantity) {
               
                // order items.
                $q1 = 'INSERT INTO bookinventoryorderitems (orderId, bookId, quantity) VALUES (?, ?, ?)';
                $stmt2 = $mysqli->prepare($q1);
                $stmt2->bind_param('iii', $orderId, $bookId, $quantity);
                $stmt2->execute();

                // update quantity
                $updateQ = "UPDATE bookInventory SET quantity = quantity - $quantity WHERE id = $bookId";
                $res = $mysqli->query($updateQ);
                if (! $res) {                
                  $queryErr[] = $mysqli->error;
                }
              }              
            }
            // unset the cart session.
            unset($_SESSION['books_added']);     
          } else {
            $queryErr[] =  $stmt1->error;         
          }

        if (empty($queryErr)) {
          redirect_user("success.php");
        }       
      } 
     
  }
?>

<section class="h-100 h-custom" style="background-color: #ffffff; ">
  <div class="container py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12">
        <div class="card card-registration card-registration-2" style="border-radius: 15px; margin-bottom: 30px">
          <div class="card-body p-0">
            <div class="row g-0">
              <div class="col-lg-7">
                <div class="p-5">
                  <div class="d-flex justify-content-between align-items-center mb-5">
                    <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                   
                  </div>
                  <hr class="my-4">

                  <?php 
                    if (isset($_SESSION['books_added']) && count($_SESSION['books_added']) > 0 ) {
                        foreach($_SESSION['books_added'] as $key=>$bk) { 
                            $book = getProductDetails($mysqli, $bk['book_id']);
                       
                  ?>
                  <div class="row mb-4 d-flex justify-content-between align-items-center cart_item_<?php echo $key ?>">
                    <div class="col-md-3 col-lg-3 col-xl-3">
                      <img
                        src="<?php echo "uploads/".$book['image_url'];  ?>"
                        class="img-fluid rounded-3" alt="Cotton T-shirt" style="width:120px; height: 130px;">
                    </div>
                    <div class="col-md-3">
                      <h6 class="text-muted"><?php echo $book['book_title']; ?></h6>
                      <h6 class="text-black mb-0"> <?php ?> </h6>
                    </div>

                    <div class="col-md-2 d-flex">
                     
                      <input id="quantity" min="1" name="quantity" value="<?php echo $bk['count']; ?>" type="number"
                        class="form-control changeQuantity"  data-sessid=<?php echo $key; ?> data-bookid=<?php echo $book['id']; ?> >

                    </div>

                    <div class="col-md-2 ml-2">
                      <h6 class="mb-0"><?php echo "$".$book['price'];  "$"?></h6>
                    </div>
                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                      <a href="#!" class="text-muted delete_product" data-index=<?php echo $key;  ?>><i class="fas fa-times"></i></a>
                    </div>
                  </div>

                  <hr class="my-4">

                  <?php  } } else { ?>

                    <h6> No items in your cart!!!</h6>
                    <?php } ?>

             
                  <div class="pt-5">
                    <h6 class="mb-0"><a href="books.php" class="text-body"><i
                          class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a></h6>
                  </div>
                </div>
              </div>

              <div class="col-lg-5 bg-grey">
                <div class="p-5">
                  <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                  <hr class="my-4">

                  <div class="d-flex justify-content-between mb-4">
                    <h5 class="text-uppercase"> Sub Total</h5>
                    <h5 class="sub_total"><?php  echo isset($total) ? "$".$total : 0; ?></h5>
                  </div>

               <form action="cart-list.php" method="post">   

                  <h6 class="text-uppercase mb-3">First Name</h6>

                  <div class="mb-2">
                    <div class="form-outline">
                      <input type="text" name="firstname" class="form-control form-control-lg" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>"/>
                    </div>
                    <span style="color: red"><?php if (isset($errFirstName)) echo $errFirstName ?> </span>
                  </div>

                  <h6 class="text-uppercase mb-3">Last Name</h6> 
                    <div class="mb-2">
                    <div class="form-outline">
                        <input type="text" name="lastname" class="form-control form-control-lg" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>" />
                    </div>
                    <span style="color: red"><?php if (isset($errLastName)) echo $errLastName ?> </span>
                    </div>

                    <h5 class="text-uppercase mb-3">Payment</h5>
                    <div class="mb-4 pb-2">
                        <select class="select payment_option" name="payment_option">
                            <option value="1" <?php echo (isset($_POST['payment_option']) && $_POST['payment_option'] == 1) ? "selected" : ""; ?>>Net Banking</option>
                            <option value="2" <?php echo (isset($_POST['payment_option']) && $_POST['payment_option'] == 2) ? "selected" : ""; ?>>Credit/Debit</option>
                            <option value="3" <?php echo (isset($_POST['payment_option']) && $_POST['payment_option'] == 3) ? "selected" : ""; ?>>Cash On Delivery</option>
                        </select>
                    </div>

                    <div class="row card-details">
                    <h6 class="text-uppercase mb-3">Card Number</h6>
                    
                    <div class="mb-2">
                      <div class="form-outline">
                          <input type="text" name="cardNumber" class="form-control form-control-lg" value="<?php if (isset($_POST['cardNumber'])) echo $_POST['cardNumber']; ?>"/>
                      </div>
                      <span style="color: red"><?php if (isset($errCardNumber)) echo $errCardNumber ?> </span>
                    </div>

                  
                      <div class="col-md-4">
                      <h6 class="text-uppercase mb-3">Expiry</h6>
                      <div class="mb-2">
                        <div class="form-outline">
                            <input type="text" placeholder="MM/YY" name="expiry" class="form-control form-control-lg" value="<?php if (isset($_POST['expiry'])) echo $_POST['expiry']; ?>"/>
                        </div>
                        <span style="color: red"><?php if (isset($errExpiry)) echo $errExpiry ?> </span>
                      </div>
                      </div>

                      <div class="col-md-4">
                      <h6 class="text-uppercase mb-3">CVV</h6>
                      <div class="mb-2">
                        <div class="form-outline">
                            <input type="text" name="cvv" class="form-control form-control-lg" value="<?php if (isset($_POST['cvv'])) echo $_POST['cvv']; ?>"/>
                        </div>
                        <span style="color: red"><?php if (isset($errCvv)) echo $errCvv ?> </span>
                      </div>
                      </div>
                      </div>


                  <hr class="my-4">

                  <!-- <div class="d-flex justify-content-between mb-5">
                    <h5 class="text-uppercase">Total price</h5>
                    <h5>€ 137.00</h5>
                  </div> -->

                  <button type="submit" class="btn btn-dark btn-block btn-lg"
                    data-mdb-ripple-color="dark">confirm order</button>
            </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<script>

    <?php 
    
      $errors;
    ?>

    $(".changeQuantity").click(function(){
      var count = $(this).val(); 
      var bookid =  $(this).data("bookid"); 
      var index = $(this).data("sessid");
      $.ajax({
            method: "POST",
            url: "change-quantity.php",         
            data: {id:bookid, count: count, index: index},
            success: function (response) {
              console.log(response);
              if (response) {
                $(".sub_total").text("$"+response);
              }
             
            }
        });
    });

    $(".card-details").hide();

    if ($(".payment_option").val() == 2) {
      $(".card-details").show();
    } 

    $(".payment_option").change(function() {
       if ($(this).val() == 2) {
          $(".card-details").show();
       } else {
        $(".card-details").hide();
       }
        
    })

    // delete cart item from session
    $(".delete_product").click(function(){ 
        var index = $(this).data("index");
        $("#div1").remove();
        $(".cart_item_"+index).remove();

        $.ajax({
            method: "POST",
            url: "delete-cart-item.php",         
            data: {data:index},
            success: function (response) {
              // console.log(response);
              if (response) {
                var res = JSON.parse(response);
                console.log(res);
                $(".cart_count").text('CART('+res.count+')');
                $(".sub_total").text("$"+res.total);
              }
             
            }
        });


    });
  
</script>