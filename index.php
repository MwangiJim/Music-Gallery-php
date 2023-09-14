<?php
  include './audio_mac.db.inc.php';
  session_start();
  if(isset($_POST['submit-logout'])){
    session_start();
    session_unset();
    session_destroy();
    header('Location:./index.php');
    die();
  }

  $sql = 'SELECT * FROM music';
  $res = mysqli_query($conn,$sql);

  $music_data = mysqli_fetch_all($res,MYSQLI_ASSOC);
 // print_r($music_data);
$id = '';
$music_info;
 if(isset($_POST['submit-song'])){
  $id = $_POST['id'];
  //echo $id;
  //to be continued....//
  $get_song_sql = "SELECT * FROM music WHERE id = $id";
  $res_get_song = mysqli_query($conn,$get_song_sql);
  $music_info = mysqli_fetch_assoc($res_get_song);
 }

 $email_val = $_SESSION['session_id'];
 $sql_user = "SELECT id,name,email,profileimg_path FROM users WHERE email = '$email_val'";
 $sql_res = mysqli_query($conn,$sql_user);
 $response = mysqli_fetch_assoc($sql_res);
// print_r($response);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Mac with PHP&MySQL</title>
    <link rel="stylesheet" href="./styles.css"/>
    <style>
      .profile{
        width:30px;
        height: 30px;
        cursor: pointer;
      }
      .controls{
        display: flex;
        justify-content: center;
        align-items: center;
        padding:20px 0 10px;
      }
       .controls img{
            width: 25px;
            height: 25px;
            cursor: pointer;
            margin: 0 10px;
        }
        .form{
          height: 40px;
            width:400px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #efefef;
            border-radius: 10px;
        }
       .form input{
            height: 40px;
            width:100%;
            outline: none;
            border: none;
            padding: 0 15px;
            background: transparent;
            border-radius: 10px;
        }
        .form button{
          background-color: transparent;
          outline: none;
          border: none;
          padding: 10px;
        }
        .search_img{
          width:20px;
          height: 20px;
          cursor: pointer;
          margin-left: 10px;
          margin-top: 0px;
        }
        .musicBox{
          margin: 20px 0;
        }
        .musicBox .music_logo{
          width:40px;
          height: 40px;
        }
        .music-display{
          overflow: scroll;
          width: 99%;
          height: 70vh;
          max-height: 70vh;
          overflow-y: scroll;
          display:grid;
          grid-template-columns: repeat(4,250px);
          margin-top: 10px;
        }
        .music-card{
          width: 15%;
          padding: 15px;
          margin: 0 30px;
          text-align: left;
          cursor: pointer;
          grid-area: span 10;
        }
        .music-card img{
          width:220px;
          height:170px;
          border-radius: 10px;
          object-fit: cover;
          transition: 1s;
        }
        .music-card:hover img{
          transform: scale(1.05);
        }
        .music-card form button{
          background: transparent;
          border: none;
          outline: none;
          padding: 10px 0px;
          width: 50px;
        }
        .music-card form p{
          width: 100%;
        }
        .music-card form button img{
          width:20px;
          height: 20px;
          cursor: pointer;
          object-fit: cover;
        }
        .play-section{
          display: flex;
          justify-content: space-between;
          align-items: center;
          width:200px;
        }
        .profile-box {
          background-color: #fff3f3;
          padding: 10px 12px;
          border-radius: 10px;
        }
        .profile-box .top-section{
          display: flex;
          justify-content: left;
          align-items: center;
        }
        .profile-box img{
          width:80px;
          height: 80px;
          border-radius: 50%;
          object-fit: cover;
        }
        .profile-box .info{
          margin: 0 10px;
          text-align: left;
          color: #000;
        }
        .profile-box button{
          background-color: rgb(7, 49, 139);
          padding: 6px 20px;
          color: #efefef;
          cursor: pointer;
          border-radius: 7px;
          outline: none;
          border: none;
          margin: 5px 0;
        }
        .profile-box input{
          margin: 10px 0;
        }
    </style>
</head>
<body>
    <section class="container">
    <div class="header">
        <div class="left_header">
            <h2>AUDIOMAC</h2>
            <span></span>
            <li>Songs</li>
            <li>Genres</li>
            <li>Artists</li>
        </div>
        <div class="right_header">
        <img src="./Images/profile.png" class="profile"/>
        <?php if(isset($_SESSION['session_id'])):?>
            <form action="./index.php" method="POST">
              <button type="submit" name="submit-logout">LOGOUT</button>
            </form>
            <?php endif ?>
        </div>
    </div>
     <div class="music_container">
        <?php if(isset($_SESSION['session_id'])):?>
              <div class="music_box">
                <div class="left">
                   <li><a href="">Home</a></li>
                   <li><a href="">Artist</a></li>
                   <li><a href="">Releases</a></li>
                   <div class="musicBox">
                      <img src="./Images/music.jpg" class="music_logo"/>
                      <p> <?php echo $music_info['music_description'] ?><small>Audio.mac</small></p>
                      <small><?php echo $music_info['music_name']?></small>
                      <div class="controls">
                        <img src="./Images/prev.png"/>  
                        <img src="./Images/play_btn.png" class="playBtn" onclick="playAudio()"/>  
                        <img src="./Images/next.png"/>  
                      </div>
                      <audio controls download repeat class="song">
                        <source src="<?php echo $music_info['music_path'] ?>" type="audio/mp3"/>
                      </audio>
                   </div>
                    <div class="profile-box">
                     <div class="top-section">
                        <img src="<?php echo $response['profileimg_path'] ?>"/>
                        <div class="info">
                          <h4><?php  echo $response['name']?></h4>
                          <p><?php echo $response['email']?></p>
                        </div>
                     </div>
                     <form action="./index.php" method="POST" enctype="multipart/form-data">
                       <input type="file" name="file"/>
                       <button type="submit" name="submit-profile">Change Profile</button>
                     </form>
                   </div> 
                </div>
                <div class="right">
                  <div class="top_music_banner">
                  <div class="top_right">
                    <div class="info">
                    <li><a href="">Home</a></li>
                     <li><a href="">Releases</a></li>
                    </div>
                    <div class="add_button">
                        <a href="./upload-song.php">Add Song</a>
                    </div>
                  </div>
                  <h1>Releases</h1>
                  <div class="bar">
                    <div class="input-section">
                     <form action="./index.php" method="POST" class="form">
                        <input type="text" name="search-music" placeholder="Search"/>
                        <img src="./Images/search.png" class="search_img"/>
                      </form>
                    </div>
                    <div class="right-section">
                      <button>Featured</button>
                      <button>Popular</button>
                      <button>Newest</button>
                    </div>
                  </div>
                  </div>
                   <div class="music-display">
                      <?php foreach($music_data as $music){?>
                        <div class="music-card">
                          <form action="./index.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $music['id']?>"/>
                            <img src="<?php echo $music['musicbanner_path'] ?>"/>
                            <div class="play-section">
                              <h4><?php echo $music['music_name'] ?></h4>
                              <button type="submit" name="submit-song"><img src="./Images/play_btn.png" class="img_play"/></button>
                            </div>
                            <p><?php echo $music['music_description']?></p>
                          </form>
                        </div>
                        <?php }?>
                   </div>
                </div>
              </div>
              <?php else :?>
                <h3>You ain't logged in!</h3>
                <?php include './login.php' ?>
         <?php endif?>
     </div>
    <div class="footer">
        <p>Copyright Jim.Audio.Mac || 2023.Reserved Rights</p>
    </div>
    </section>
</body>
<script>
  let playBtn = document.querySelector('.playBtn');
  let audio = document.querySelector('.song');
  let img_play = document.querySelector('.img_play');
  //alert("Hello world")
  let playing = false;
 // playBtn.src="./Images/pause.png";
  function playAudio(){
      if(!playing){
        playSong()
      }
      else{
        pauseSong()
      }
      //alert('Hello')
  }

  function playSong(){
    playing = true;
      audio.play()
      playBtn.src="./Images/pause.png";  
      img_play.src="./Images/music.gif"
  }
  function pauseSong(){
    playing = false;
    audio.pause()
      playBtn.src="./Images/play_btn.png";
      img_play.src="./Images/play_btn.png";
  }
</script>
</html>