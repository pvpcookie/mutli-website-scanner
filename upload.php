<?php

session_start();

if(is_array($_FILES)) {
	if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
		$sourcePath = $_FILES['userImage']['tmp_name'];
		$targetPath = "docs/".session_id().".txt";
		
		if(move_uploaded_file($sourcePath,$targetPath)) {
			echo $targetPath;
		}
	}
}
?>