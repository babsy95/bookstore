

<?php
  require('includes/mysqli_oop_connect.php');
  require("./includes/func.php");
  checkIfUser();
  require("./includes/header.php");
?>


<div class="jumbotron text-center" style="padding: 50px">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong class="text-success">Your order has been placed sucessfully !</strong> </p>
  <hr>
 
  <p class="lead mt-5">
    <a class="btn btn-danger btn-sm" href="user-home.php" role="button">Back to homepage</a>
  </p>
</div>

<?php
 require("./includes/footer.php");
?>