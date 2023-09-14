<?php 
 include './audio_mac.db.inc.php';
// print_r($users);
 $name =$email = '';
 //echo $email;
 if(isset($_POST['submit-login'])){
    if(empty($_POST['email']) || empty($_POST['pwd'])){
       header('Location:./index.php?error=MissingInputFields');
       exit();
    }
    else{
      $email = $_POST['email'];
      $password = $_POST['pwd'];
      
      $sql = "SELECT email,password FROM users WHERE email = '$email'";
      $res = mysqli_query($conn,$sql);
      $user = mysqli_fetch_assoc($res);
      print_r($user);
      if(!$res){
        header('Location:./index.php?error=UserDoesNotExist');
        exit();
      }
      else{
        if(!password_verify($password,$user['password'])){
            header('Location:./index.php?error=WrongPassword');
            exit();
        }
        else{
            session_start();
            $_SESSION['session_id'] = $user['email'];
            header('Location:./index.php');
           // echo 'Logged in';
            exit();
        }
      }
    }
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="./styles.css"/>
   <style>
      form .input{
        height:45px;
        width:95%;
        border-radius: 20px;
        padding: 0 10px;
        border: 2px solid #000;
        margin: 2% 0;
        outline: none;
      }
   </style>
</head>
<body>
<section class="registration">
         <div class="register_form">
            <form action="./login.php" method="POST">
                <h3>Login</h3>
                <input class="input" type="email" name="email" placeholder="Email Address"/>
                <input class="input" type="password" name="pwd" placeholder="Password"/>
                <button type="submit" name="submit-login">Sign In</button>
                <p>Don't Have Account?<a href="./register.php">Register</a></p>
                <small>AudioMac.inc</small>
            </form>
         </div>
     </section>
</body>
</html>