<?php
  session_start();
  if($_SESSION['status'] != "login"){
    header("location:../login.php");
  }
?>

<?php include('../layout/header.php'); ?>

<?php 
  include('../../config.php');
  $title = "Perhitungan"; 
  error_reporting(0);
?>

<!-- perhitungan -->
<?php   
                    
  $kategori = mysqli_query($koneksi, "SELECT * FROM kategori"); 

  foreach ($kategori as $kat) { //kategori
    $id_kategori = $kat['id'];

    $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

    if($v_pertanyaan->num_rows > 0){

      foreach($v_pertanyaan as $key => $per){ //pertanyaan
        $id_pertanyaan = $per['id'];

        //jawaban where id_pertanyaan
        $survey = mysqli_query($koneksi, "SELECT survey.*, jawaban.skor, jawaban.pertanyaan_id FROM survey LEFT JOIN jawaban ON survey.jawaban_id = jawaban.id WHERE jawaban.pertanyaan_id = '$id_pertanyaan'");

        $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung");

        if ($survey->num_rows > 0) {
          //sum skor
          $r_kep = 0;
          foreach ($survey as $s => $sur) {
            $r_kep += $sur['skor'];
          }

          //penjumlahan rata2 kepentingan dan rata2 persetujuan
          $r_kep_sum = $r_kep/$pengunjung->num_rows;
          $kep_sum   = round($r_kep_sum, 2);
        }else{
          $kep_sum = 0;
        }

        //input into table
        $perhitungan = mysqli_query($koneksi, "SELECT * FROM perhitungan WHERE pertanyaan_id = '$id_pertanyaan'");

        if($perhitungan->num_rows < 1){
          $add_perhitungan = mysqli_query($koneksi, "INSERT INTO perhitungan (pertanyaan_id, r_kepentingan, r_persetujuan) VALUES ('$id_pertanyaan', '$kep_sum', '$kep_sum')");
        }else{
          $update_perhitungan = mysqli_query($koneksi, "UPDATE perhitungan SET r_kepentingan = '$kep_sum', r_persetujuan = '$kep_sum' WHERE pertanyaan_id = '$id_pertanyaan'");
        }

      }//endforeach pertanyaan

    }

    //mencari max score
    $max_hitung = mysqli_query($koneksi, "SELECT max(r_kepentingan) as k_max 
                  FROM perhitungan
                  INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id
                  WHERE pertanyaan.kategori_id = '$id_kategori'");

    $row_hitung = mysqli_query($koneksi, "SELECT perhitungan.*
                  FROM perhitungan
                  INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id
                  WHERE pertanyaan.kategori_id = '$id_kategori'");

    $max = 0;
    foreach ($max_hitung as $m) {
      $max = $m['k_max'];
    }

    foreach ($row_hitung as $r) {
      $m_id_pertanyaan = $r['pertanyaan_id'];

      //max score
      // $m_hasil = $r['r_kepentingan'] * $max;
      $m_hasil = $r['r_kepentingan'] * 5;
      $m_score = round($m_hasil, 2);

      //weight score
      $w_hasil = $r['r_kepentingan'] * $r['r_persetujuan'];
      $w_score = round($w_hasil, 2);

      // echo $m_id_pertanyaan.'. '.$r['r_kepentingan'].' * '.$r['r_persetujuan'].' = '.$w_score.'<br/>';

      // echo $m_id_pertanyaan.'. '.$r['r_kepentingan'].' * '.$max.' = '.$m_score.'<br/>';

      $m_update_perhitungan = mysqli_query($koneksi, "UPDATE perhitungan SET m_score = '$m_score', w_score = '$w_score' WHERE pertanyaan_id = '$m_id_pertanyaan'");
    }

  }//endforeach kategori
  
?>

<?php include('../layout/navbar.php'); ?>
    

    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-12">
          <div class="card mb-3">
            <div class="card-header">
              <span class="text-primary"><?= $title; ?></span>
              <?php 
                  $c_survey = mysqli_query($koneksi, "SELECT * FROM survey");
                ?>

              <?php if($c_survey->num_rows > 0){ ?>
                <!-- <button type="button" class="float-end btn btn-primary btn-sm rounded" data-bs-toggle="modal" data-bs-target="#tambah"><i class="fa fa-print"></i> Cetak Laporan</button> -->
                <a href="laporan.php" class="float-end btn btn-primary btn-sm rounded" ><i class="fa fa-print"></i> Cetak Laporan</a>
              <?php } ?>
              
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

                <?php if($c_survey->num_rows > 0){ ?>

                  <!-- <div class="card"> -->
                    <!-- <div class="card-header"> -->
                      <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-selected="true">Dimensi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-max" type="button" role="tab" aria-selected="false">Maximum Score</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-weight" type="button" role="tab" aria-selected="false">Weight Score</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-webqual" type="button" role="tab" aria-selected="false">Webqual Index</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-gap-tab" data-bs-toggle="pill" data-bs-target="#pills-gap" type="button" role="tab" aria-selected="true">Nilai GAP</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-Tgap-tab" data-bs-toggle="pill" data-bs-target="#pills-Tgap" type="button" role="tab" aria-selected="false">Nilai GAP Per Dimensi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-hasil" type="button" role="tab" aria-selected="false">Hasil Akhir</button>
                        </li>
                      </ul>
                    <!-- </div> -->

                    <!-- <div class="card-body"> -->
                      <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                          <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                <tr class="text-center" style="color: black;">
                                  <th width="5%">#</th>
                                  <th width="20%">Dimensi</th>
                                  <th>Indikator</th>
                                  <th width="15%">Jumlah Nilai</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php   
                                  $i = 1;
                                  foreach ($kategori as $kat) {
                                    $id_kategori = $kat['id'];

                                    $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                                    foreach($v_pertanyaan as $key => $per){
                                      $id_pertanyaan = $per['id'];
                                ?>
                                    <tr>
                                      <?php if($key == 0){ ?>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>" class="text-center"><?= $i++; ?></td>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>"><?= $kat['kategori'] ?></td>
                                      <?php } ?>

                                      <!-- <td class="text-center">
                                        <?php
                                          if($per['kategori_id'] == 1){
                                            $list = "Us.";
                                          }elseif ($per['kategori_id'] == 2) {
                                            $list = "Iq.";
                                          }elseif ($per['kategori_id'] == 3) {
                                            $list = "Sq.";
                                          }else{
                                            $list = "Q.";
                                          }
                                        ?>
                                        <?= $list.$key + 1; ?>
                                      </td> -->
                                      <td><?= $per['pertanyaan']; ?></td>

                                      <td class="text-center">
                                      <?php
                                        $survey = mysqli_query($koneksi, "SELECT survey.*, jawaban.skor, jawaban.pertanyaan_id FROM survey LEFT JOIN jawaban ON survey.jawaban_id = jawaban.id WHERE jawaban.pertanyaan_id = '$id_pertanyaan'");

                                        $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung");

                                        $r_kep = 0;
                                        foreach ($survey as $s => $sur) {
                                          $r_kep += $sur['skor'];
                                        }                           

                                        echo $r_kep;
                                      ?>
                                      </td> 
                                    </tr>
                                  <?php } //end foreach v_pertanyaan ?>
                                <?php } //end foreach kategori ?>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="pills-max" role="tabpanel" aria-labelledby="pills-max-tab" tabindex="0">
                          <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                <tr class="text-center" style="color: black;">
                                  <th width="5%">#</th>
                                  <th width="20%">Dimensi</th>
                                  <th>Indikator</th>
                                  <th>Rata2 Kepentingan</th>
                                  <th>Rata2 Persetujuan</th>
                                  <th>Maximum Score</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php   
                                  $i = 1;
                                  foreach ($kategori as $kat) {
                                    $id_kategori = $kat['id'];

                                    $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                                    $t_m_score = 0;
                                    foreach($v_pertanyaan as $key => $per){
                                      $id_pertanyaan = $per['id'];
                                ?>
                                    <tr>
                                      <?php if($key == 0){ ?>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>" class="text-center"><?= $i++; ?></td>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>"><?= $kat['kategori'] ?></td>
                                      <?php } ?>
                                      <td class="text-center">
                                        <?php
                                          if($per['kategori_id'] == 1){
                                            $list = "Us.";
                                          }elseif ($per['kategori_id'] == 2) {
                                            $list = "Iq.";
                                          }elseif ($per['kategori_id'] == 3) {
                                            $list = "Sq.";
                                          }else{
                                            $list = "Q.";
                                          }
                                        ?>
                                        <?= $list.$key + 1; ?>
                                      </td>

                                      <?php
                                        $v_perhitungan = mysqli_query($koneksi, "SELECT perhitungan.*, pertanyaan.kategori_id FROM perhitungan INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id WHERE pertanyaan.id = '$id_pertanyaan'");

                                        foreach($v_perhitungan as $ht){
                                          $t_m_score += $ht['m_score'];
                                      ?>

                                        <td class="text-center"><?= $ht['r_kepentingan']; ?></td>
                                        <td class="text-center"><?= $ht['r_persetujuan']; ?></td>
                                        <td class="text-center"><?= $ht['m_score']; ?></td>

                                        <?php if($key == 0){ ?>
                                          <td rowspan="<?= $v_pertanyaan->num_rows; ?>">
                                            <?php 
                                              $sum_perhitungan = mysqli_query($koneksi, "SELECT SUM(m_score) as s_max, perhitungan.*, pertanyaan.kategori_id 
                                                FROM perhitungan 
                                                INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id
                                                WHERE pertanyaan.kategori_id = '$id_kategori'");

                                              foreach ($sum_perhitungan as $value) {
                                                $v_max = round($value['s_max'], 2);
                                                $t_max += $v_max; 
                                                echo $v_max;
                                              }
                                            ?>
                                          </td>
                                        <?php } ?>

                                      <?php } //end foreach v_perhitungan ?>
                                        
                                    </tr>

                                    <?php } //end foreach v_pertanyaan ?>
                                  <?php } //end foreach kategori ?>
                                  <tr style="color: black;" class="text-center">
                                    <td colspan="5"><span class="float-right">Total</span></td>
                                    <td><?= $t_max; ?></td>
                                    <td></td>
                                  </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="pills-weight" role="tabpanel" aria-labelledby="pills-weight-tab" tabindex="0">
                          <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                <tr class="text-center" style="color: black;">
                                  <th width="5%">#</th>
                                  <th width="20%">Dimensi</th>
                                  <th>Indikator</th>
                                  <th>Rata2 Kepentingan</th>
                                  <th>Rata2 Persetujuan</th>
                                  <th>Weight Score</th>
                                  <th>Total</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php   
                                  $i = 1;
                                  foreach ($kategori as $kat) {
                                    $id_kategori = $kat['id'];

                                    $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                                    foreach($v_pertanyaan as $key => $per){
                                      $id_pertanyaan = $per['id'];
                                ?>
                                    <tr>
                                      <?php if($key == 0){ ?>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>" class="text-center"><?= $i++; ?></td>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>"><?= $kat['kategori'] ?></td>
                                      <?php } ?>
                                      <td class="text-center">
                                        <?php
                                          if($per['kategori_id'] == 1){
                                            $list = "Us.";
                                          }elseif ($per['kategori_id'] == 2) {
                                            $list = "Iq.";
                                          }elseif ($per['kategori_id'] == 3) {
                                            $list = "Sq.";
                                          }else{
                                            $list = "Q.";
                                          }
                                        ?>
                                        <?= $list.$key + 1; ?>
                                      </td>

                                      <?php
                                        $v_perhitungan = mysqli_query($koneksi, "SELECT perhitungan.*, pertanyaan.kategori_id FROM perhitungan INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id WHERE pertanyaan.id = '$id_pertanyaan'");

                                        foreach($v_perhitungan as $ht){
                                      ?>
                                      <td class="text-center"><?= $ht['r_kepentingan']; ?></td>
                                      <td class="text-center"><?= $ht['r_persetujuan']; ?></td>
                                      <td class="text-center"><?= $ht['w_score']; ?></td>

                                      <?php if($key == 0){ ?>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>">
                                          <?php 
                                            $sum_perhitungan = mysqli_query($koneksi, "SELECT SUM(w_score) as s_weight, perhitungan.*, pertanyaan.kategori_id 
                                              FROM perhitungan 
                                              INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id
                                              WHERE pertanyaan.kategori_id = '$id_kategori'");

                                            foreach ($sum_perhitungan as $value) {
                                              $v_weight = round($value['s_weight'], 2);
                                              $t_weight += $v_weight;

                                              echo $v_weight;
                                            }
                                          ?>
                                        </td>
                                      <?php } ?>

                                      <?php } //end foreach v_perhitungan ?>
                                    </tr>

                                    <?php } //end foreach v_pertanyaan ?>
                                  <?php } //end foreach kategori ?>

                                  <tr style="color: black;" class="text-center">
                                    <td colspan="5"><span class="float-right">Total</span></td>
                                    <td><?= $t_weight; ?></td>
                                    <td></td>
                                  </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="pills-webqual" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                          <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                <tr class="text-center" style="color: black;">
                                  <th width="5%">#</th>
                                  <th width="20%">Dimensi</th>
                                  <th>Indikator</th>
                                  <th>Weight Score</th>
                                  <th>Maximum Score</th>
                                  <th>Webqual Index</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php   
                                  $i = 1;
                                  foreach ($kategori as $kat) {
                                    $id_kategori = $kat['id'];

                                    $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                                    foreach($v_pertanyaan as $key => $per){
                                      $id_pertanyaan = $per['id'];
                                ?>
                                    <tr>
                                      <?php if($key == 0){ ?>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>" class="text-center"><?= $i++; ?></td>
                                        <td rowspan="<?= $v_pertanyaan->num_rows; ?>"><?= $kat['kategori'] ?></td>
                                      <?php } ?>
                                      <td class="text-center">
                                        <?php
                                          if($per['kategori_id'] == 1){
                                            $list = "Us.";
                                          }elseif ($per['kategori_id'] == 2) {
                                            $list = "Iq.";
                                          }elseif ($per['kategori_id'] == 3) {
                                            $list = "Sq.";
                                          }else{
                                            $list = "Q.";
                                          }
                                        ?>
                                        <?= $list.$key + 1; ?>
                                      </td>

                                      <?php
                                        $v_perhitungan = mysqli_query($koneksi, "SELECT perhitungan.*, pertanyaan.kategori_id FROM perhitungan INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id WHERE pertanyaan.id = '$id_pertanyaan'");

                                        foreach($v_perhitungan as $ht){
                                      ?>
                                      <td class="text-center"><?= $ht['w_score']; ?></td>
                                      <td class="text-center"><?= $ht['m_score']; ?></td>
                                      <td class="text-center">
                                        <?php  
                                          $scr   = $ht['w_score']/$ht['m_score'];
                                          $v_scr = round($scr, 3);
                                          $t_scr += $v_scr; 

                                          echo $v_scr;
                                        ?>
                                      </td>

                                      <?php } //end foreach v_perhitungan ?>
                                    </tr>

                                    <?php } //end foreach v_pertanyaan ?>
                                  <?php } //end foreach kategori ?>

                                  <tr style="color: black;" class="text-center">
                                    <td colspan="5"><span class="float-right">Total</span></td>
                                    <td><?= $t_scr; ?></td>
                                  </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="pills-gap" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                          <div class="table-responsive">
                            <?php   
                              $i = 1;
                              foreach ($kategori as $kat) {
                                $id_kategori = $kat['id'];
                            ?>
                            <div class="mb-3 mt-3 text-center" style="color: black;">Nilai GAP <?= $kat['kategori']; ?></div>
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                <tr class="text-center" style="color: black;">
                                  <!-- <th width="5%" rowspan="2">#</th> -->
                                  <th width="20%" rowspan="2" style="vertical-align:middle;">Dimensi</th>
                                  <th rowspan="2" style="vertical-align:middle;">Indikator</th>
                                  <th colspan="2">Nilai</th>
                                  <th rowspan="2" style="vertical-align:middle;">GAP</th>
                                </tr>
                                <tr style="color: black;" class="text-center">
                                  <th>Persepsi</th>
                                  <th>Harapan</th>
                                </tr>
                              </thead>
                              <tbody>

                                <?php

                                  $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                                  $t_gap = 0;

                                  foreach($v_pertanyaan as $key => $per){
                                    $id_pertanyaan = $per['id'];
                                ?>
                                  <tr>
                                    <?php if($key == 0){ ?>
                                      <!-- <td rowspan="<?= $v_pertanyaan->num_rows; ?>" class="text-center"><?= $i++; ?></td> -->
                                      <td rowspan="<?= $v_pertanyaan->num_rows; ?>"><?= $kat['kategori'] ?></td>
                                    <?php } ?>
                                    <td class="text-center">
                                      <?php
                                        if($per['kategori_id'] == 1){
                                          $list = "Us.";
                                        }elseif ($per['kategori_id'] == 2) {
                                          $list = "Iq.";
                                        }elseif ($per['kategori_id'] == 3) {
                                          $list = "Sq.";
                                        }else{
                                          $list = "Q.";
                                        }
                                      ?>
                                      <?= $list.$key + 1; ?>
                                    </td>

                                    <?php
                                      $v_perhitungan = mysqli_query($koneksi, "SELECT perhitungan.*, pertanyaan.kategori_id FROM perhitungan INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id WHERE pertanyaan.id = '$id_pertanyaan'");

                                      foreach($v_perhitungan as $ht){
                                    ?>
                                    <td class="text-center"><?= $ht['w_score']; ?></td>
                                    <td class="text-center">5.00</td>
                                    <td class="text-center">
                                      <?php
                                        $gap = $ht['w_score'] - 5;
                                        $t_gap += $gap;
                                        echo $gap;
                                      ?>
                                    </td>

                                    <?php } //end foreach v_perhitungan ?>
                                  </tr>

                                  <?php } //end foreach v_pertanyaan ?>

                                  <tr style="color: black;" class="text-center">
                                    <td colspan="4"><span class="float-right">Total</span></td>
                                    <td><?= $t_gap; ?></td>
                                  </tr>
                              </tbody>
                            </table>
                            <br/><br/>
                            <?php } //end foreach kategori ?>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="pills-Tgap" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                          <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                  <tr class="text-center" style="color: black;">
                                  <th width="5%" rowspan="2" style="vertical-align:middle;">#</th>
                                  <th width="20%" rowspan="2" style="vertical-align:middle;">Dimensi</th>
                                  <th colspan="2">Nilai</th>
                                  <th rowspan="2" style="vertical-align:middle;">GAP</th>
                                </tr>
                                <tr style="color: black;" class="text-center">
                                  <th>Persepsi</th>
                                  <th>Harapan</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $responden = mysqli_query($koneksi, "SELECT * FROM pengunjung");

                                  $i = 1;
                                  $count = 0;
                                  foreach ($kategori as $kat) {
                                    $id_kategori = $kat['id'];                                  
                                ?>

                                  <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $kat['kategori']; ?></td>
                                      <?php 
                                        $v_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                                        $t_gap = 0;
                                        foreach($v_pertanyaan as $key => $per){
                                          $id_pertanyaan = $per['id'];

                                          $v_perhitungan = mysqli_query($koneksi, "SELECT perhitungan.*, pertanyaan.kategori_id FROM perhitungan INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id WHERE pertanyaan.id = '$id_pertanyaan'");

                                          foreach($v_perhitungan as $ht){        
                                            $gap = $ht['w_score'] - 5;
                                            $t_gap += $gap;
                                          }
                                        }

                                        $total_gap = $t_gap/$responden->num_rows;
                                        $hasil_gap = $total_gap - 5;
                                      ?>
                                    <td class="text-center"><?= round($total_gap, 2); ?></td>
                                    <td class="text-center">5.00</td>
                                    <td class="text-center"><?= round($hasil_gap, 2); ?></td>
                                  </tr>

                                <?php } //end foreach kategori ?>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="pills-hasil" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                          <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 15px;">
                              <thead>
                                <tr class="text-center" style="color: black;">
                                  <th width="5%">#</th>
                                  <th width="20%">Dimensi</th>
                                  <th>Maximum Score</th>
                                  <th>Weight Score</th>
                                  <th>Webqual Index</th>
                                  <th>Interprestasi Webqual Index</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $i = 1;
                                  $count = 0;
                                  foreach ($kategori as $kat) {
                                    $id_kategori = $kat['id'];                                  
                                ?>

                                  <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $kat['kategori']; ?></td>

                                      <?php 
                                        $sum_perhitungan = mysqli_query($koneksi, "SELECT SUM(m_score) as s_max, SUM(w_score) as s_weight, perhitungan.*, pertanyaan.kategori_id 
                                          FROM perhitungan 
                                          INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id
                                          WHERE pertanyaan.kategori_id = '$id_kategori'");

                                        foreach ($sum_perhitungan as $value) {
                                          $count  = $count + 1;
                                          $max    = round($value['s_max'], 2); 
                                          $weight = round($value['s_weight'], 2); 
                                          
                                          $webqual    = $max/$weight;
                                          $v_webqual  = round($webqual, 4);
                                          $t_webqual += $webqual;

                                          $total   = $t_webqual/$count;
                                          $v_total = round($total, 4);
                                      ?>

                                    <td class="text-center"><?= $max; ?></td>
                                    <td class="text-center"><?= $weight; ?></td>
                                    <td class="text-center"><?= $v_webqual; ?></td>
                                    <td class="text-center">
                                      <?php 
                                        if($v_webqual >= 0.80){
                                          $text = "Sangat Baik";
                                          $div  = "badge-success";
                                        }elseif($v_webqual >= 0.60){
                                          $text = "Baik";
                                          $div  = "badge-info";
                                        }elseif ($v_webqual >= 0.40) {
                                          $text = "Cukup Baik";
                                          $div  = "badge-secondary";
                                        }elseif($v_webqual >= 0.20){
                                          $text = "Kurang Baik";
                                          $div  = "badge-warning";
                                        }else{
                                          $text = "Sangat Kurang Baik";
                                          $div  = "badge-danger";
                                        }
                                      ?>
                                      <div class="badge <?= $div; ?>" style="font-weight: 500;" ><?= $text; ?></div>
                                    </td>
                                    <?php } //end foreach sum_perhitungan ?>
                                  </tr>

                                <?php } //end foreach kategori ?>

                                  <tr style="color: black;">
                                    <td colspan="4"><span class="float-right">Total</span></td>
                                    <td class="text-center"><?= $v_total; ?></td>
                                    <td class="text-center">
                                      <?php 
                                        if($v_total >= 0.80){
                                          $text = "Sangat Baik";
                                          $div  = "badge-success";
                                        }elseif($v_total >= 0.60){
                                          $text = "Baik";
                                          $div  = "badge-info";
                                        }elseif ($v_total >= 0.40) {
                                          $text = "Cukup Baik";
                                          $div  = "badge-secondary";
                                        }elseif($v_total >= 0.20){
                                          $text = "Kurang Baik";
                                          $div  = "badge-warning";
                                        }else{
                                          $text = "Sangat Kurang Baik";
                                          $div  = "badge-danger";
                                        }
                                      ?>
                                      <div class="badge <?= $div; ?>" style="font-weight: 500;" ><?= $text; ?></div>
                                    </td>
                                  </tr>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>

                      </div>
                    <!-- </div> -->
                  <!-- </div> -->

                <?php }else{ ?>
                  <div class="alert alert-secondary text-center" role="alert">
                    Tidak ada data survey
                  </div>
                <?php } ?>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Jumbotron -->

   <!--  <script>
      window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove(); 
        });
      }, 3000);
    </script> -->

<?php include('../layout/footer.php'); ?>
