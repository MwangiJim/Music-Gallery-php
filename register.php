<?php
 include './audio_mac.db.inc.php';
 $name=$email=$password='';

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
            $file = $_FILES['file'];
            
            $fileName = $file['name'];
            $fileType = $file['type'];
            $fileTmpName = $file['tmp_name'];
            $fileError = $file['error'];
            $fileSize = $file['size'];

            $fileExt = explode('.',$fileName);
            $fileActualExt = strtolower(end($fileExt));
            $allowedExts = array('png','jpg','jpeg');
            
            $sql_users = "SELECT email FROM users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql_users);
            mysqli_stmt_bind_param($stmt,'s',$email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $row = mysqli_stmt_num_rows($stmt);
            //print_r($row);
            if($row > 0){
                header('Location:./register.php?error=UserEmailAlreadyTaken');
                exit();
            }
            else{
                    if(in_array($fileActualExt,$allowedExts)){
                        if($fileError === 0){
                            if($fileSize < 200000){
                               $fileActualName = "profile" . "." . uniqid("",true) . "." . $fileActualExt;
                               $fileDestination = "./profile/" . $fileActualName;
                               $sql = "INSERT INTO users(name,email,password,profileimg_path) VALUES(?,?,?,?)";

                               $statement = mysqli_stmt_init($conn);
                               mysqli_stmt_prepare($statement,$sql);
                               mysqli_stmt_bind_param($statement,'ssss',$name,$email,$hashPwd,$fileDestination);
                               mysqli_stmt_execute($statement);
                               move_uploaded_file($fileTmpName,$fileDestination);
                               header('Location:./index.php?userCreate=true&&profileupload==true');
                               exit();
                            }else{
                                header('Location:./index.php?error=FileSizeExceededLimit');
                                exit();
                            }
                        }else{
                            header('Location:./register.php?error=FileCorrupted&upload=empty');
                            exit();
                        }
                    }
                    else{
                        header('Location:./register.php?error=fileExtensionNotAllowed&&AllowedExts=.png&.jpeg&.png&&upload=empty');
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
    <style>
        form{
            align-items: center;
            text-align: center;
        }
        .register_form form .input{
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
            <form action="./register.php" method="POST" enctype="multipart/form-data">
                <h3>Create Account</h3>
                <img src="./Images/profile.png"/>
                <input class="input" type="text" name="name" placeholder="UserName" />
                <input class="input" type="email" name="email" placeholder="Email Address"/>
                <input class="input" type="password" name="pwd" placeholder="Password"/>
                <label>Add a Profile Picture</label>
                <br/>
                       <input type="file" name="file"/>
                       <br/>
                <button type="submit" name="submit-register">Create Account</button>
                <p>Already Have Account?<a href="./index.php">Login</a></p>
                <small>AudioMac.inc</small>
            </form>
         </div>
     </section>
</body>
</html>