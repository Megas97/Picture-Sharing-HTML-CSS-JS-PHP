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
			if (isset($_POST["downloadIMG" . $i])){
				if ($picid){
					$fullimagepath = $picid->getAttribute("src");
					$imgextinfo = new SplFileInfo($fullimagepath);
					$imageext = $imgextinfo->getExtension();
					$file_path = "../uploads/img" . $i . "." . $imageext;
					if (file_exists($file_path)) {
						$picname = "img" . $i . "." . $imageext;
						header("Content-disposition: attachment; filename = '$picname'");
						header("Content-type: application/octet-stream");
						ob_clean();
						readfile($file_path);
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
					alert('Please login in order to download files!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
?>