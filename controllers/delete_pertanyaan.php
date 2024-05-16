<?php
	session_start();
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

    $id_pertanyaan = $_GET['id'];

    $check_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE id = '$id_pertanyaan'");

    if ($check_pertanyaan->num_rows > 0) {

    	$check_jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE pertanyaan_id = '$id_pertanyaan'");

	    if ($check_jawaban->num_rows > 0) {

	    	while ($row = mysqli_fetch_array($check_jawaban)) {
		        $id_jawaban   = $row['id'];
		        $check_survey = mysqli_query($koneksi, "SELECT * FROM survey WHERE jawaban_id = '$id_jawaban'");

		        if ($check_survey->num_rows > 0) {
		        	while($survey_row = mysqli_fetch_array($check_survey)){
		        		$id_survey 	   = $survey_row['id'];
			        	$delete_survey = mysqli_query($koneksi, "DELETE FROM survey WHERE id = '$id_survey'");
			        }	
		        }

		        $delete_jawaban = mysqli_query($koneksi, "DELETE FROM jawaban WHERE id = '$id_jawaban'");

		    }  
	    }

	    $check_perhitungan = mysqli_query($koneksi, "SELECT * FROM perhitungan WHERE pertanyaan_id = '$id_pertanyaan'");

	    if($check_perhitungan->num_rows > 0){
	    	$delete_perhitungan = mysqli_query($koneksi, "DELETE FROM perhitungan WHERE pertanyaan_id = '$id_pertanyaan'");
	    }

	    $delete_pertanyaan = "DELETE FROM pertanyaan WHERE id = '$id_pertanyaan'";

	    if (mysqli_query($koneksi, $delete_pertanyaan)) {
	    	$pesan =  "Data berhasil dihapus !";
        	header('location:../views/admin/data_pertanyaan.php?pesan='.$pesan);
	    }else{
			$pesan =  "Data gagal dihapus !";
        	header('location:../views/admin/data_pertanyaan.php?pesanError='.$pesan);
	    }

    }else{
    	$pesan =  "Data tidak ditemukan !";
        header('location:../views/admin/data_pertanyaan.php?pesanError='.$pesan);
    }

    
?>