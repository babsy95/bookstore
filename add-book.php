<?php
  require('includes/mysqli_oop_connect.php');
  require("./includes/func.php");
  checkIfAdmin();
  require("./includes/admin-header.php");

  $errors = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_FILES['upload']) && ! empty($_FILES['upload']['name'])) {
        $image =  $mysqli->real_escape_string(trim($_FILES['upload']['name']));
        
    } else {
        $uploadError = 'Please upload a file !';
        $errors = true;
    }

    if (! empty($_POST['book_title'])) {
        $book_title = $mysqli->real_escape_string(strip_tags($_POST['book_title']));
    } else {
        $bookTitleError = 'Please fill the title !';
        $errors = true;
    }
    
    if (! empty($_POST['author'])) {
        $author = $mysqli->real_escape_string(strip_tags($_POST['author']));
    } else {
        $authorError = 'Please fill the author !';
        $errors = true;
    }

    if (! empty($_POST['price'])) {
        $price = $mysqli->real_escape_string(strip_tags($_POST['price']));
    } else {
        $priceError = 'Please fill the price !';
        $errors = true;
    }

    if (! empty($_POST['quantity'])) {
        $quantity = $mysqli->real_escape_string(strip_tags($_POST['quantity']));
    } else {
        $quantityError = 'Please fill the quantity !';
        $errors = true;
    }


    if (! empty($_POST['date'])) {
        $date = new DateTime($_POST['date']); 
        $date = $date->format('Y/m/d H:i:s');
        $date = $mysqli->real_escape_string(strip_tags($date));
    } else {
        $dateError = 'Please select a date !';
        $errors = true;
    }

    if (! empty($_POST['description']) && strlen($_POST['description']) > 0) {  
        $description = $mysqli->real_escape_string(strip_tags($_POST['description']));
    } else {
        $descriptionError = 'Please fill the description !';
        $errors = true;
    }

   

    if (! $errors) {		
        $allowed = ['image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png'];
        if (in_array($_FILES['upload']['type'], $allowed)) { 	
           // $dir = mkdir("uploads");

            if (move_uploaded_file ($_FILES['upload']['tmp_name'], "./uploads/{$_FILES['upload']['name']}")) {
                
                $q = "INSERT INTO bookinventory (book_title, author, price, description, quantity, image_url, publish_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $mysqli->prepare($q);
                $stmt->bind_param('ssdsiss', $book_title, $author, $price, $description, $quantity, $image, $date);
                $stmt->execute();
                
                if ($stmt->affected_rows == 1) { 
                    redirect_user("admin-home.php");
                }    

            } 
        }
    }

 }
?>


    <div class="container mt-3">         
        <div class="row">
            <div class="col-md-10">
            <form enctype="multipart/form-data" action="add-book.php" method="POST"> 
                <h4> ADD BOOK</h4>    
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label>BOOK TITLE:</label>
                            <input type="text" class="form-control" placeholder="Enter title" name="book_title" value="<?php if (isset($_POST['book_title'])) echo $_POST['book_title'] ?>">
                            <span style="color: red">
                                <?php if (isset($bookTitleError))  echo $bookTitleError; ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label>AUTHOR:</label>
                            <input type="text" class="form-control" placeholder="Author" name="author" value="<?php if (isset($_POST['author'])) echo $_POST['author'] ?>">
                            <span style="color: red">
                                <?php if (isset($authorError))  echo $authorError; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label>PRICE:</label>
                            <input type="text" class="form-control" placeholder="price" name="price"  value="<?php if (isset($_POST['price'])) echo $_POST['price'] ?>">
                            <span style="color: red">
                                <?php if (isset($priceError))  echo $priceError; ?>
                            </span>
                        </div>
                    </div>
                            
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label>QUANTITY:</label>
                            <input type="number" class="form-control" placeholder="Enter Quantity" name="quantity" value="<?php if (isset($_POST['quantity'])) echo $_POST['quantity'] ?>">
                            <span style="color: red">
                                <?php if (isset($quantityError))  echo $quantityError; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label>PUBLISH DATE:</label>
                            <input type="text" id="datepicker" name="date" class="form-control">
                            <span style="color: red">
                                <?php if (isset($dateError))  echo $dateError; ?>
                            </span>
                        </div>
                       
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label>UPLOAD IMAGE:</label>
                            <input type="file" class="form-control" name="upload">
                            <span style="color: red">
                                <?php if (isset($uploadError))  echo $uploadError; ?>
                            </span>
                            
                        </div>
                    </div>                   
                   
                </div>

                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="form-group mb-4">
                            <label>DESCRIPTION:</label>
                            <textarea type="text" class="form-control" placeholder="Enter a description" name="description" rows="10" cols="50"><?php if (isset($_POST['description'])) echo $_POST['description'] ?></textarea>
                            <span style="color: red">
                                <?php if (isset($descriptionError))  echo $descriptionError; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-danger">ADD</button>      
                </div>
                

            </form>
            </div>

        </div>  
            
           
            </div>
        </div>
    </div>

  

<?php
    require("./includes/footer.php");
?>

<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  });
  </script>