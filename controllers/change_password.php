<?php
	session_start();
	
	include('../config.php');

    date_default_timezone_set('Asia/Jakarta');

	$email 			   = $_SESSION['email']; 
	$old_password  	   = $_POST['old_password'];
	$new_password      = $_POST['new_password'];
	$confirm_password  = $_POST['confirm_password'];
	$date     		   = date('Y-m-d H:i:s');

	$user  = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");

	while ($row = mysqli_fetch_array($user)) {
	  $id_user  = $row['id'];
	  $password = $row['password'];
	}

	$hash_old_password = md5($old_password);
	$hash_new_password = md5($new_password);
	$hash_confirm_password = md5($confirm_password);

		if (empty($old_password)) {
			$pesan = "Form password lama harus diisi !";
			header('location:../views/admin/change_password.php?pesanError='.$pesan);
		    exit();
		}

		if (empty($new_password)) {
			$pesan = "Form password baru harus diisi !";
			header('location:../views/admin/change_password.php?pesanError='.$pesan);
		    exit();
		}

		if (empty($confirm_password)) {
			$pesan = "Form konfirmasi password harus diisi !";
			header('location:../views/admin/change_password.php?pesanError='.$pesan);
		    exit();
		} 


		if ($password == $hash_old_password) {
			if ($hash_old_password != $hash_new_password) {
				if ($hash_new_password == $hash_confirm_password) {
					
					$query_update = "UPDATE users SET password = '$hash_new_password', updated_at = '$date' WHERE id = '$id_user'";

					if (mysqli_query($koneksi, $query_update)) {
						session_destroy();
						$pesan = "Password berhasil diperbaharui, silahkan login kembali !";
						header('location:../views/login.php?pesan='.$pesan);	
					}else{
						$pesan = "Password gagal diperbaharui !";
						header('location:../views/admin/change_password.php?pesanError='.$pesan);	
					}

				}else{
					$pesan = "Password baru dan konfirmasi password tidak sama !";
					header('location:../views/admin/change_password.php?pesanError='.$pesan);
		    		exit();
				}
			}else{
				$pesan = "Password baru tidak boleh sama dengan password lama !";
				header('location:../views/admin/change_password.php?pesanError='.$pesan);
		    	exit();
			}
		}else{
			$pesan = "Password lama anda salah !";
			header('location:../views/admin/change_password.php?pesanError='.$pesan);
		}

?>