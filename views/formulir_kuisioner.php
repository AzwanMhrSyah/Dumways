
<?php
  session_start();
  if($_SESSION['status'] == "login"){
    header("location:admin/dashboard.php");
  }
?>

<?php 
  include('../config.php');
  $title = "Survey"; 
  error_reporting(0);
?>

<?php include('layout/header.php'); ?>

<!--   <style type="text/css">
    body{
      min-height: 1000px;
    }
  </style> -->

<?php include('layout/navbar.php'); ?>

    

    <!-- Jumbotron -->
    <div class="container section mb-5">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center">Formulir Kuisioner</h5>
              <p class="card-text text-center">Silahkan isi formulir dibawah ini dengan benar.</p>

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

              <form action="../controllers/send_formulir.php" method="post">
                <div class="mt-4">
                  <p style="font-size: 20px; font-weight: bold;">Data Responden</p>
                  <hr style="border: 1px solid green; border-radius: 6px;">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Nama Lengkap</label>
                        <input type="text" required class="form-control" id="nama" name="nama">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email Address</label>
                        <input type="email" required class="form-control" id="email" name="email">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Umur</label>
                        <input type="number" required class="form-control" id="umur" name="umur">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" name="jk" id="jk" required>
                          <option value="">-- Pilih --</option>
                          <option value="L">Laki - Laki</option>
                          <option value="P">Perampuan</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Pekerjaan</label>
                        <input type="text" required class="form-control" id="pekerjaan" name="pekerjaan">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-4">
                  <p style="font-size: 20px; font-weight: bold;">Isi Formulir</p>
                  <hr style="border: 1px solid green; border-radius: 6px;">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="mb-3">

                        <table>
                          <?php
                            $i = 0;
                            $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan ORDER BY id ASC");

                            while ($row = mysqli_fetch_array($pertanyaan)) {
                              $i++;
                          ?>
                            <tr>
                              <td style="vertical-align: top;"><?= $i.'.'; ?> </td>
                              <td>

                                <?= $row['pertanyaan']; ?>

                                  <div style="margin-top: 5px;">
                                    <?php 
                                      $id_pertanyaan = $row['id'];
                                      $jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE pertanyaan_id = '$id_pertanyaan' ORDER BY skor DESC");

                                      while ($jb = mysqli_fetch_array($jawaban)) {
                                        $id_jawaban = $jb['id'];
                                    ?>

                                    <div style="padding-left: 4px;">
                                      <div class="form-check">
                                        <input class="form-check-input" required type="radio" name="check[<?= $id_pertanyaan; ?>]" value="<?= $id_jawaban; ?>">
                                        <label class="form-check-label">
                                          <?= $jb['jawaban']; ?>
                                        </label>
                                      </div>
                                    </div>

                                    <?php } ?>
                                  </div>

                                <br>
                              </td>
                            </tr>
                          <?php } ?>
                        </table>

                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-4">
                  <p class="grey-text" style="font-size: 15px; text-align: center;">Pastikan jawaban yang anda isi sudah benar, survey hanya bisa dilakukan 1 kali dengan 1 email</p>
                  <center>
                    <table>
                      <tr>
                        <td style="text-align: center; vertical-align: middle;">
                          <div class="form-check">
                            <input class="form-check-input" required type="checkbox" value="check" id="" name="">
                              Ya, kuisioner ini sudah diisi dengan benar
                            </label>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </center>

                  <button type="submit" class="btn btn-primary col-sm-12 mt-4">Submit</button>
                </div>

              </form>
            </div>
          </div>
        </div>
        <div class="col-sm-2"></div>
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

<?php include('layout/footer.php'); ?>
