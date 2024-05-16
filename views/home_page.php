
<?php include('layout/header.php'); ?>
<?php $title = "Home"; ?>
<?php include('layout/navbar.php'); ?>

<?php
  session_start();
  if($_SESSION['status'] == "login"){
    header("location:admin/dashboard.php");
  }
  error_reporting(0);
?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-md-6">
          <p class="mt-5">
            Selamat Datang, <br/>
            <font style="font-size: 30px;">SISTEM INFORMASI KUISIONER</font> <br/>
            <font style="color: grey;">Silahkan klik tombol dibawah untuk mengisi form kuisioner!</font>
          </p>
          <a href="formulir_kuisioner.php" class="btn btn-primary">Formulir Kuisioner</a>
        </div>
        <div class="col-md-5">
          <p><img src="../assets/img/home_page.png"></p>
        </div>
      </div>
    </div>
    <!-- End Jumbotron -->
    

<?php include('layout/footer.php'); ?>
