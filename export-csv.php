<?php

include("db-connection.php");
/* vars for export */
// database record to be exported
$db_record = 'images';

// filename for export
$csv_filename = 'db_export_'.date('Y-m-d').'.csv';

// create var to be filled with export data
$csv_export = '';

// query to get data from database
$result = $mysqli->query("SELECT * FROM ".$db_record) or die($mysqli->error);
$field = $mysqli->field_count;

// create line with field names
$finfo = $result->fetch_field();
$csv_export.= $finfo->name;
for($i = 1; $i < $field; $i++) {
	$finfo = $result->fetch_field();
  $csv_export.= ','.$finfo->name;
}

$csv_export.= '
';

while($row = $result->fetch_array(MYSQLI_NUM))
{
  // create line with field values
  $csv_export.= '"'.$row[0].'"';
  for($i = 1; $i < $field; $i++) {
    $csv_export.= ','.'"'.$row[$i].'"';
  }
  $csv_export.= '
';
}

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=".$csv_filename."");
echo($csv_export);
?>

