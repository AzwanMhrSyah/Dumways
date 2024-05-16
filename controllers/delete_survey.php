<?php
	session_start();
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

    $id = $_GET['id'];

    $check_pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung WHERE id = '$id'");

    if ($check_pengunjung->num_rows > 0) {

    	$delete_pengunjung = "DELETE FROM pengunjung WHERE id = '$id'";

    	if (mysqli_query($koneksi, $delete_pengunjung)) {
    		
    		$check_survey = mysqli_query($koneksi, "SELECT * FROM survey WHERE pengunjung_id = '$id'");

	    	if ($check_survey->num_rows > 0) {
	    		$delete_survey = mysqli_query($koneksi, "DELETE FROM survey WHERE pengunjung_id = '$id'");
	    	}	
    	}

    	$pesan =  "Data berhasil dihapus !";
		header('location:../views/admin/hasil_survey.php?pesan='.$pesan);

    }else{
    	$pesan =  "Data tidak ditemukan !";
		header('location:../views/admin/hasil_survey.php?pesanError='.$pesan);
    }


?>