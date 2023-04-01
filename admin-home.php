<?php
  require('includes/mysqli_oop_connect.php');
  require("./includes/func.php");
  checkIfAdmin();
  require("./includes/admin-header.php");

  $bookQuery = 'SELECT * FROM bookInventory WHERE quantity > 0';
  $stmt = $mysqli->prepare($bookQuery);
  $stmt->execute();
  $result = $stmt->get_result(); 
  
?>

<div class="container list-display">
  <h3 style="display: inline;"> BOOKS</h3>
  <a href="add-book.php" class="btn btn-danger mb-5" style="float: right;"> <i class="fa fa-plus" aria-hidden="true"></i>ADD BOOKS</a>
  <?php if (! empty($result)) { ?>
  <table class="table table-bordered" style="margin-bottom: 50px;">
    <thead>
      <tr>
        <th scope="col">Image</th>
        <th scope="col">Title</th>
        <th scope="col">Author</th>
        <th scope="col">Price</th>
        <th scope="col">Quantity</th>
        <th scope="col">Publish Date</th>
      </tr>
    </thead>
    <tbody>
      <?php 
          while ($book = $result->fetch_assoc()) {   ?>
      <tr>
        <td>
          <img src="<?php echo "uploads/".$book['image_url']?>" style="width:80px; height:70px"> </img>
          
        </td>
        <td><?php echo $book['book_title']; ?> </td>
        <td><?php echo $book['author']; ?> </td>
        <td><?php echo "$".$book['price']; ?> </td>
        <td><?php echo $book['quantity']; ?> </td>
        <td><?php echo $book['publish_date']; ?> </td>
      </tr>
      <?php 
        } } else {
          echo "No records found!";
        }  
      ?>
     
    </tbody>
  </table>
</div>

<?php
  require("./includes/footer.php");
?>