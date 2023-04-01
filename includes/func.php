
<?php
    //require('mysqli_oop_connect.php');

    function redirect_user($page) {
       $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
       $url = rtrim($url, '/\\');
       $url .= '/' . $page;   
       header("Location: $url");
       exit();    
    }

    function checkLogin($mysqli, $username = '', $password = '', $roleId) {
        $nameError = ''; 
        $passwordError = '';
        $user = '';
       
        if (! empty($username)) { 
            $username =  $mysqli->real_escape_string(trim($username));
        } else {  
            $nameError = "Please enter a username !";
        }

        if (! empty($password)) {
            $password =  $mysqli->real_escape_string(trim($password));
        } else {
            $passwordError = "Please enter a password !";
        } 

        if (! empty($username) && !  empty($username)) {
            //$pass=SHA2('$password', 512);
           // $pass=hash( 'sha256', $password );
            //$loginQuery = 'SELECT * FROM users WHERE username = ? AND password = ? AND roleId= ?';
            $loginQuery = 'SELECT * FROM users WHERE username = ? AND roleId= ?';
            $stmt = $mysqli->prepare($loginQuery);
    
            $stmt->bind_param('si', $username, $roleId);  
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if (! empty($user)) {
                if (password_verify($password, $user['password'])) { 
                    return ['user'=> $user, 'nameError'=> $nameError, 'passwordError'=> $passwordError];
                }
            }

            return ['user'=> '', 'nameError'=> $nameError, 'passwordError'=> $passwordError];
            

            // if ($stmt->affected_rows == 1) { 
            //     session_start();
            //     $_SESSION['is_user'] = true;
            //     $_SESSION['userid'] = $user['userid'];
            //     $_SESSION['roleId'] = $roleId;
            //     $_SESSION['username'] = $user['username'];
            //     $_SESSION['name'] = $user['firstName']." ".$user['lastName'] ;
            //     redirect_user("user-home.php");
            // } else {
            //    $loginErr = "Username or password do not match";
            // }
    
            // $stmt->close();
            // unset($stmt);
        }

        //return ['user'=> $user, 'nameError'=> $nameError, 'passwordError'=> $passwordError];
    }

   
   function checkIfUser() {
        session_start();
        if (! isset($_SESSION['roleId'])) {
            redirect_user("login.php");
        } else if(isset($_SESSION['roleId']) && $_SESSION['roleId'] !=2) { 
            echo "Access denied ! </br> Go to home page <a href=''></a>"; 
            exit();
        }

        // if (! isset($_SESSION['is_user'])) {
        //     redirect_user("login.php");
        // } else (isset($_SESSION['is_user']) && $_SESSION['roleId'] !=2) { 
        //     echo "Access denied ! </br> Go to home page <a href=''></a>"; 
        //     exit();
        // }

        
    }

    function checkIfAdmin() {
        session_start();
        if (! isset($_SESSION['is_admin'])) {
            redirect_user("admin-login.php");
        } else if(isset($_SESSION['is_admin']) && $_SESSION['roleId'] !=1) { 
            echo "Access denied ! </br> Go to home page <a href=''></a>"; 
            exit();
        }
    }

    function getCartCount() {
       // session_start();
        $count = "";
        if (isset($_SESSION["books_added"])) { 
            $count = "(".count($_SESSION["books_added"]).")";
        } 

        echo $count;      
    }

    function getProductDetails($mysqli, $id) {
       
        $prodQuery = "SELECT * FROM bookInventory WHERE id = $id";        
        $result = $mysqli->query($prodQuery);

        return $result->fetch_array();
    }

    function checkCount($mysqli, $id) {
        $prodQuery = "SELECT quantity FROM bookInventory WHERE id = $id";        
        $result = $mysqli->query($prodQuery);

        return $result->fetch_array();
    }

  

?>