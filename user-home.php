<?php
  require('includes/mysqli_oop_connect.php');
  require("./includes/func.php");
  checkIfUser();
  require("./includes/header.php");
?>



  <!-- Background image -->
  <div class="text-center bg-image" style="background-image: url('images/featured-image.jpg');height: 400px;">

    <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
      <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-white">
          <h2 class="mb-3">Welcome to the world of books!</h2>
          
          <a class="btn btn-outline-light btn-lg" href="books.php" role="button"
          >SHOP NOW</a
          >
        </div>
      </div>
    </div>
  </div>
  <!-- Background image -->
<!-- </header> -->
<?php
 require("./includes/footer.php");
?>

