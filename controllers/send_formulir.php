<?php 
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

    $nama 	= $_POST['nama'];
    $email  = $_POST['email'];
    $umur   = $_POST['umur'];
    $jk     = $_POST['jk'];
    $pekerjaan 	= $_POST['pekerjaan'];
    $date   = date('Y-m-d H:i:s');

    $check_email = mysqli_query($koneksi, "SELECT * FROM pengunjung WHERE email = '$email'");

    if ($check_email->num_rows < 1) {
        $add_responden = mysqli_query($koneksi, "INSERT INTO pengunjung (nama, email, umur, jk, pekerjaan, tanggal_survey, created_at, updated_at) VALUES ('$nama', '$email', '$umur', '$jk', '$pekerjaan', '$date', '$date', '$date')");

        $id_pengunjung = mysqli_insert_id($koneksi); //mengambil id pengunjung yg baru diinput

    }else{
        while ($row = mysqli_fetch_array($check_email)) {
           $id_pengunjung = $row['id'];
        }
    }

    $check_survey = mysqli_query($koneksi, "SELECT * FROM survey WHERE pengunjung_id = '$id_pengunjung'");

    if ($check_survey->num_rows > 0) {   
        $pesan = "Anda telah mengisi survey !";
        header('location:../views/formulir_kuisioner.php?pesanError='.$pesan);
    }else{

        foreach ($_POST['check'] as $id => $jawaban_id) {
            // var_dump($jawaban_id);
            $add_survey = mysqli_query($koneksi, "INSERT INTO survey (pengunjung_id, jawaban_id, created_at, updated_at) VALUES ('$id_pengunjung', '$jawaban_id', '$date', '$date')");

        }     

        $pesan = "Terima kasih, jawaban anda telah terkirim !";
        header('location:../views/formulir_kuisioner.php?pesan='.$pesan);
    }


?>