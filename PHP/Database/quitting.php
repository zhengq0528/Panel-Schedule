<?php
include("db.php");
if($_POST['id'])
{
  $id = $_POST['id'];
  $jn = $_POST['jn'];
  $pns = $_POST['ppns'];
  $host = $_POST['host'];
  /*
  $File = "Y://Project//Quan.txt";
  $f = @fopen($File, "r+");
  ftruncate($f, 0);
  fclose($f);
  $myfile = fopen($File, "w") or die("Unable to open file!");
  $txt = "id : $id
          jn : $jn
          panel : $pns
          host : $host
    Date: ".date("Y-m-d")."; Time is : ".date("h:i:sa");
  fwrite($myfile, $txt);
  fclose($myfile);*/
  $time = date("Y-m-d").date("h:i:sa");
  $sql = "UPDATE ps.tracklog SET times='$time', state ='1' where id ='$id'";
  if($conn->query($sql))
  {
    echo "good to go";
  }
}
?>
