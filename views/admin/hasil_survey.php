<?php
  session_start();
  if($_SESSION['status'] != "login"){
    header("location:../login.php");
  }
?>

<?php include('../layout/header.php'); ?>

<?php 
  include('../../config.php');
  $title = "Survey"; 
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mb-3">
            <div class="card-header">
              <span class="text-primary"><?= $title; ?></span>
              <!-- <button type="button" class="float-end btn btn-primary btn-sm rounded" data-bs-toggle="modal" data-bs-target="#tambah"><i class="fa fa-plus"></i> Cetak Laporan</button> -->
            </div>
            <div class="card-body">
              <!-- <h5 class="card-title text-center">Formulir Kuisioner</h5>
              <p class="card-text text-center">Silahkan isi formulir dibawah ini dengan benar.</p> -->

              <div class="mt-3">
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
                <div class="table-responsive">
                  <table id="myTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="20%">Nama</th>
                        <th>Email</th>
                        <th>L/P</th>
                        <th>Umur</th>
                        <th>Pekerjaan</th>
                        <!-- <th class="text-center">Tanggal Survey</th> -->
                        <th class="text-center" width="24%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $i = 0;
                        $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung ORDER BY tanggal_survey ASC");

                        while ($row = mysqli_fetch_array($pengunjung)) {
                          $i++;
                      ?>
                      <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['jk']; ?></td>
                        <td><?= $row['umur']; ?></td>
                        <td><?= $row['pekerjaan']; ?></td>
                        <!-- <td><?= date('d-m-Y', strtotime($row['tanggal_survey'])) ?></td> -->
                        <td class="text-center">
                          <a href="detail_jawaban.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">Lihat jawaban</a>
                          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#skor<?= $row['id']; ?>">Skor</button>
                          <a href="../../controllers/delete_survey.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini ?');">Delete</a>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Jumbotron -->

    <!-- Modal -->
    <?php 
      foreach($pengunjung as $row){ 
      $id = $row['id'];
    ?>
    <div class="modal fade" id="skor<?= $row['id']; ?>" tabindex="-1" aria-labelledby="tambah" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="tambah">Skor</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
              <div class="form-group">
                <table class="table">
                  <tr>
                    <th width="30%">Skor Jawaban</th>
                    <th width="1%">:</th>
                    <th>
                      <?php   
                        $skor_survey = mysqli_query($koneksi, "SELECT survey.*, jawaban.skor 
                                          FROM survey 
                                          INNER JOIN jawaban ON survey.jawaban_id = jawaban.id
                                          WHERE pengunjung_id = '$id'");
                        $skor = 0;
                        foreach($skor_survey as $sr){
                          $skor += $sr['skor'];
                        }

                        echo $skor;
                      ?>
                    </th>
                  </tr>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
    <?php } ?>

    <script>
      window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
        });
      }, 3000);
    </script>

<?php include('../layout/footer.php'); ?>
