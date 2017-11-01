<!--Constant setting(fixed position for this area) -->
<div style =
'position:fixed;
width:120px;
height:auto;
left:10px;
top:150px;
border: 1px solid #283747; '>
<center>
  <?php
  //List of Panels.  Get Panel name from database and create as buttom
  echo "<h4>Select Panel</p>";
  $sql = "SELECT * FROM ps.panel WHERE jn = '$_GET[jn]'";
  $result = $conn->query($sql);
  echo $conn->error;
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  //Display if there is more than 0 panel in the project

  if ($result->num_rows > 0) {

    while ($rowu = mysqli_fetch_array($result)) {
      echo "<form method='post'>";
      //Create data for panel schedule
      echo "<input type='hidden' value = '$rowu[panel]' name = 'panel'>";
      echo "<input type='submit' class = 'button' value= '$rowu[panel]' name = 'ShowTable'>";
      echo "<br>";
        echo "</form>";
    }

  }
  else echo "<p style='font-size: 16px;'>No Panels</p>";

  ?>
</center>
</div>
