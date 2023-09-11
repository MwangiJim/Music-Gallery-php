<?php
 include './audio_mac.db.inc.php';
 $name=$email=$password='';

 $users_sql = "SELECT id,name,email FROM users";
 
 $res = mysqli_query($conn,$users_sql);
 $users = mysqli_fetch_all($res,MYSQLI_ASSOC);
 //print_r($users);
 if(isset($_POST['submit-register'])){
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['pwd'])){
        header('Location:./register.php?error=MissingInputFields');
        exit();
    }
    else{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];
        
        if(!preg_match('/^[a-zA-Z]/',$name)){
            header('Location:./register.php?error=InvalidNameFormat');
            exit();
        }
        else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            header('Location:./register.php?error=InvalidEmailFormat');
            exit();
        }
        else{
            $name = mysqli_real_escape_string($conn,$name);
            $email = mysqli_real_escape_string($conn,$email);
            $password = mysqli_real_escape_string($conn,$password);

            $hashPwd = password_hash($password,PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(name,email,password) VALUES('$name','$email','$hashPwd')";
             foreach($users as $user){
                 if($email === $user['email']){
                     header('Location:./register.php?error=UserAlreadyExists');
                 }
                 else{
                    if(mysqli_query($conn,$sql)){
                        header('Location:./login.php?userCreate==success&&success==true');
                        exit();
                    }
                    else{
                        echo 'Error Mysql Database'. mysqli_error($conn);
                        exit();
                        die();
                    }
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
            <form action="./register.php" method="POST">
                <h3>Create Account</h3>
                <input type="text" name="name" placeholder="UserName"/>
                <input type="email" name="email" placeholder="Email Address"/>
                <input type="password" name="pwd" placeholder="Password"/>
                <button type="submit" name="submit-register">Create Account</button>
                <p>Already Have Account?<a href="./index.php">Login</a></p>
                <small>AudioMac.inc</small>
            </form>
         </div>
     </section>
</body>
</html>