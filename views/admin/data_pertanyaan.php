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

  $kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mb-3">
            <div class="card-header">
              <span class="text-primary"><?= $title; ?></span>
              <button type="button" class="float-end btn btn-primary btn-sm rounded" data-bs-toggle="modal" data-bs-target="#tambah"><i class="fa fa-plus"></i> Tambah data</button>
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
                        <th width="30%">Pertanyaan</th>
                        <th>Kategori</th>
                        <th class="text-center" width="20%">Jawaban</th>
                        <th class="text-center" width="25%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $i = 0;
                        $pertanyaan = mysqli_query($koneksi, "SELECT pertanyaan.*, kategori.kategori 
                          FROM pertanyaan INNER JOIN kategori ON pertanyaan.kategori_id = kategori.id
                          ORDER BY kategori.id ASC");

                        while ($row = mysqli_fetch_array($pertanyaan)) {
                          $i++;
                      ?>
                      <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= $row['pertanyaan']; ?></td>
                        <td><?= $row['kategori']; ?></td>
                        <td>
                          <div class="card" style="width: 18rem;">
                            <ul class="list-group list-group-flush">
                              <?php 
                                $id_pertanyaan = $row['id'];
                                $jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE pertanyaan_id = '$id_pertanyaan'");

                                while ($jb = mysqli_fetch_array($jawaban)) {

                              ?>
                              
                                <li class="list-group-item"><?= $jb['jawaban']; ?></li>

                              <?php } ?>
                              
                            </ul>
                          </div>
                        </td>
                        <td class="text-center">
                          <a href="jawaban.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">Atur jawaban</a>
                          <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id'] ?>">Edit</button>
                          <a href="../../controllers/delete_pertanyaan.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini ?');">Delete</a>
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
    <div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="tambah" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="tambah">Tambah data pertanyaan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../../controllers/add_pertanyaan.php" method="post">
            <div class="modal-body">
              <div class="form-group">
                  <div class="form-floating">
                    <textarea style="height: 100px" class="form-control" required id="pertanyaan" name="pertanyaan"></textarea>
                    <label for="floatingTextarea">Pertanyaan</label>
                  </div>
              </div>
              <div class="form-group">
                  <label for="floatingTextarea">Ketegori</label>
                  <select class="form-control" required name="kategori_id" id="kategori_id">
                    <option value="">-- pilih kategori --</option>
                    <?php foreach($kategori as $kt){ ?>
                      <option value="<?= $kt['id']; ?>"><?= $kt['kategori']; ?></option>
                    <?php } ?>
                  </select>
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

    <!-- Modal Edit -->
    <?php
      $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
      while ($row = mysqli_fetch_array($pertanyaan)) {
    ?>
    <div class="modal fade" id="edit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="edit">Edit data pertanyaan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form action="../../controllers/update_pertanyaan.php?id=<?= $row['id']; ?>" method="post">
            <div class="modal-body">
              <div class="form-group">
                  <div class="form-floating">
                    <textarea style="height: 100px" class="form-control" required id="pertanyaan" name="pertanyaan"><?= $row['pertanyaan']; ?></textarea>
                    <label for="floatingTextarea">Pertanyaan</label>
                  </div>
              </div>
              <div class="form-group">
                  <label for="floatingTextarea">Ketegori</label>
                  <select class="form-control" required name="kategori_id" id="kategori_id">
                    <option value="">-- pilih kategori --</option>
                    <?php foreach($kategori as $kt){ ?>
                      <?php if($row['kategori_id'] == $kt['id']){ ?>
                        <option value="<?= $kt['id']; ?>" selected><?= $kt['kategori']; ?></option>
                      <?php }else{ ?>
                        <option value="<?= $kt['id']; ?>"><?= $kt['kategori']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>

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
