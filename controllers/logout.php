<?php 
	session_start();
	session_destroy();
 	
 	$pesan = "Anda berhasil logout !";
	header('location:../views/home_page.php?pesan='.$pesan);
?>