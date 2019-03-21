<?php

include("db-connection.php");
/* vars for export */
// database record to be exported

if (isset($_GET['image_id'])) {
	$img_id = intval($_GET['image_id']); // $test = isset($_GET['test']) ? $_GET['test'] : null;

	$db_table = 'images';

	// Delete image from the dataset
	$sql_query = "DELETE FROM images WHERE id = $img_id";
	$sql_ret = $mysqli->query($sql_query) or die($mysqli->error);

	if ($sql_ret) {
		rename("images/".$img_id.".jpg","deleted-images/".$img_id.".jpg");
	}

	header("Location: index.php", true, 301);
	exit();
}

?>

