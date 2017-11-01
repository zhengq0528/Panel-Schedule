<center ><h2> Please Enter The Job Number To Delete</h2>
<form method='post'>
  <input type='text' name = 'jn'>
  <br>
  <h2> Please Enter "CONFIRM" </h2>
  <input type='text' name = 'cm'>
  <br>
  <br>
  <input type='submit' name = 'sb'>
</form>

<?php
include('PHP/Database/db.php');
if(isset($_POST['sb']))
{
  if(strcmp($_POST['cm'],"CONFIRM")==0)
  {
    $jn = $_POST['jn'];
    //echo "job you want to delete is $jn";
    $sql = "DELETE FROM ps.panel WHERE jn = '$jn'";
    $conn->query($sql);
    $sql = "DELETE FROM ps.project WHERE jn = '$jn'";
    $conn->query($sql);
    $sql = "DELETE FROM ps.circuit WHERE jn = '$jn'";
    $conn->query($sql);
    $sql = "DELETE FROM ps.keys WHERE jn = '$jn'";
    $conn->query($sql);
    $sql = "DELETE FROM ps.transformer WHERE jn = '$jn'";
    $conn->query($sql);
    echo "<h1 style='color:green;'> You have successfully deleted project :$jn <h1>";
  }
  else {
    echo "<h1> Please Enter 'CONFIRM' to confirm deletion</h1>";
  }
}
 ?>
</center>
