<?php
include('../Database/db.php');
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
  echo "type = $type ; key = $key <br>";
  $key = addslashes($key);
  if(empty($key))
    $key = 0;
  $sql = "UPDATE ps.circuit SET $type = '$key' WHERE id = '$id' ";
  echo "$id is here <br>";
  if(mysqli_query($conn,$sql)){
    echo " <br>Records were updated successfully.";
  }
  else {
    echo " <br>ERROR: Could not able to execute $sql. " . mysqli_error($conn);
  }
}
?>
