<?php
	session_start();
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

    $id_pertanyaan = $_GET['id'];
    $pertanyaan    = $_POST['pertanyaan'];
    $kategori_id   = $_POST['kategori_id'];
	$date          = date('Y-m-d H:i:s');

    $check_data = mysqli_query($koneksi, "SELECT * FROM pertanyaan WHERE id = '$id_pertanyaan'");

    if ($check_data->num_rows > 0) {
    	$update_pertanyaan = "UPDATE pertanyaan SET pertanyaan = '$pertanyaan', kategori_id = '$kategori_id', updated_at = '$date' 
    						  WHERE id = '$id_pertanyaan'";

    	if (mysqli_query($koneksi, $update_pertanyaan)) {
    		$pesan =  "Data baru berhasil diupdate !";
			header('location:../views/admin/data_pertanyaan.php?pesan='.$pesan);
    	}else{
    		$pesan =  "Data gagal diupdate !";
        	header('location:../views/admin/data_pertanyaan.php?pesanError='.$pesan);
    	}
    }else{
    	$pesan =  "Data tidak ditemukan !";
        header('location:../views/admin/data_pertanyaan.php?pesanError='.$pesan);
    }
    

?>