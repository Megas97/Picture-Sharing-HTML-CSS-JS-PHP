<!DOCTYPE HTML>
<html>
	<head>
		<title>Picture Sharing</title>
		<link rel="shortcut icon" type="image/x-icon" href="../icons/favicon.ico">
	</head>
</html>

<?php
	session_start();
	if ($_SESSION["loggedin"] == 1){
		$username = $_SESSION["logUsername"];
		echo "
			<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
			<script>
				function info(){
					alert('You successfully logged out, $username!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
		$_SESSION["loggedin"] = 0;
	}else{
		echo "
			<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
			<script>
				function info(){
					alert('You are not logged in!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
?>