<?php
// The file has JSON type.
header('Content-Type: application/json');

// Prepare the file name from the query string.
// Don't use session_start here. Otherwise this file will be only executed after the process.php execution is done.
$file = str_replace(".", "", $_GET['file']);
$file = "tmp/" . $file . ".txt";

// Make sure the file is exist.
if (file_exists($file)) {
  // Get the content and echo it.
  $text = file_get_contents($file);
  echo $text;

  // Convert to JSON to read the status.
  $obj = json_decode($text);
  // If the process is finished, delete the file.
  
}
else {
  echo json_encode(array("percent" => null, "rows" => null, "total" => null, "success" => null, "redirect" => null, "warning" => null, "danger" => null, "message" => null));
}
