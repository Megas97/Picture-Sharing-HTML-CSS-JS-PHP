<!DOCTYPE HTML>
<html>
	<head>
		<title>Picture Sharing</title>
		<link rel="shortcut icon" type="image/x-icon" href="../icons/favicon.ico">
	</head>
</html>

<?php
	session_start();
	if ((!empty($_POST["logUsername"])) && (!empty($_POST["logPassword"]))){
		if ($_SESSION["loggedin"] == 1){
			$username = $_SESSION["logUsername"];
			echo "
				<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
				<script>
					function info(){
						alert('You are already logged in, $username!');
						window.location.href = '../../PictureSharing.html';
					}
					function print(){
						setTimeout(info, 200);
					}
				</script>
			";
		}else{
			$_SESSION["logUsername"] = $_POST["logUsername"];
			$_SESSION["logPassword"] = $_POST["logPassword"];
			$username = $_SESSION["logUsername"];
			$password = $_SESSION["logPassword"];
			if ((!empty($_SESSION["logUsername"])) && (!empty($_SESSION["logPassword"]))){
				if (file_exists("../config/accounts.txt")) {
					$wrongInfo = false;
					$accountsFile = fopen("../config/accounts.txt", "r");
					while ($line = fgets($accountsFile)) {
						$newline = "\r\n";
						$account = explode(", ", $line);
						$user = $account[0];
						$pass = preg_replace("/\s+/", "", $account[1]);
						if (($_SESSION["logUsername"] == $user) && ($_SESSION["logPassword"] == $pass)){
							echo "
								<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
								<script>
									function info(){
										alert('Welcome, $username :)');
										window.location.href = '../../PictureSharing.html';
									}
									function print(){
										setTimeout(info, 200);
									}
								</script>
							";
							$_SESSION["loggedin"] = 1;
							$wrongInfo = false;
							break;
						}else{
							$wrongInfo = true;
						}
					}
					fclose($accountsFile);
					if ($wrongInfo == true){
						echo "
							<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
							<script>
								function info(){
									alert('Wrong username & password combination!');
									window.location.href = '../../PictureSharing.html';
								}
								function print(){
									setTimeout(info, 200);
								}
							</script>
						";
					}
				}else{
					echo "
						<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
						<script>
							function info(){
								alert('The accounts database is empty!');
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