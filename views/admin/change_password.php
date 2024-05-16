<?php
  session_start();
  if($_SESSION['status'] != "login"){
    header("location:../login.php");
  }
?>

<?php include('../layout/header.php'); ?>

<?php 
  include('../../config.php');
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-6">
          <div class="card mb-3">
            <div class="card-header">
              <span class="text-primary">Change Password</span>
            </div>
            <div class="card-body">
              <!-- <h5 class="card-title text-center">Formulir Kuisioner</h5>
              <p class="card-text text-center">Silahkan isi formulir dibawah ini dengan benar.</p> -->

              <div class="mt-0">
                <?php if(isset($_GET['pesan'])){ ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_GET['pesan']; ?>
                    <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                  </div>
              <?php } ?>
              <?php if(isset($_GET['pesanError'])){ ?> 
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?= $_GET['pesanError']; ?>
                  <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                </div>
              <?php } ?>
              </div>

              <div class="mt-3">
                <form action="../../controllers/change_password.php" method="post">
                  <div class="mt-0">
                    <div class="col-sm-12">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Password Lama</label>
                        <input type="password" required class="form-control" id="old_password" name="old_password">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Password Baru</label>
                        <input type="password" required class="form-control" id="new_password" name="new_password">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="mb-">
                        <label for="exampleInputEmail1" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" required class="form-control" id="confirm_password" name="confirm_password">
                      </div>
                    </div>
                  </div>
                  <div class="col mt-4">
                    <div class="float-right">
                      <button class="btn btn-secondary" type="button" onclick="history.back();">Kembali</button>
                      <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Jumbotron -->

    <script>
      window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
        });
      }, 3000);
    </script>

<?php include('../layout/footer.php'); ?>
