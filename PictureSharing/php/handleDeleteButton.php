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
		if (!empty($_SESSION["logUsername"])){
			$linksFile = fopen("../config/accountImageLinks.txt", "r");
			$deleteLinksFile = false;
			$errormsgdisplayed = false;
			while ($line = fgets($linksFile)) {
				$data = explode(", ", $line);
				$user = $data[0];
				$file = preg_replace("/\s+/", "", $data[1]);
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
					$imagetag = $doc->getElementById("img" . $i);
					if ($imagetag){
						$fullimgpath = $imagetag->getAttribute("src");
						$extinfo = new SplFileInfo($fullimgpath);
						$ext = $extinfo->getExtension();
						$file_path = "../uploads/img" . $i . "." . $ext;
						if (isset($_POST["deleteIMG" . $i])){
							$imagename = "img" . $i;
							if (($_SESSION["logUsername"] == $user) && ($imagename == $file)){
								$parentdiv = $doc->getElementById("uploadedPictures");
								$divtoremove = $doc->getElementById("divIMG" . $i);
								if ($divtoremove){
									$parentdiv->removeChild($divtoremove);
									if (file_exists($file_path)) {
										unlink($file_path);
									}
									$doc->saveHTMLFile("../../PictureSharing.html");
									$fileIterator = new FilesystemIterator("../uploads", FilesystemIterator::SKIP_DOTS);
									$numberOfFiles = iterator_count($fileIterator);
									if ($numberOfFiles == 0){
										copy("../css/default.css", "../css/styles.css");
										$deleteLinksFile = true;
										$countdoc->getElementsByTagName("imgid")->item(0)->nodeValue = 1;
										$countdoc->save("../config/imgidCount.xml");
									}
									header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
									header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
									header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
									header("Cache-Control: post-check=0, pre-check=0", false);
									header("Pragma: no-cache");
									header("Location: ../../PictureSharing.html");
									break;
								}
							}else{
								if ($errormsgdisplayed == false){
									echo "
										<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
										<script>
											function info(){
												alert('Only the image uploader can delete it!');
												window.location.href = '../../PictureSharing.html';
											}
											function print(){
												setTimeout(info, 200);
											}
										</script>
									";
									$errormsgdisplayed = true;
								}
							}
						}
					}
				}
			}
			fclose($linksFile);
			if ($deleteLinksFile == true){
				if (file_exists("../config/accountImageLinks.txt")) {
					unlink("../config/accountImageLinks.txt");
				}
			}
		}
	}else{
		echo "
			<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
			<script>
				function info(){
					alert('You need to login in order to delete images!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
?>