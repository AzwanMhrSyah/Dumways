<?php
  session_start();
  if($_SESSION['status'] != "login"){
    header("location:../login.php");
  }
?>

<?php include('../layout/header.php'); ?>

<?php 
  include('../../config.php');
  $title = "Data Pertanyaan"; 
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mb-3">
            <div class="card-header">
              <span class="text-primary">Data Jawaban</span>
              <button type="button" class="float-end btn btn-primary btn-sm rounded" data-bs-toggle="modal" data-bs-target="#tambah"><i class="fa fa-plus"></i> Tambah Jawaban</button>
            </div>
            <div class="card-body">
              <!-- <h5 class="card-title text-center">Formulir Kuisioner</h5>
              <p class="card-text text-center">Silahkan isi formulir dibawah ini dengan benar.</p> -->

              <div class="mt-3">
                <label>Pertanyaan :</label>
                <div class="alert alert-primary" role="alert">
                  <?php 
                    $id_pertanyaan = $_GET['id'];
                    $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE id = '$id_pertanyaan'");

                    while ($row = mysqli_fetch_array($pertanyaan)) {
                  ?>

                    <?= $row['pertanyaan']; ?>

                  <?php } ?>
                </div>
              </div>

              <div class="mt-3">
                <?php if(isset($_GET['pesan'])){ ?>
                  <div class="alert alert-success alert-dismissible fade show away" role="alert">
                    <?= $_GET['pesan']; ?>
                    <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                  </div>
              <?php } ?>
              <?php if(isset($_GET['pesanError'])){ ?> 
                <div class="alert alert-danger alert-dismissible fade show away" role="alert">
                  <?= $_GET['pesanError']; ?>
                  <!-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
                </div>
              <?php } ?>
              </div>

              <div class="mt-3">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Jawaban</th>
                        <th>Skor</th>
                        <th class="text-center" width="15%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE pertanyaan_id = '$id_pertanyaan'");

                        while ($jb = mysqli_fetch_array($jawaban)) {

                      ?>
                        <tr>
                          <td><?= $jb['jawaban']; ?></td>
                          <td><?= $jb['skor']; ?></td>
                          <td class="text-center">
                            <a href="../../controllers/delete_jawaban.php?id=<?= $jb['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini ?');">Delete</a>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="mt-3">
                <a href="data_pertanyaan.php" class="float-start btn btn-secondary">Kembali</a>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Jumbotron -->

    <!-- Modal -->
    <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="tambah" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="tambah">Tambah data jawaban</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../../controllers/add_jawaban.php?id=<?= $id_pertanyaan; ?>" method="post">
            <div class="modal-body">
              <div class="form-group">
                  <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="jawaban" name="jawaban"></textarea>
                    <label for="floatingTextarea">Jawaban</label>
                  </div>
              </div>
              <div class="form-group">
                <label>Skor</label>
                <input type="number" required name="skor" id="skor" class="form-control">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Tambah data</button>
            </div>
          </form>

        </div>
      </div>
    </div>
    
    <script>
      window.setTimeout(function() {
        $(".away").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
        });
      }, 3000);
    </script>

<?php include('../layout/footer.php'); ?>
