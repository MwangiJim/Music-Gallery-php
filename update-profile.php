<?php 
include './audio_mac.db.inc.php';
session_start();
$email = $_SESSION['session_id'];
//echo $email;
if(isset($_POST['submit-profile'])){
    $file = $_FILES['file'];
    //get file details
    $fileName = $file['name'];
    $fileType = $file['type'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $fileExt = explode('.',$fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowedExts = array('png','jpeg','jpg');
    if(in_array($fileActualExt,$allowedExts)){
         if($fileError === 0){
             if($fileSize < 300000){
                $fileActualName = "profile" . "." . uniqid("",true) . "." . $fileActualExt;
                $fileDestination = "./profile/" . $fileActualName;

                $sql = "UPDATE users SET profileimg_path = '$fileDestination' where email = '$email'";
                if(mysqli_query($conn,$sql)){
                    move_uploaded_file($fileTmpName,$fileDestination);
                    header('Location:./index.php?profileUpdate=success');
                    exit();
                }
                else{
                    header('Location:./index.php?error=ErrorUpdatingServer');
                    exit();
                }
             }else{
                header('Location:./index.php?error=FileSizeExceededLimit');
                exit();
             }
         }
         else{
            header('Location:./index.php?error=FileCorrupted');
            exit();
         }
    }else{
        header('Location:./index.php?error=ExtensionNotAllowed&&Exts={.png,.jpg,.jpeg}');
        exit();
    }
}
?>