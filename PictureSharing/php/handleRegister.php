<!DOCTYPE HTML>
<html>
	<head>
		<title>Picture Sharing</title>
		<link rel="shortcut icon" type="image/x-icon" href="../icons/favicon.ico">
	</head>
</html>

<?php
	session_start();
	if ((!empty($_POST["regUsername"])) && (!empty($_POST["regPassword"]))){
		if ($_SESSION["loggedin"] == 1){
			$username = $_SESSION["logUsername"];
			echo "
				<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
				<script>
					function info(){
						alert('You are already logged in, $username! Please logout before registering.');
						window.location.href = '../../PictureSharing.html';
					}
					function print(){
						setTimeout(info, 200);
					}
				</script>
			";
		}else{
			$_SESSION["regUsername"] = $_POST["regUsername"];
			$_SESSION["regPassword"] = $_POST["regPassword"];
			$username = $_SESSION["regUsername"];
			$password = $_SESSION["regPassword"];
			if ((!empty($_SESSION["regUsername"])) && (!empty($_SESSION["regPassword"]))){
				$addAccount = true;
				if (file_exists("../config/accounts.txt")) {
					$accountsFile = fopen("../config/accounts.txt", "r");
					while ($line = fgets($accountsFile)) {
						$account = explode(", ", $line);
						$user = $account[0];
						if ($_SESSION["regUsername"] == $user){
							echo "
								<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
								<script>
									function info(){
										alert('This username is already taken. Please choose another one!');
										window.location.href = '../../PictureSharing.html';
									}
									function print(){
										setTimeout(info, 200);
									}
								</script>
							";
							$addAccount = false;
							break;
						}
					}
					fclose($accountsFile);
					if ($addAccount == true){
						$newline = "\r\n";
						$info = htmlspecialchars($newline) . $_SESSION["regUsername"] . ", " . $_SESSION["regPassword"];
						file_put_contents("../config/accounts.txt", $info, FILE_APPEND);
						echo "
							<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
							<script>
								function info(){
									alert('Username: $username, Password: $password. Remember them!');
									window.location.href = '../../PictureSharing.html';
								}
								function print(){
									setTimeout(info, 200);
								}
							</script>
						";
					}
				}else{
					$info = $_SESSION["regUsername"] . ", " . $_SESSION["regPassword"];
					file_put_contents("../config/accounts.txt", $info, FILE_APPEND);
					echo "
						<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
						<script>
							function info(){
								alert('Username: $username, Password: $password. Remember them!');
								window.location.href = '../../PictureSharing.html';
							}
							function print(){
								setTimeout(info, 200);
							}
						</script>
					";
				}
			}
		}
	}else{
		echo "
			<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
			<script>
				function info(){
					alert('Please input both fields!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
?>