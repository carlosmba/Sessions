<?php
session_start();

if(isset($_SESSION['user'])){
	header('Location: index.php');
}
	$error = '';
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$user = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
		$pass = $_POST['password'];
		$pass = hash( 'sha512',$pass);


		if(empty($user) or empty($pass)){
			$error .= '<li>Por favor llene todos los campos</li>';
		}else{
			try{
				$connect = new PDO('mysql:host=localhost;dbname=elpepe', 'root', '');
			}catch(PDOException $e){
				echo 'Error: '. $e->getmessage();
				die();
			}

			$stm = $connect->prepare('SELECT * FROM users WHERE user=:user AND pass=:pass');

			$stm->execute(array(':user' => $user, ':pass' => $pass));

			$result = $stm->fetch();

			if($result == false){
				$error .=  '<li>Datos Incorrectos</li>';
			}else{
				$_SESSION['user'] = $user;
				header('Location: index.php');
			}

		}
	}

	

	require 'views/login.view.php';
?>