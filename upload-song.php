<?php 
 include './audio_mac.db.inc.php';
$title =$description = '';
 if(isset($_POST['submit-file'])){
    $title = $_POST['title'];
    $title = strtolower(str_replace(' ','-',$title));
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $artist = $_POST['artist'];

    $audio_file = $_FILES['audio-file'];
    $image_file = $_FILES['image-file'];

    print_r($audio_file);
    print_r($image_file);
    //image details
    $fileName = $image_file['name'];
    $fileType = $image_file['type'];
    $fileTmpName = $image_file['tmp_name'];
    $fileError = $image_file['error'];
    $fileSize = $image_file['size'];

    $fileExt = explode('.',$fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    $includedExts = array('jpg','jpeg','png');
    //music details;
    $audioName = $audio_file['name'];
    $audioType = $audio_file['type'];
    $audioTmpName = $audio_file['tmp_name'];
    $audioError = $audio_file['error'];
    $audioSize = $audio_file['size'];

    $audioExt = explode('.',$audioName);
    $actualAudioExt = strtolower(end($audioExt));

    $allowedAudioExts = array('mp3');
    if(in_array($fileActualExt,$includedExts) && in_array($actualAudioExt,$allowedAudioExts)){
        if($fileError === 0 && $audioError === 0){
           if($fileSize < 200000){
             $fileActualName = $title .".". $fileActualExt;
             $fileDestination = './audio_banner/gallery/' . $fileActualName;

             $audioActualName = $title . "." . $actualAudioExt;
             $audioDestination = './audio/songs/' . $audioActualName;

             if(empty($_POST['title']) || empty($_POST['description']) || empty($_POST['artist']) || empty($_POST['genre'])){
                header('Location:./upload-song.php?error=&&upload==empty');
                exit();
             }
             else{
                $title = mysqli_real_escape_string($conn,$title);
                $description = mysqli_real_escape_string($conn,$description);
                $genre = mysqli_real_escape_string($conn,$genre);
                $artist = mysqli_real_escape_string($conn,$artist);

                $sql = "INSERT INTO music(musicbanner_path,music_path,music_name,music_description,artist,genre)
                VALUES('$fileDestination','$audioDestination','$title','$description','$artist','$genre')";
                //to be continued...............//
                if(mysqli_query($conn,$sql)){
                    header('Location:./index.php?upload==success');
                    move_uploaded_file($fileTmpName,$fileDestination);
                    move_uploaded_file($audioTmpName,$audioDestination);
                    exit();
                }
                else{
                    header('Location:./upload.php?error==upload&&upload === empty');
                    echo mysqli_error($conn);
                    exit();
                }
             }
           }
        }
        else{
            header('Location:./upload-song.php?error=FileErrorDetected');
            exit();
        }
    }else{
        header('Location:./upload-song.php?error=FileExtensionNotAllowed');
        exit();
    }
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="./styles.css"/>
</head>
<body>
    <section class="song_form">
        <div class="song_box">
            <div class="song_header">
                <div class="song_header_left">
                  <img src="./Images/music.jpg"/>
                  <h4>Update Music Form</h4>
                </div>
                <img src="./Images/close.png" class="close"/>
            </div>
            <hr/>
            <form action="./upload-song.php" method="POST" enctype="multipart/form-data">
                <label>Title</label>
                <br/>
                <input type="text" class="input" name="title" placeholder="Title">
                <br/>
                <label>Description</label>
                <br/>
                <input type="text" class="input" name="description" placeholder="Description">
                <br/>
                <label>Artist</label>
                <br/>
                <input type="text" class="input" name="artist" placeholder="Artist">
                <br/>
                <label>Genre</label>
                <br/>
                <input type="text" class="input" name="genre" placeholder="Genre">
                <br/>
                <label>Audio File</label>
                <input type="file" name="audio-file"/>
                <br/>
                <label>Song Banner</label>
                 <input type="file" name="image-file"/>
                 <br/>
                 <div class="bottom">
                    <button type="submit" name="submit-file"><img src="./Images/upload.png"/>Upload</button>
                    <a href="./index.php"><img src="./Images/close.png"/>Cancel</a>
                 </div>
            </form>
        </div>
    </section>
</body>
</html>
