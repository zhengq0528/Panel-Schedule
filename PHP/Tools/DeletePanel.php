<?php
//Deleting the selected panel
if(isset($_POST['deletePanel'])){

  $pname = $_POST['deletePanel'];

  $sql = "DELETE FROM ps.panel
  WHERE jn = '$_GET[jn]' and panel = '".$pname."'";
  $conn->query($sql);

  $sql = "DELETE FROM ps.circuit WHERE jn = '$_GET[jn]' and panel = '".$pname."'";
  $conn->query($sql);

  $sql3 = "DELETE From ps.transformer
  WHERE jn='$_GET[jn]' and source = '".$pname."'";
  $conn->query($sql);

}

$editproject = new popwin("Select A Panel To Delete","Good","DPanel","CloseDPanel");
$editproject->draw_head();

$result = $conn->query("SELECT * FROM ps.panel WHERE jn = '$_GET[jn]'");
if (!$result) {
  echo 'Could not run query: ' . mysql_error();
  exit;
}
if ($result->num_rows > 0) {
  echo "<form method='post'>";
  while ($row = mysqli_fetch_array($result))
  {
    echo " <br><input class='button' type='submit' value= $row[panel] name = 'deletePanel'><br> ";
  }
  echo "</form>";
}
$editproject->draw_foot();

?>

<script>
var Delete_Panel = document.getElementById('DPanel');
var Close_Delete_Panel = document.getElementById('CloseDPanel');
$('#dep').click(function(){
  Delete_Panel.style.display = "block";
});
Close_Delete_Panel.onclick = function() {
  Delete_Panel.style.display = "none";
}
</script>
