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
		if ((!empty($_SESSION["logUsername"])) && (!empty($_SESSION["logPassword"])) && ($_SESSION["logUsername"] == "tester") && ($_SESSION["logPassword"] == "test456")){
			$htmlfile = file_get_contents("../../PictureSharing.html");
			libxml_use_internal_errors(true);
			$doc = new DOMDocument();
			$doc->loadHTML($htmlfile);
			$countfile = file_get_contents("../config/imgidCount.xml");
			libxml_use_internal_errors(true);
			$countdoc = new DOMDocument();
			$countdoc->loadXML($countfile);
			$count = $countdoc->getElementsByTagName("imgid")->item(0)->nodeValue;
			for ($i = 1; $i < $count; $i++){
				$parentdiv = $doc->getElementById("uploadedPictures");
				$divtoremove = $doc->getElementById("divIMG" . $i);
				if ($divtoremove){
					$parentdiv->removeChild($divtoremove);
					$imagetag = $doc->getElementById("img" . $i);
					$fullimgpath = $imagetag->getAttribute("src");
					$extinfo = new SplFileInfo($fullimgpath);
					$ext = $extinfo->getExtension();
					$file_path = "../uploads/img" . $i . "." . $ext;
					if (file_exists($file_path)) {
						unlink($file_path);
					}
					$doc->saveHTMLFile("../../PictureSharing.html");
				}
			}
			copy("../css/default.css", "../css/styles.css");
			$countdoc->getElementsByTagName("imgid")->item(0)->nodeValue = 1;
			$countdoc->save("../config/imgidCount.xml");
			if (file_exists("../config/accountImageLinks.txt")) {
				unlink("../config/accountImageLinks.txt");
			}
			echo "
				<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
				<script>
					function info(){
						alert('The website has been reset!');
						window.location.href = '../../PictureSharing.html';
					}
					function print(){
						setTimeout(info, 200);
					}
				</script>
			";
		}else{
			echo "
				<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
				<script>
					function info(){
						alert('Only MegasXLR can reset the website!');
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
					alert('You need to login in order to reset the website!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
?>