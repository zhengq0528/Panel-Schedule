<?php
include("../Database/db.php");
if($_POST['id'])
{
  //echo $_POST['id']."is here";
  $id=$_POST['id'];
  $type;
  $key;
  foreach ($_POST as $key => $value){
    $type = $key;
    $key = $value;
    echo "{$key} = {$value}<br>";
  }
  $key = addslashes($key);
  $sql = "UPDATE ps.keys SET $type = '$key' WHERE id = '$id' ";
  if(mysqli_query($conn,$sql)){
    echo " <br>Records were updated successfully.";
  }
  else {
    echo " <br>ERROR: Could not able to execute $sql. " . mysqli_error($conn);
  }
}
?>
