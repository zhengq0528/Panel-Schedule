<?php
if(isset($_POST['sk']))
{
  $ds = array("p","P","l","L","R","r","E","e","X","x","S","s");
  $codes = array("p","P","t","T","l","L","R","r","M","m","E","e","X","x","S","s");
  $cCode = array("m","M","r","R","l","L");
  //Create a new key
  if(strcmp($_POST['sk'], "CREATE") == 0)
  {
    $_POST['key'] = strtoupper($_POST['key']);
    if(empty($_POST['derating'])) $_POST['derating'] = 100;
    $_POST['description'] = addslashes($_POST['description']);
    if(in_array($_POST['key'],$codes))
    {
      echo "<script>alert('Can Not Add Default Keys!')</script>";
    }
    else
    {
      $sql = "INSERT INTO ps.keys(jn,keyname,derating,description) VALUES ('$_GET[jn]','$_POST[key]','$_POST[derating]','$_POST[description]')";
      if(!$conn->query($sql)){
        echo "<script>alert('NEW KEY FAIL CREATE!')</script>";
      }
    }
  }
  //Delete a selected key
  if(strcmp($_POST['dsk'],'Delete')==0)
  {
    $sql = "DELETE FROM ps.keys WHERE keyname = '$_POST[sk]' and jn = '$_GET[jn]'";
    if(!in_array($_POST['sk'],$ds)){
      if (!$conn->query($sql))
      echo "<script>alert('$conn->error;')</script>";
    }
    else
    echo "<script>alert('This is a default key')</script>";
  }


  $extrakey = array();
  $total = array();
  $sql = "SELECT * FROM ps.panel WHERE jn = '$_GET[jn]'";
  $result = $conn->query($sql);
  if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
  }
  while ($row = mysqli_fetch_array($result)) {
    //echo "$row[panel] <br>";
    $sql2 = "SELECT * FROM ps.circuit WHERE jn = '$_GET[jn]' and panel = '$row[panel]'";
    $result2 = $conn->query($sql2);
    if (!$result2) {
      echo 'Could not run query: ' . mysql_error();
      exit;
    }
    while ($row = mysqli_fetch_array($result2)) {
      $cos = strtoupper($row['code']);
      if(!in_array($row['code'],$codes))
      {
        if(!array_key_exists($row['code'],$extrakey))
        {
          $extrakey[$cos] = 0;
          $sql = "INSERT INTO ps.keys(jn,keyname,derating) VALUES ('$_GET[jn]','$cos',100)";
          $conn->query($sql);
        }
      }
      $total[strtoupper($row['code'])] += $row['watts1'] + $row['watts2'] + $row['watts3'];
    }
  }

  echo "<table style='position:relative; top:160px;left:300px;border-style:solid;'
  border='1' class ='table-hover table-striped' width='50%'
  color='black' cellspacing='1' cellpadding='1' id = 'keytable'>";
  $col = 4;

  echo "<tr><th colspan='$col'><h2><center>Key Table<center></h2></th></tr>";
  echo "<tr><th width='25%'><center>Key</center></th><th width='25%'><center>Description</center></th>
  <th width='25%'><center>Derating</center></th><th width='25%'><center>Total Loads(watts)</center></th></tr>";
  //Print out the special case first
  echo "<tr>
  <td><center><b>M</td>
  <td><center><b>Motors</td>
  <td><center><b>125%</td>
  <td><center><b>$total[M]</td>
  </tr>";

  echo "<tr>
  <td><center><b>L</td>
  <td><center><b>Lighting</td>
  <td><center><b>Per Table 220.42</td>
  <td><center><b>$total[L]</td>
  </tr>";

  echo "<tr>
  <td><center><b>R</td>
  <td><center><b>Receptacle</td>
  <td><center><b>10k 100%, Rest 50%</td>
  <td><center><b>$total[R]</td>
  </tr>";
  $sql = "SELECT * FROM ps.keys where jn = '$_GET[jn]'";
  $result2 = $conn->query($sql);
  while($row = $result2->fetch_assoc()) {
    echo "<tr>";
    if(array_key_exists($row['keyname'],$extrakey))
    {
      CreateKeyCell($row['id'],'keyname',$row['keyname']);
      CreateKeyCell($row['id'],'description',$row['description']);
      CreateKeyCellN($row['id'],'derating',$row['derating']);
      $index = $row['keyname'];
      echo "<td><center> $total[$index] </td>";
    }
    else if(!array_key_exists($row['keyname'],$extrakey))
    {
      if(in_array($row['keyname'],$codes))
      {
        if(!in_array($row['keyname'],$cCode))
        {
          if(strcasecmp($row['keyname'],"E")==0)
          {
            echo "<td><b><center>$row[keyname]</td>";
            CreateKeyCell($row['id'],'description',$row['description']);
            CreateKeyCellN($row['id'],'derating',$row['derating']);
            $index = $row['keyname'];
            echo "<td><center> $total[$index] </td>";
          }
          else
          {
            echo "<tr>";
            echo "<td><b><center>$row[keyname]</td>
            <td class= 'constant'><b><center>$row[description]</b></td>";
            CreateKeyCellN($row['id'],'derating',$row['derating']);
            $index = $row['keyname'];
            echo "<td><center> $total[$index] </td>";
            echo "</tr>";
          }
        }
      }
      else
      {
        //echo "error debug $row[keyname]";
        CreateKeyError($row['id'],'keyname',$row['keyname']);
        CreateKeyError($row['id'],'description',$row['description']);
        CreateKeyCellN($row['id'],'derating',$row['derating']);
        $index = $row['keyname'];
        echo "<td><center> $total[$index] </td>";
      }
    }
    echo "</tr>";
  }
  echo "<tr><th colspan='$col'>
This table shows the circuit use codes, derating percentage and connected load for that code.  Any values appearing in Black cannot be altered.<br>

Some derating values are fixed by code.  Motors loads at 125%, Receptacle loads first 10k 100%, rest 50%, and Lighting loads per table 220.42 based on building type.<br>

Descriptions and deratings in <i style='color:blue;'>blue</i> can be changed.<br>

Descriptions and deratings in <i style='color:red;'>red</i> are unused and are the only ones that can be deleted.<br>
  </th></tr>";

  echo "</table>";

  echo "</div>";
  echo "<button style='position:relative; top:200px; left:500px;' class='button' id = 'keycc'>Create Key</button>";
  echo "<button style='position:relative; top:200px; left:700px;' class='button' id = 'delcc'>Delete Key</button>";
  $NewKey = new popwin("Create A New Key",1,'nkeypop','ckeypop');
  $NewKey->draw_head();
  $NewKey->draw_table_s();
  //Panel Location Field
  $c_data = array("text","key","","","style='text-transform:uppercase' maxlength='4'");
  $NewKey->draw_content("<b>Key:",$c_data);

  $c_data = array("number","derating","","","style='text-transform:uppercase' maxlength='4'");
  $NewKey->draw_content("<b>Derating:",$c_data);

  $c_data = array("text","description","","","style='text-transform:uppercase' maxlength='20'");
  $NewKey->draw_content("<b>Description:",$c_data);

  $NewKey->draw_table_e();
  echo "<input type = 'submit' name = 'sk' value = 'CREATE'>";
  $NewKey->draw_foot();

  $DelKey = new popwin('Select A Key To Delete',1,'dkey','cdkey');
  $DelKey->draw_head();
  echo "<input type='hidden' value='Delete' name = 'dsk'>";
  $sql = "SELECT * FROM ps.keys WHERE jn = '$_GET[jn]'";
  $result = $conn->query($sql);
  while ($row = mysqli_fetch_array($result)) {
    if(!in_array($row['keyname'],$codes))
    {

      if(!in_array($row['keyname'],$cCode))
      {
        echo "<br>";
        echo "<input type='submit' class = 'button' value='$row[keyname]' name ='sk'>";
        echo "<br>";
      }
    }
  }
  $DelKey->draw_foot();

  echo "<script src='../../Scripts/editkey.js?v=2>'></script>";
}

function CreateKeyCellN($id,$type,$value)
{
  //Get which is which, and importing to javascript.
  //Javascript/Ajax handling with Database. send post to database. and Modifiying it.
  $sid = $type .'_'.$id;
  $tid = $type .'_input_'.$id;
  echo "<td tabindex='0' id=$id class = '$type'><center>";
  echo "<span style='text-transform:uppercase;color:blue' id=$sid class='text'>$value</span>";
  echo "<input style='text-transform:uppercase' type='number' class = 'editbox' id=$tid value='$value' >";
  echo "%";
  echo  "</td>";
}
function CreateKeyCell($id,$type,$value)
{
  //Get which is which, and importing to javascript.
  //Javascript/Ajax handling with Database. send post to database. and Modifiying it.
  $sid = $type .'_'.$id;
  $tid = $type .'_input_'.$id;
  echo "<td tabindex='0' id=$id class = '$type'><center>";
  echo "<span style='text-transform:uppercase;color:blue' id=$sid class='text'>$value</span>";
  echo "<input style='text-transform:uppercase' type='text' class = 'editbox' id=$tid value='$value' >";
  echo  "</td>";
}
function CreateKeyError($id,$type,$value)
{
  //Get which is which, and importing to javascript.
  //Javascript/Ajax handling with Database. send post to database. and Modifiying it.
  $sid = $type .'_'.$id;
  $tid = $type .'_input_'.$id;
  echo "<td tabindex='0' id=$id class = '$type'><center>";
  echo "<span style='text-transform:uppercase;color:red'' id=$sid class='text'>$value</span>";
  echo "<input style='text-transform:uppercase' type='text' class = 'editbox' id=$tid value='$value' >";
  echo  "</td>";
}
?>
