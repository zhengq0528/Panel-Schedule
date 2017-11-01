<?php
include("db.php");
if($_POST['id'])
{
  $id = $_POST['id'];
  $jn = $_POST['jn'];
  $pns = $_POST['ppns'];
  $host = $_POST['host'];
  
  $time = date("Y-m-d").date("h:i:sa");
  $sql = "UPDATE ps.tracklog SET times='$time', state ='1' where id ='$id'";
  if($conn->query($sql))
  {
    echo "good to go";
  }
}
?>
