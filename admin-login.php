<?php
    require_once('includes/mysqli_oop_connect.php');

    require('includes/func.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  

        $data = checkLogin($mysqli, $_POST['username'], $_POST['password'], $_POST['roleId']);

        if (empty($data['nameError']) && empty($data['passwordError'])) {
            if (! empty($data['user'])) {
                session_start();
                $_SESSION['is_admin'] = true;
                $_SESSION['userid'] = $data['user']['userid'];
                $_SESSION['roleId'] = $_POST['roleId'];
                $_SESSION['username'] = $data['user']['username'];
                $_SESSION['name'] = $data['user']['firstName'] ." ".$data['user']['lastName'] ;
                redirect_user("admin-home.php");
            } else {
                $loginErr = "Username or password do not match";
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
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">

                    <div class="mb-md-3 mt-md-4">

                    <h2 class="fw-bold mb-2 text-uppercase">Admin Login</h2>
                    
                    <form action="admin-login.php" method="POST">
                        <input type="text" value="1" name="roleId" hidden>
                        <div class="form-outline form-white mb-4">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg" />
                            <span style="color: red">
                                <?php if (isset($data['nameError'])) echo $data['nameError'] ?>
                            </span>
                        </div>

                        <div class="form-outline form-white mb-4">
                            <label class="form-label" >Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" />
                            <span style="color: red">
                                <?php if (isset($data['passwordError'])) echo $data['passwordError'] ?>
                            </span>
                        </div>

                        <span style="color: red">
                            <?php if (isset($loginErr)) echo $loginErr ?>
                        </span>

                        <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                    </form>                    
                    </div>

                    <div>
                    
                    </div>

                </div>
                </div>
            </div>
            </div>
        </div>
    </section>
		
	</body>
</html>

<?php
    require("./includes/footer.php");
?>