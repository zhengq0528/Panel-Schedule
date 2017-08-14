<?php

function CheckLoop($panel,$jn,$dpanel,$djn,$str,$jns)
{
  include('../Database/db.php');
  $sql = "SELECT * FROM ps.circuit
  WHERE jn= '$djn' and panel = '$dpanel'";

  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {
    //echo "-1--$row[linkpanel]-2--$str -1--$row[phase]-2--$jns <br>";
    if(strcasecmp($row['linkpanel'],$str) == 0 && strcasecmp($row['phase'],$jns)==0)
    {
      return false;
    }
    if($row['linkphase']==1 || $row['linkphase']==4)
    {
      if(CheckLoop($dpanel,$djn,$row['linkpanel'],$row['phase'],$str,$jns) == false)
      {
        return false;
      }
    }
  }
  return true;
}

$link1 = new popwin("Ling To A Panel",'1','lk1','clk1');
$link1->draw_head();

$link1->draw_table_s();
//Connect Which Panel
$c_data = array("text","LUIPanel",'',"","style='text-transform:uppercase' maxlength='10'");
$link1->draw_content("<b>Connect Which Panel",$c_data);

//Enter Job Number if Connecting from Other Project
$c_data = array("text","Job",'',"","style='text-transform:uppercase' maxlength='10'");
$link1->draw_content("<b>Enter Job Number If Connecting From Other Projects",$c_data);
$link1->draw_table_e();
echo "<input type= hidden name = 'slink1' value = 'l1'>";
echo "<input type= hidden name = 'panel' value = '$panelName'>";
echo "<input class= 'button' type='submit' value= 'OK' name = 'ShowTable'>";
$link1->draw_foot();

if(isset($_POST["slink1"]))
{
  $link2 = new popwin(" More Information ",'1','lk2','clk2');
  $link2->draw_head();
  $djn = $_POST['Job'];
  if(empty($_POST['Job'])) $djn = $_GET['jn'];

  $con_panel = new panel($djn,$_POST['LUIPanel']);
  $dpanel = strtoupper($_POST['LUIPanel']);
  $d_type = $con_panel->get_intype();
  $dv = $con_panel->get3PH();
  $cv = $cur_panel->get3PH();
  if($con_panel->circuit==0){
    echo "<script>alert('Panel:$_POST[LUIPanel] Does Not Exist!');</script>";
    return 0;
  }
  else if(!CheckLoop($_POST['panel'],$_GET['jn'],$dpanel,$djn,$_POST['panel'],$_GET['jn']))
  {
    echo "<script>alert('You Can Not Link $dpanel.  You Are Creating A Loop!');</script>";
    return 0;
  }
  else if(strcasecmp($_POST['panel'],$dpanel)==0 &&strcasecmp($djn,$_GET['jn'])==0)
  {
    echo "<script>alert('You Can Not Link $dpanel.  You Are Creating A Loop!');</script>";
    return 0;
  }
  else if($dv != $cv)
  {
    echo "<script>alert('You Can Not Link Two Different Voltage');</script>";
    return 0;
  }
  else if($t_type==1 && $d_type==2 ||$t_type==1 && $d_type==3)
  {
    echo "<script>alert('Wrong Type For Connection');</script>";
    return 0;
  }

  $link2->draw_table_s();

  $sqla = "SELECT * FROM ps.circuit
  WHERE jn = '$_GET[jn]' AND panel = '".$panelName."'";
  $results = $conn->query($sqla);
  $codesex = array('x','X','s','S');
  $ar = array();
  while($rowe = $results->fetch_assoc()) {
    if(empty($rowe['watts1'])) $rowe['watts1']=0;
    if(empty($rowe['watts2'])) $rowe['watts2']=0;
    if(empty($rowe['watts3'])) $rowe['watts3']=0;
    if(in_array($rowe['code'],$codesex)
    && $rowe['watts1']==0
    && $rowe['watts2']==0
    && $rowe['watts3']==0)
    {
      $ar += [$rowe['circuit'] => $rowe['code']];
    }
  }
  echo "<tr><td>Select a circuit</td><td>";
  echo "<center><select id = 1 name='selectedC' style='width:200px;'>";
  $index;
  if($t_type == 1)
  {
    for($index= 0 ; $index <= $circuit ; $index++)
    {
      if(!empty($ar[$index]))
      {
        if(!empty($ar[$index+2]))
        echo "<option value=$index>$index</option>";
      }
    }
  }
  else if($t_type == 2)
  {
    for($index= 0 ; $index <= $circuit ; $index++)
    {
      if(!empty($ar[$index]))
      {
        if($d_type ==1 || $d_type ==4)
        {
          if(!empty($ar[$index+2]))
          echo "<option value=$index>$index</option>";
        }
        else{
          if(!empty($ar[$index+2]) && !empty($ar[$index+4]))
          echo "<option value=$index>$index</option>";
        }
      }
    }
  }
  else
  {
    for($index= 0 ; $index <= $circuit ; $index++)
    {
      if(!empty($ar[$index]))
      {
        echo "<option value=$index>$index</option>";
      }
    }
  }
  echo "</select></center>";
  echo "</td></tr>";

  if($t_type == 3 && $d_type ==1 ||$t_type == 3 && $d_type==4)
  {
    echo "<tr>";
    echo "<td><b> Select Type</td>";
    echo "<td>";
    echo "<select id = 1 name='pos'>";
    echo "<option value='1'>A & B</option>";
    echo "<option value='2'>B & C</option>";
    echo "<option value='3'>C & A</option>";
    echo "</select>";
    echo "<td>";
    echo "</tr>";
  }


  $link2->draw_table_e();
  echo "<input type= hidden name = 'slink2' value = 'l2'>";
  echo "<input type= hidden name = 'panel' value = '$panelName'>";
  echo "<input type= hidden name = 'dpanel' value =  '$_POST[LUIPanel]'>";
  echo "<input type= hidden name = 'djn' value = '$djn'>";
  echo "<input type= hidden name = 'types' value = '$t_type'>";
  echo "<input class= 'button' type='submit' value= 'OK' name = 'ShowTable'>";



  $link2->draw_foot();
  echo "<script>document.getElementById('lk2').style.display = 'block';</script>";
}
?>
