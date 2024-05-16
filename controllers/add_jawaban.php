<?php
	session_start();
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

	$jawaban = $_POST['jawaban']; 
	$skor    = $_POST['skor'];
	$date 	 = date('Y-m-d H:i:s');
	$id_pertanyaan = $_GET['id'];

	if (empty($jawaban)) {
		$pesan =  "Form jawaban harus diisi !";
		header('location:../views/admin/jawaban.php?id='.$id_pertanyaan.'&pesanError='.$pesan);
		exit();
	}

	$query_add = "INSERT INTO jawaban (jawaban, skor, pertanyaan_id, created_at, updated_at) VALUES ('$jawaban', '$skor', '$id_pertanyaan', '$date', '$date')";

	if (mysqli_query($koneksi, $query_add)) {
		$pesan =  "Data baru berhasil ditambahlkan !";
		header('location:../views/admin/jawaban.php?id='.$id_pertanyaan.'&pesan='.$pesan);
	}else{
		$pesan =  "Data pertanyaan gagal ditambahlan !";
		header('location:../views/admin/jawaban.php?id='.$id_pertanyaan.'&pesan='.$pesan);
	}

?>