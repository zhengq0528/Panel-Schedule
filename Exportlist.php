<?php
include('PHP/Database/db.php');
$sql = "SELECT panel FROM ps.panel WHERE jn = '$_GET[jn]'";
$result = $conn->query($sql);
if (!$result) {
  exit;
}
if ($result->num_rows > 0) {
  while ($row = mysqli_fetch_array($result)) {
echo "$row[panel]
";
  }
}
?>
