<?php
    session_start();
    if($_SESSION['status'] != "login"){
    header("location:../login.php");
    }

    include('../../config.php');

    error_reporting(0);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Kuisioner e-Court</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        .bgcolor{
            background-color: white !important;
        }

        .right{
            text-align: right;
        }
        .line-6 {
          box-shadow: inset 0 0 0 10000px #000;
          height: 1px;
          background: black;
        }
    </style>

  </head>

<body class="bgcolor" onload="window.print()">
<!-- <body> -->
<!-- [ Main Content ] start -->
<div class="row">
    <!-- [ horizontal-layout ] start -->
    <div class="col-sm-8" style="padding-left: 30px; padding-top: 30px;">
        <!-- <div class="card"> -->
            <div style="text-align:center; padding-top: 10px;">
                <span style="font-size: 23px; font-weight: bold; font-family: times new roman ">SISTEM KUISIONER E-COURT</span><br>
                <span style="font-size: 18px; font-family: times new roman">KOTA PARIAMAN</span><br>
                <!-- <span style="font-size: 20px; font-family: times new roman">KELURAHAN BALAI GADANG</span> -->
                <!-- <hr style="border: 1px solid black;"> -->
                <div class="line-6 mb-4"></div>
            </div>
            <!-- <div class="card-body"> -->
                <center>
                    <span style="font-size: 16px; font-weight: bold;">Laporan Hasil Survey e-Court</span><br/>
                </center><br>

               <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 12px;">
                  <thead>
                    <tr class="text-center">
                      <th width="5%" style="vertical-align:middle;">#</th>
                      <th width="15%" style="vertical-align:middle;">Dimensi</th>
                      <th>Maximum Score</th>
                      <th>Weight Score</th>
                      <th>Webqual Index</th>
                      <th style="vertical-align:middle;">Interprestasi</th>
                      <th style="vertical-align:middle;">Rekomendasi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                      $kategori = mysqli_query($koneksi, "SELECT * FROM kategori"); 

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
                          <div><?= $text; ?></div>
                        </td>
                        <?php } //end foreach sum_perhitungan ?>
                        <td style="text-align: justify; text-justify: inter-word;">
                          <?php 
                            $x = 1;
                            $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

                            foreach($pertanyaan as $row){
                              $id_pertanyaan = $row['id'];

                              $survey = mysqli_query($koneksi, "SELECT SUM(skor) as sum, survey.*, jawaban.skor, jawaban.pertanyaan_id FROM survey LEFT JOIN jawaban ON survey.jawaban_id = jawaban.id WHERE jawaban.pertanyaan_id = '$id_pertanyaan'");

                              foreach ($survey as $key => $su) {
                                $tArray[$su['pertanyaan_id']] = $su['sum'];
                              }
                              
                              $min     = min($tArray);
                              $id_pert = array_keys($tArray, $min); //get array keys (id pertanyaan)

                            }

                            // echo $i++.'. '.$min.'<br/>';

                            //looping untuk mndapatkan id pertanyaan
                            foreach($id_pert as $p_id){
                              $get_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE id = '$p_id'");

                              foreach ($get_pertanyaan as $result) {
                                // echo $i++.'. '.$result['pertanyaan'].'<br/>';
                          ?>

                            <table>
                              <tr>
                                <td style="vertical-align: top;"><?= $x++; ?>. </td>
                                <td><?= $result['pertanyaan']; ?><td>
                              <tr>
                            </table>

                          <?php
                              }
                            }
                          ?>
                        </td>
                      </tr>

                    <?php } //end foreach kategori ?>

                      <tr style="color: black;">
                        <td colspan="4"><span style="float: right;">Total</span></td>
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
                          <div><?= $text; ?></div>
                        </td>
                        <td></td>
                      </tr>
                    
                  </tbody>
                </table>

                <div class="col-sm-12">
                  <p style="font-size: 12px; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Berdasarkan tabel diatas dapat disimpulkan bawah perhitungan manual dengan menggunakan metode webqual dari tiga variabel, kualitas layanan website e-court menyatakan bahwa kualitas layanan website e-court secara keseluruhan termasuk dalam kategori 
                  <font style="text-transform: uppercase;"><?= $text; ?>.</font> 
                  Dapat dilihat dari hasil webqual index dari tiga variabel didapat

                  <?php
                    foreach ($kategori as $kat) {
                        $id_kategori = $kat['id'];

                          $k_perhitungan = mysqli_query($koneksi, "SELECT SUM(m_score) as k_max, SUM(w_score) as k_weight, perhitungan.*, pertanyaan.kategori_id 
                              FROM perhitungan 
                              INNER JOIN pertanyaan ON perhitungan.pertanyaan_id = pertanyaan.id
                              WHERE pertanyaan.kategori_id = '$id_kategori'");

                          foreach ($k_perhitungan as $p) {
                              $p_max    = round($p['k_max'], 2); 
                              $p_weight = round($p['k_weight'], 2);

                              $p_webqual      = $p_max/$p_weight;
                              $webqual_index  = round($p_webqual, 4);

                              echo $kat['kategori'].' = '.$webqual_index.', ';
                          }

                    }
                  ?>
                   
                   dengan total <?= $v_total; ?> dengan hasil interprestasi dengan nilai <font style="text-transform: uppercase;"><?= $text; ?>.</font></p>

                </div>

                <?php
                    function tanggal_indo($tgl){
                     $bulan = array (
                      1 =>   'Januari',
                      'Februari',
                      'Maret',
                      'April',
                      'Mei',
                      'Juni',
                      'Juli',
                      'Agustus',
                      'September',
                      'Oktober',
                      'November',
                      'Desember'
                     );
                     $pecah = explode('-', $tgl);
                    return $pecah[2] . ' ' . $bulan[ (int)$pecah[1] ] . ' ' . $pecah[0]; 
                    // variabel pecah 2 = tahun || variabel pecah 1 = bulan || variabel pecah 0 = tanggal

                    }
                ?>
                
                <div style="float: right; text-align: center; font-family: times new roman; font-size: 13px;">
                    <span>
                        Padang, <?php echo tanggal_indo(date('Y-m-d'));  ?>
                    </span><br>
                    <span>Pimpinan,</span><br/><br/>
                    <br>
                    </br>
                    <span style="font-weight: bold;">
                        Firman Arif S.HI,MH
                    </span><br>
                </div>

            <!-- </div> -->
        <!-- </div> -->
    </div>
    <!-- [ horizontal-layout ] end -->
</div>
<!-- [ Main Content ] end -->

<!-- Js -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>
</html>

