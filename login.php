<?php 
 include './audio_mac.db.inc.php';
 $users_sql = "SELECT id,name,email,password FROM users";
 
 $res = mysqli_query($conn,$users_sql);
 $users = mysqli_fetch_all($res,MYSQLI_ASSOC);
 //print_r($users);
 $name =$email = '';
 if(isset($_POST['submit-login'])){
    if(empty($_POST['email']) || empty($_POST['pwd'])){
       header('Location:./index.php?error=MissingInputFields');
       exit();
    }
    else{
      $email = $_POST['email'];
      $password = $_POST['pwd'];
      
       foreach($users as $user){
         if($email !== $user['email']){
             header('Location:./index.php?error=UserDoesNotExist');
             exit();
         }
        else{
            if(!password_verify($password,$user['password'])){
                header('Location:./index.php?error=wrongPassword');
                exit();
            }
            else{
                header('Location:./index.php?login==true');
                session_start();
                $_SESSION['session_id'] = $user['email'];
                exit();
            }
        }
      }
    }
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="./styles.css"/>
</head>
<body>
<section class="registration">
         <div class="register_form">
            <form action="./login.php" method="POST">
                <h3>Login</h3>
                <input type="email" name="email" placeholder="Email Address"/>
                <input type="password" name="pwd" placeholder="Password"/>
                <button type="submit" name="submit-login">Sign In</button>
                <p>Don't Have Account?<a href="./register.php">Register</a></p>
                <small>AudioMac.inc</small>
            </form>
         </div>
     </section>
</body>
</html>