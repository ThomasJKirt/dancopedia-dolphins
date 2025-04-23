<?php
session_start();

$user_type=" ";

if(isset($_SESSION["admin_name"])){
   $user_type="admin";
}

else if(isset($_SESSION["user_name"])){
  $user_type="user";
}
else{
  $user_type="guest";
}

$toolBar=" "
if($user_type=="admin"){
  $toolBar="adminhome.php";
}
else if($user_type=="user"){
  $toolBar="userhome.php";
}
else if($user_type=="guest"){
  $toolBar="toolbar.html";
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Dance Catagories</title>
  <link rel="stylesheet" href="css/Catagories.css">
</head>

<body>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  </head>

  <body>

    <header>
      <div id="toolbar-placeholder">
        <?php include($toolBar); ?>
      </div>
    </header>

    <section class="one">
      <div class="background">
        <div class="scrollContainer">
          <img src="../assets/images/brazil_flag.jpg" alt="Brazil Flag" class="flag">
          <h1>Catagories</h1>
          <div class="danceContainer" id="danceContainer">
            <div class="Catagories">
              <div class="Catagorie" onclick="window.location.href='Traditional.html'">
                <h2>Traditional</h2>
              </div>
              <div class="Catagorie">
                <h2>Ballroom</h2>
              </div>
              <div class="Catagorie">
                <h2>Folk</h2>
              </div>
              <div class="Catagorie">
                <h2>Modern</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="two"></section>
    <section class="three"></section>
    <div class="fixed-state">

      <div class="container mt-4">
        <div class="card mx-auto" style="max-width:400px">
          <!-- Header -->
          <div class="card-header bg-transparent" style="padding: 10px; width: 400px" ;>
            <div class="navbar navbar-expanded p-0">
              <ul class="navbar-nav me-auto align-items-center">
                <li class="nav-item d-flex align-items-center">
                  <a href="#!" class="nav-link">
                    <div class="position-relative"
                      style="width:40px; height: 40px; border-radius: 50% ; border: 2px solid #e84118; padding: 2px;">
                      <img src="../assets/images/chatbox_face.jpg" class="img-fluid" alt=""
                           style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                      <span
                        class="position-absolute bottom-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                      </span>
                    </div>
                  </a>
                  <a href="#!" class="nav-link ms-2">Ask Me!</a>
                </li>
              </ul>
              <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                  <a href="#!" class="nav-link d-flex align-items-center" id="controlButtons">
                    <i class="fas fa-window-minimize me-2" id="minimizeBtn"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- Message Area -->
          <div class="card-body p-4" style="height: 250px; overflow: auto;">
            <div class="d-flex align-items-baseline mb-4">
              <div class="position-relative avatar">
                <img src="../assets/images/chatbox_face.jpg" class="img-fluid" alt=""
                     style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                <span
                  class="position-absolute bottom-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                  <span class="visually-hidden">New alerts</span>
                </span>
              </div>
              <div class="pe-2">
                <div>
                  <div class="card d-inline-block p-2 px-3 m-1">Welcome, What Do You Wanna Learn?</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Input Area -->
          <div class="input-area">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Type a message...">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-paper-plane"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </body>

</html>
