<?php
session_start();

if(isset($_SESSION['user'])){
	header('Location: index.php');
}
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$user = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
		$pass = $_POST['password'];
		$pass2 = $_POST['password2'];

		$error = '';
		if(empty($user) or empty($pass) or empty($pass2)){
			$error .= '<li>Por favor llene todos los campos</li>';
		}else{
			try{
				$connect = new PDO('mysql:host=localhost;dbname=elpepe', 'root', '');

			}catch(PDOException $e){
				echo 'Error: '. $e->getmessage();
				die();
			}
			$stm = $connect->prepare('SELECT * FROM users WHERE user=:user LIMIT 1');

			$stm->execute(array(':user' => $user));

			$result = $stm->fetch();

			if($result != false){
				$error .= '<li>El usuario ya existe</li>';
			}

			$pass = hash('sha512', $pass);
			$pass2 = hash('sha512', $pass2);

			if($pass!=$pass2){
				$error .= '<li>Las contraseñas no son iguales </li>';
			}

			if(empty($error)){
				$stm = $connect->prepare('INSERT INTO users(id,user,pass) VALUES(null,:user,:pass)');
				$stm->execute(array(':user' => $user, ':pass' => $pass));

				header('Location: login.php');
			}





		}





	}

	


	require 'views/register.view.php';
?>