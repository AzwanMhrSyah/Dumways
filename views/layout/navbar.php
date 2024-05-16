<?php
  session_start();
  error_reporting(0);
  if($_SESSION['status'] != "login"){
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Sistem Kuisioner e-Court</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php if($title == "Home"){echo 'active';} ?>" aria-current="page" href="home_page.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title == "Survey"){echo 'active';} ?>" href="formulir_kuisioner.php">Survey</a>
        </li>&nbsp;&nbsp;&nbsp;
        <li class="nav-item">
          <a href="login.php" class="btn btn-outline-light">Login &nbsp; <i class="fa fa-right-to-bracket"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<?php } else { ?>

  <?php
    $email = $_SESSION['email'];
    $user  = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
  ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Sistem Kuisioner e-Court</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php if($title == "Dashboard"){echo 'active';} ?>" aria-current="page" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title == "Data Pertanyaan"){echo 'active';} ?>" href="data_pertanyaan.php">Pertanyaan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title == "Survey"){echo 'active';} ?>" href="hasil_survey.php">Survey</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($title == "Perhitungan"){echo 'active';} ?>" href="perhitungan.php">Perhitungan</a>
        </li>&nbsp;&nbsp;&nbsp;
        <li class="nav-item">
          <!-- <a href="../../controllers/logout.php" class="btn btn-outline-light">Logout &nbsp; <i class="fa fa-right-to-bracket"></i></a> -->
          <div class="btn-group">
            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <?php 
                foreach ($user as $value) {
                  echo $value['nama'];
                }
              ?>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="../../controllers/logout.php">Logout</a></li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<?php } ?>