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
  $id    = $_GET['id'];
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mb-3">
            <div class="card-header">
              <span class="text-primary">Detail jawaban</span>
              <a href="hasil_survey.php" class="float-end btn btn-primary btn-sm rounded">Kembali</a>
            </div>
            <div class="card-body">
              <div class="mt-3">
                <div class="row">
                  <div class="col-sm-6">
                    <table class="table table-striped table-bordered">
                      <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>L/P</th>
                        <th>Umur</th>
                      </tr>
                      <?php
                        $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung WHERE id = '$id'");

                        foreach($pengunjung as $row){
                      ?>

                      <tr>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['jk']; ?></td>
                        <td><?= $row['umur']; ?></td>
                      </tr>

                      <?php } ?>
                    </table>
                    <!-- <table class="table">
                      <tr>
                        <th width="40%">Skor Jawaban</th>
                        <th width="1%">:</th>
                        <th>
                          <?php   
                            $skor_survey = mysqli_query($koneksi, "SELECT survey.*, jawaban.skor 
                                              FROM survey 
                                              INNER JOIN jawaban ON survey.jawaban_id = jawaban.id
                                              WHERE pengunjung_id = '$id'");
                            foreach($skor_survey as $sr){
                              $skor += $sr['skor'];
                            }

                            echo $skor;
                          ?>
                        </th>
                      </tr>
                    </table> -->
                  </div>
                  <div class="col-sm-6">
                    <?php 

                      $i = 0;
                      $survey = mysqli_query($koneksi, "SELECT survey.*, jawaban.jawaban, jawaban.pertanyaan_id, pertanyaan.pertanyaan FROM survey LEFT JOIN jawaban ON survey.jawaban_id = jawaban.id LEFT JOIN pertanyaan ON jawaban.pertanyaan_id = pertanyaan.id WHERE pengunjung_id = '$id'");

                    ?>

                    <?php if($survey->num_rows > 0){ ?>
                      <?php 
                        while ($e_row = mysqli_fetch_array($survey)) {
                        $i++; 
                      ?>
                      <table class="table table-striped table-bordered">
                        <tr>
                          <td width="5%"><?= $i; ?></td>
                          <td width="90%"><?= $e_row['pertanyaan']; ?></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td><?= $e_row['jawaban']; ?></td>
                        </tr>
                      </table>
                      <?php } ?>
                    <?php }else{ ?>
                      <table class="table table-bordered">
                        <tr>
                          <td width="90%" class="text-center">Belum mengisi survey !</td>
                        </tr>
                      </table>
                    <?php } ?>
                    
                    

                  </div>
                </div>
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
