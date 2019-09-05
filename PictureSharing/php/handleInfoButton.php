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
		$countfile = file_get_contents("../config/imgidCount.xml");
		libxml_use_internal_errors(true);
		$filedoc = new DOMDocument();
		$filedoc->loadXML($countfile);
		$count = $filedoc->getElementsByTagName("imgid")->item(0)->nodeValue;
		for ($i = 1; $i < $count; $i++){
			$htmlfile = file_get_contents("../../PictureSharing.html");
			$doc = new DOMDocument();
			$doc->loadHTML($htmlfile);
			$picid = $doc->getElementById("img" . $i);
			if (isset($_POST["infoIMG" . $i])){
				if ($picid){
					$fullimagepath = $picid->getAttribute("src");
					$imgextinfo = new SplFileInfo($fullimagepath);
					$imageext = $imgextinfo->getExtension();
					$file_path = "../uploads/img" . $i . "." . $imageext;
					if(file_exists($file_path)) {
						$info = getimagesize($file_path);
						$size = formatBytes(filesize($file_path));
						$name = "img" . $i . "." . $imageext;
						$width = $info[0];
						$height = $info[1];
						$type = $info["mime"];
						echo "
							<html>
								<head>
									<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
									<meta charset='utf-8'>
									<meta http-equiv='X-UA-Compatible' content='IE=edge'>
									<meta name='viewport' content='width = device-width, initial-scale = 1'>
									<link rel='stylesheet' type='text/css' href='../css/styles.css'>
									<script src='../javascript/clock.js'></script>
								</head>
								<body onload=showTime()>
									<div id='digitalClock'></div>
									<form name='register' id='RegisterForm' action='handleRegister.php' method='post'>
										Username <input type='text' name='regUsername'>
										Password <input type='password' name='regPassword'>
										<input type='submit' value='Register'>
									</form>
									<form name='login' id='LoginForm' action='handleLogin.php' method='post'>
										Username <input type='text' name='logUsername'>
										Password <input type='password' name='logPassword'>
										<input type='submit' value='Login'>
									</form>
									<br>
									<form name='logout' id='LogoutForm' action='handleLogout.php' method='post'>
										<input type='submit' value='Logout'>
									</form>
									<br>
									<p id='shoutboxLabel'>Shoutbox</p>
									<div class='shoutboxClass'>
										<script src='https://www.shoutbox.com/chat/chat.js.php'></script>
										<script> var chat = new Chat(12226); </script>
									</div>
									<br>
									<div id='picPreviewDiv'>
										<img src=$file_path id='picPreviewImage'>
										<p>Name: $name</p>
										<p>Width: $width</p>
										<p>Height: $height</p>
										<p>Type: $type</p>
										<p>Size: $size</p>
										<form action='../../PictureSharing.html' method='post'>
											<input type='hidden' name='goBackToIndexPage'>
											<input type='submit' id='goBackButton' value='Go Back'>
										</form>
										<br>
										<br>
									</div>
								</body>
							</html>
							<style>
								#picPreviewImage {
									width: 35%;
								}
								#picPreviewDiv {
									text-align: center;
								}
								@media (min-width: 800px) {
									#picPreviewImage {
										width: 25%;
									}
								}
							</style>
						";
						break;
					}
				}
			}
		}
	}else{
		echo "
			<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
			<script>
				function info(){
					alert('Please login in order to see file info!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
	
	function formatBytes($bytes, $precision = 2) {
		$units = array("B", "KB", "MB", "GB", "TB");
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $precision) . " " . $units[$pow];
	}
?>