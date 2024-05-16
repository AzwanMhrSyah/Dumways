<?php
	session_start();
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

	$pertanyaan = $_POST['pertanyaan']; 
	$kategori_id = $_POST['kategori_id']; 
	$date 		= date('Y-m-d H:i:s');

	if (empty($pertanyaan)) {
		$pesan =  "Form pertanyaan harus diisi !";
		header('location:../views/admin/data_pertanyaan.php?pesanError='.$pesan);
		exit();
	}

	$query_add = "INSERT INTO pertanyaan (pertanyaan, kategori_id, created_at, updated_at) VALUES ('$pertanyaan', '$kategori_id', '$date', '$date')";

	if (mysqli_query($koneksi, $query_add)) {
		$pesan =  "Data baru berhasil ditambahlkan !";
		header('location:../views/admin/data_pertanyaan.php?pesan='.$pesan);
	}else{
		$pesan =  "Data pertanyaan gagal ditambahlan !";
		header('location:../views/admin/data_pertanyaan.php?pesanError='.$pesan);
	}
?>