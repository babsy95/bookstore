<?php
  require('includes/mysqli_oop_connect.php');
  require("./includes/func.php");

  $errors = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
   
    if (! empty($_POST['firstName'])) {
        $firstName = $mysqli->real_escape_string(strip_tags($_POST['firstName']));
    } else {
        $firstNameError = 'Please fill the firstName !';
        $errors = true;
    }
    
    if (! empty($_POST['lastName'])) {
        $lastName = $mysqli->real_escape_string(strip_tags($_POST['lastName']));
    } else {
        $lastNameError = 'Please fill the lastName !';
        $errors = true;
    }

    if (! empty($_POST['username'])) {
        $username = $mysqli->real_escape_string(strip_tags($_POST['username']));
    } else {
        $usernameError = 'Please fill the username !';
        $errors = true;
    }

    if (!empty($_POST['password'])) {
        $pattern = '/^(?=.*[A-Za-z0-9])(?=.*[@$*]).{6,}$/';

        if (preg_match($pattern, $_POST['password'])) { 
            if ($_POST['password'] != $_POST['re_password']) {
                $passwordError = 'Your password did not match the confirmed password.';
                $errors = true;
            } else {
                $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
            }
        } else {
            $passwordError = 'The password should contain a special character @ or * or $ and should be longer than 6 characters';
            $errors = true;
        }
		
	} else {
		$passwordError = 'You forgot to enter your password.';
	} 

    if (empty($_POST['re_password'])) {
        $rpasswordError = 'You forgot to enter your password.';
        $errors = true;
    }  

    $roleId = 2;

 
    if (! $errors) {		
       
        $q = "INSERT INTO users (roleId, username, firstName, lastName, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($q);
        $stmt->bind_param('issss', $roleId, $username, $firstName, $lastName, $password);
        $stmt->execute();
        if ($stmt->affected_rows == 1) { 
            redirect_user("login.php");
        }                 
    }

 }
?>



<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>

    </title>
    <link  rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
	<body>

        <section class="vh-100" style="background-color: #eee;">
        
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black mt-3 mb-5" style="border-radius: 25px;">
                <div class="card-body">
                    <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                        <p class="text-center h1 fw-bold mb-3 mx-1 mt-2">Register as user</p>

                        <form action="register.php" method="POST" class="mx-1 mx-md-4">

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                                <label class="form-label" for="form3Example1c">First Name</label>
                                <input type="text" name="firstName" class="form-control" value="<?php if (isset($_POST['firstName'])) echo $_POST['firstName'] ?>"/> 
                                <span style="color: red">
                                    <?php if (isset($firstNameError))  echo $firstNameError; ?>
                                </span>                           
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                                <label class="form-label" for="form3Example3c">Last Name</label>
                                <input type="text" name="lastName" class="form-control" value="<?php if (isset($_POST['lastName'])) echo $_POST['lastName'] ?>"/> 
                                <span style="color: red">
                                    <?php if (isset($lastNameError))  echo $lastNameError; ?>
                                </span>              
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                                <label class="form-label" for="form3Example4c">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>"/>
                                <span style="color: red">
                                    <?php if (isset($usernameError))  echo $usernameError; ?>
                                </span>  
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                                <label class="form-label" for="form3Example4c">Password</label>
                                <input type="password" name="password" class="form-control" />
                                <span style="color: red">
                                    <?php if (isset($passwordError))  echo $passwordError; ?>
                                </span>  
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0">
                                <label class="form-label" for="form3Example4c">Re-enter your password</label>
                                <input type="password" name="re_password" class="form-control" />
                                <span style="color: red">
                                    <?php if (isset($rPasswordError))  echo $rPasswordError; ?>
                                </span>  
                            </div>
                        </div>

                        
                        <div class="d-flex mx-4 mb-3 mb-lg-4">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>

                        </form>

                    </div>
                    <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                        <img src="images/register.png"
                        class="img-fluid" alt="Sample image">

                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </section>
    </body>
</html>

