<?php
	session_start();
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

    $id_jawaban = $_GET['id'];

    $check = mysqli_query($koneksi, "SELECT * FROM jawaban WHERE id = '$id_jawaban'");

    while ($row = mysqli_fetch_array($check)) {
        $id_pertanyaan  = $row['pertanyaan_id'];
    }

    if ($check->num_rows > 0) {
        
        $query_delete = "DELETE FROM jawaban WHERE id = '$id_jawaban'";

        if (mysqli_query($koneksi, $query_delete)) {
            $pesan =  "Data berhasil dihapus !";
            header('location:../views/admin/jawaban.php?id='.$id_pertanyaan.'&pesan='.$pesan);
        }else{
            $pesan =  "Data jawaban gagal dihapus !";
            header('location:../views/admin/jawaban.php?id='.$id_pertanyaan.'&pesanError='.$pesan);
        }

    }else{
        $pesan =  "Data tidak ditemukan !";
        header('location:../views/admin/jawaban.php?pesanError='.$pesan);
    }

?>