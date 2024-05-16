<?php
  session_start();
  if($_SESSION['status'] != "login"){
    header("location:../login.php");
  }
?>

<?php include('../layout/header.php'); ?>

<?php
  include('../../config.php'); 
  $title = "Dashboard"; 
  error_reporting(0);

  $pertanyaan   = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
  $c_pertanyaan = mysqli_num_rows($pertanyaan);

  $pengunjung   = mysqli_query($koneksi, "SELECT * FROM pengunjung");
  $c_pengunjung = mysqli_num_rows($pengunjung); 

  $email = $_SESSION['email'];
  $user  = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mb-3">
            <div class="card-header"><span class="text-primary"><?= $title; ?></span></div>
            <div class="card-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 mb-0">
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    Selamat Datang, <?php foreach($user as $u){echo $u['nama'];} ?> !
                    <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Jumlah pertanyaan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $c_pertanyaan; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Jumlah Responden</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $c_pengunjung; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Requests</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
              </div>
              <div class="row">
                <?php foreach ($pertanyaan as $row) { ?>
                  <div class="col-md-4 mb-4">
                    <div class="card shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div style="font-size: 13px;" class="font-weight-bold text-black mb-4">
                                          <?= $row['pertanyaan']; ?>
                                      </div>
                                      
                                      <div class="row">
                                        <div style="font-size: 13px;" class="mb-0 text-gray-800">
                                          <?php 
                                            $id_pertanyaan = $row['id'];
                                            $jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE pertanyaan_id = '$id_pertanyaan'");

                                            foreach ($jawaban as $jb) {
                                              $id_jawaban = $jb['id'];
                                              $survey = mysqli_query($koneksi, "SELECT * FROM survey WHERE jawaban_id = '$id_jawaban'");
                                          ?>

                                            <div style="font-weight: 400 !important; padding: 5px 5px 5px 5px;" class="badge bg-secondary">
                                              <?= $jb['jawaban']; ?> : <?= $survey->num_rows; ?>
                                            </div>

                                          <?php } ?>

                                        </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                <?php } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Jumbotron -->
    

<?php include('../layout/footer.php'); ?>
