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
		$uploaddir = "../uploads/";
		$extinfo = new SplFileInfo(basename($_FILES["userfile"]["name"]));
		$fileext = $extinfo->getExtension();
		$uploadfile = "img" . $count . "." . $fileext;
		$isuploaded = move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploaddir . $uploadfile);
		if ($isuploaded){
			$htmlfile = file_get_contents("../../PictureSharing.html");
			libxml_use_internal_errors(true);
			$doc = new DOMDocument();
			$doc->loadHTML($htmlfile);
			$imginfo = "PictureSharing/uploads/" . $uploadfile;
			$divtoaddto = $doc->getElementById("uploadedPictures");
			$divtoadd = $doc->createElement("div");
			$divtoadd->setAttribute("class", "uploadedPic");
			$divtoadd->setAttribute("id", "divIMG" . $count);
			$divtoaddto->appendChild($divtoadd);
			$imgtoadd = $doc->createElement("img");
			$imgtoadd->setAttribute("src", $imginfo);
			$imgtoadd->setAttribute("id", "img" . $count);
			$divtoadd->appendChild($imgtoadd);
			$downloadformtoadd = $doc->createElement("form");
			$downloadformtoadd->setAttribute("class", "pictureButtons");
			$downloadformtoadd->setAttribute("action", "PictureSharing/php/handleDownloadButton.php");
			$downloadformtoadd->setAttribute("method", "post");
			$divtoadd->appendChild($downloadformtoadd);
			$downloadbuttonhidden = $doc->createElement("input");
			$downloadbuttonhidden->setAttribute("type", "hidden");
			$downloadbuttonhidden->setAttribute("name", "downloadIMG" . $count);
			$downloadformtoadd->appendChild($downloadbuttonhidden);
			$downloadbuttontoadd = $doc->createElement("input");
			$downloadbuttontoadd->setAttribute("type", "submit");
			$downloadbuttontoadd->setAttribute("id", "downloadButton" . $count);
			$downloadbuttontoadd->setAttribute("value", "Download");
			$downloadformtoadd->appendChild($downloadbuttontoadd);
			$infoformtoadd = $doc->createElement("form");
			$infoformtoadd->setAttribute("class", "pictureButtons");
			$infoformtoadd->setAttribute("action", "PictureSharing/php/handleInfoButton.php");
			$infoformtoadd->setAttribute("method", "post");
			$divtoadd->appendChild($infoformtoadd);
			$infobuttonhidden = $doc->createElement("input");
			$infobuttonhidden->setAttribute("type", "hidden");
			$infobuttonhidden->setAttribute("name", "infoIMG" . $count);
			$infoformtoadd->appendChild($infobuttonhidden);
			$infobuttontoadd = $doc->createElement("input");
			$infobuttontoadd->setAttribute("type", "submit");
			$infobuttontoadd->setAttribute("id", "infoButton" . $count);
			$infobuttontoadd->setAttribute("value", "Info");
			$infoformtoadd->appendChild($infobuttontoadd);
			$deleteformtoadd = $doc->createElement("form");
			$deleteformtoadd->setAttribute("class", "pictureButtons");
			$deleteformtoadd->setAttribute("action", "PictureSharing/php/handleDeleteButton.php");
			$deleteformtoadd->setAttribute("method", "post");
			$divtoadd->appendChild($deleteformtoadd);
			$deletebuttonhidden = $doc->createElement("input");
			$deletebuttonhidden->setAttribute("type", "hidden");
			$deletebuttonhidden->setAttribute("name", "deleteIMG" . $count);
			$deleteformtoadd->appendChild($deletebuttonhidden);
			$deletebuttontoadd = $doc->createElement("input");
			$deletebuttontoadd->setAttribute("type", "submit");
			$deletebuttontoadd->setAttribute("id", "deleteButton" . $count);
			$deletebuttontoadd->setAttribute("value", "Delete");
			$deleteformtoadd->appendChild($deletebuttontoadd);
			$doc->saveHTMLFile("../../PictureSharing.html");
			$newline = "\r\n";
			$csstext = htmlspecialchars($newline) . "#img" . $count . " { width: 90%; }" . htmlspecialchars($newline) . "#downloadButton" . $count . " { margin-right: 15px; }" . htmlspecialchars($newline) . "#infoButton" . $count . " { margin-right: 15px; }" . htmlspecialchars($newline) . "#deleteButton" . $count . " { margin-right: 1px; }" . htmlspecialchars($newline);
			file_put_contents("../css/styles.css", $csstext, FILE_APPEND);
			if (file_exists("../config/accountImageLinks.txt")){
				$info = htmlspecialchars($newline) . $_SESSION["logUsername"] . ", " . "img" . $count;
				file_put_contents("../config/accountImageLinks.txt", $info, FILE_APPEND);
			}else{
				$info = $_SESSION["logUsername"] . ", " . "img" . $count;
				file_put_contents("../config/accountImageLinks.txt", $info, FILE_APPEND);
			}
			$count++;
			$filedoc->getElementsByTagName("imgid")->item(0)->nodeValue = $count;
			$filedoc->save("../config/imgidCount.xml");
			header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Location: ../../PictureSharing.html");
		}else{
			echo "
				<iframe src = '../../PictureSharing.html' onload = 'print();' style = 'position:fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; border: none; margin: 0; padding: 0; overflow: hidden; z-index: 999999;'>Your browser doesn't support iframes.</iframe>
				<script>
					function info(){
						alert('Please select a file to upload first!');
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
					alert('Please login in order to upload files!');
					window.location.href = '../../PictureSharing.html';
				}
				function print(){
					setTimeout(info, 200);
				}
			</script>
		";
	}
?>