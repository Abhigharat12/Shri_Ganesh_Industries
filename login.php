<link rel="stylesheet" href="assets/css/popup_style.css"> 


</style>
   <?php
   
  include('./constant/layout/head.php');
  include('./constant/connect.php');
session_start();

if(isset($_SESSION['userId'])) {
 //header('location:'.$store_url.'login.php');   
}

$errors = array();

if(isset($_POST["login"])) {    

  $username = $_POST['username'];
  $password = $_POST['password'];
 // $pass = md5($password);
  //echo $password;exit;
  
  if(empty($username) || empty($password)) {
    if($username == "") {
      $errors[] = "Username is required";
    } 

    if($password == "") {
      $errors[] = "Password is required";
    }
  } else {
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $connect->query($sql);

    if($result->num_rows == 1) {
      $value = $result->fetch_assoc();
      $storedPassword = $value['password'];

      // Check if password is hashed (starts with $) or MD5
      $passwordValid = false;
      if (substr($storedPassword, 0, 1) === '$') {
        $passwordValid = password_verify($password, $storedPassword);
      } else {
        $passwordValid = md5($password) === $storedPassword;
      }

      if($passwordValid) {
        $user_id = $value['user_id'];

        // set session
        $_SESSION['userId'] = $user_id;?>
         <div class="popup popup--icon -success js_success-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Success 
    </h1>
    <p>Login Successfully</p>
    <p>
     
     <?php echo "<script>setTimeout(\"location.href = 'dashboard.php';\",1500);</script>"; ?>
    </p>
  </div>
</div>
     <?php  }  
      else{
        ?>


        <div class="popup popup--icon -error js_error-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Error 
    </h1>
    <p>Incorrect username/password combination</p>
    <p>
      <a href="login.php"><button class="button button--error" data-for="js_error-popup">Close</button></a>
    </p>
  </div>
</div>
       
      <?php } // /else
    } else { ?> 
        <div class="popup popup--icon -error js_error-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Error 
    </h1>
    <p>Username does not exists</p>
    <p>
      <a href="login.php"><button class="button button--error" data-for="js_error-popup">Close</button></a>
    </p>
  </div>
</div>  
         
    <?php } // /else
  } // /else not empty username // password
  
} // /if $_POST

?>
    
    <div id="main-wrapper">
        <div class="unix-login">
            <div class="accountbg"></div>
            <div class="container-fluid" style="
     background-color: #eff3f6;
    ">
                <div class="row ">
                    <div class="col-md-4 mx-auto">
                        <div class="login-content ">
                            <div class="login-form">
                                <center><img src="./assets/uploadImage/Logo/favicon.png" style="max-width:250px;height:auto;margin:0 auto 20px;display:block;"></center>
                                <?php if(!empty($errors)) { ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php foreach($errors as $error) { ?>
                                                <li><?php echo $error; ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm" class="row">
                                    <div class="form-group col-md-12">
                                        <h4 class="text-center mb-4" style="color: black;">Enter Your Credentials</h4>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required="">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required="">
                                    </div>
                                    <div class="form-group col-md-12 d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <a href="#" class="text-decoration-none">Forgot Password?</a>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" name="login" class="btn btn-primary btn-block mt-3">Sign In</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><center>
 
            
            </footer> </center>
    </div>
    
    
    
    
    <script src="./assets/js/lib/jquery/jquery.min.js"></script>
    
    <script src="./assets/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="./assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
    
    <script src="./assets/js/jquery.slimscroll.js"></script>
    
    <script src="./assets/js/sidebarmenu.js"></script>
    
    <script src="./assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    
    <script src="./assets/js/custom.min.js"></script>
    <<script>
       function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);
    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('page', true);
    show('loading', false);
});  
    </script>
</body>

</html>
