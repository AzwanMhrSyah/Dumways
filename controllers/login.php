<?php
	session_start();
	include('../config.php');

	$email    = $_POST['email'];
	$password = $_POST['password'];

	if (empty($email)) {
		$pesan =  "Form email harus diisi !";
		header('location:../views/login.php?pesanError='.$pesan);
	    exit();
	}else if (empty($password)) {
		$pesan =  "Form password harus diisi !";
		header('location:../views/login.php?pesanError='.$pesan);
	    exit();
	}

	$check_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");

	if ($check_email->num_rows > 0) {

		while ($row = mysqli_fetch_array($check_email)) {
			$r_email    = $row['email'];
			$r_password = $row['password'];
			$role_id	= $row['role_id'];
		}

		if ($r_password == md5($password)) {

			if ($role_id == 1) {
				$_SESSION['email']  = $r_email;
				$_SESSION['status'] = "login";
				header('location:../views/admin/dashboard.php');
			}else{
				$pesan =  "Akses ditolak !";
				header('location:../views/login.php?pesanError='.$pesan);
	    		exit();
			}

		}else{
			$pesan =  "Password anda salah !";
			header('location:../views/login.php?pesanError='.$pesan);
		    exit();
		}

	}else{
		$pesan =  "Email anda tidak terdaftar !";
		header('location:../views/login.php?pesanError='.$pesan);
	    exit();
	}
?>