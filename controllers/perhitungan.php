<?php   

  include('../../config.php');

  $kategori = mysqli_query($koneksi, "SELECT * FROM kategori"); 

  foreach ($kategori as $kat) { //kategori
    $id_kategori = $kat['id'];

    $pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE kategori_id = '$id_kategori'");

    foreach($pertanyaan as $key => $per){ //pertanyaan
      $id_pertanyaan = $per['id'];

      //jawaban where id_pertanyaan
      $survey = mysqli_query($koneksi, "SELECT survey.*, jawaban.skor, jawaban.pertanyaan_id FROM survey LEFT JOIN jawaban ON survey.jawaban_id = jawaban.id WHERE jawaban.pertanyaan_id = '$id_pertanyaan'");

      $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung");

      //sum skor
      $r_kep = 0;
      foreach ($survey as $s => $sur) {
        $r_kep += $sur['skor'];
      }

      //penjumlahan rata2 kepentingan dan rata2 persetujuan
      $r_kep_sum = $r_kep/$pengunjung->num_rows;
      $kep_sum   = round($r_kep_sum, 2);                            

      //input into table
      $perhitungan = mysqli_query($koneksi, "SELECT * FROM perhitungan WHERE pertanyaan_id = '$id_pertanyaan'");

      if($perhitungan->num_rows < 1){
        $add_perhitungan = mysqli_query($koneksi, "INSERT INTO perhitungan (pertanyaan_id, r_kepentingan, r_persetujuan) VALUES ('$id_pertanyaan', '$kep_sum', '$kep_sum')");
      }else{
        $update_perhitungan = mysqli_query($koneksi, "UPDATE perhitungan SET r_kepentingan = '$kep_sum', r_persetujuan = '$kep_sum' WHERE pertanyaan_id = '$id_pertanyaan'");
      }

    }//endforeach pertanyaan

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
      $m_hasil = $r['r_kepentingan'] * $max;
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