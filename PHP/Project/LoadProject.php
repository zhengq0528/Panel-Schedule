<?php

if(isset($_POST['upd']))
{
  $jn = $_POST['jn'];
  $des = $_POST['jd'];
  $des = addslashes($des);
  $type = $_POST['type'];
  $sql = "SELECT * from ps.project where jn = '$jn'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();

  $sql = "UPDATE ps.project set jobdesc = '$des', type = '$type' WHERE jn = '$jn'";
  if ($conn->query($sql) === TRUE) {

  }
  else{
    echo "<input type='hidden' value='Update Failed<br> Empty Description' id='message'>";
  }
}
function getProjectType($p_type){
  switch ($p_type) {
    case 1:
    $p_types = "Dwelling units";
    break;
    case 2:
    $p_types = "Hospitals";
    break;
    case 3:
    $p_types = "Hotels and motels";
    break;
    case 4:
    $p_types = "Warehouses";
    break;
    case 5:
    $p_types = "All Others";
    break;
  }
  return $p_types;
}
$conn->query("UPDATE ps.project set date = '".date('Y-m-d H:i:s')."' WHERE jn = '$_GET[jn]'"); //Update date of accessing time

$result = $conn->query("SELECT * from ps.project where jn = '$_GET[jn]'");
$row = $result->fetch_assoc();
$exist = 0;
if(empty($row))
{
  echo "<h1 style'color:red'>JOB NUMBER $_GET[jn] DOES NOT EXIST!</h1>";
  echo "<form method='post' action = '../../index.php'>";
  echo " <input type = 'Submit' value = 'Go Back!''>";
  echo "</form>";
  return 0;
}
?>


<div class ='infoheader'>
  <div class = 'hd1'>
    <h4s>Job Number: <?php echo "$_GET[jn] -  $row[jobdesc]";?> </h4s>
    <br>
    <h5s>Building Type: <?php echo getProjectType($row['type']);?></h5s>
  </div>

  <button id="edit_project" class = "header_button" type="button"
  style="position:fixed;left:1105px;top:5px;width:200px;">Edit Project Information</button>
  <button id="edit_project" class = "header_button" type="button" onclick="location.href='../../'"
  style="position:fixed;left:1315px;top:5px;">Close Project</button>

</div>
<div class ='infobutton'>
  <div class="box">
    <p style = 'font-size:25px; color:white;'>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Create Panel</p>
    <button id = "disbut"  class = "button" type="button">Distribution</button>
    <button id = "three"   class = "button"type="button">Three Phase</button>
    <button id = "single"  class = "button"type="button">Single Phase</button>
    <button id = "iso"     class = "button"type="button">Isolation</button>
    <button id = "ukey"     class = "button"type="button">Ulta Panels</button>
  </div>

  <div class="box2" style="left:520px;">
    <p style = 'font-size:25px; color:white;'>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Panel Tools</p>
    <button id = "upd" class = "button"type="button">Edit Info</button>
    <button id = "dep" class = "button"type="button">Delete</button>
    <form class = "button" action="" method="post">
      <input type='submit' value = 'Keys' class ="button" name="sk">
    </form>
    <form class = "button" action="" method="post">
      <input type='submit' value = 'Transformer' class ="button" name="tf">
    </form>
    <button id = "cir" class = "button" type="button">Edit Circuit</button>
    <button id = "pt" class = "button" type="button" onclick="prints()">Print</button>
    <FORM class = "button" >
      <button class = "button" type="button" id='loadp'>Calculate</button>
    </FORM>

  </div>
  <div class="box3" style="left:1245px;">
    <p style = 'font-size:25px; color:white;'>&emsp;&emsp;&emsp; Connections</p>
    <button id="link"class = "button"type="button">Panel</button>
    <button id="trans"class = "button"type="button">Transformer</button>
    <button id ="dic"class = "button"type="button">Disconnect</button>
  </div>
</div>
