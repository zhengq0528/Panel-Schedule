<?php
$tran1 = new popwin("Uses A Transformer","1","tf1","ctf1");
$tran1->draw_head();
$tran1->draw_table_s();
echo " <tr>
<td>Connect Which Panel</td>
<td><center><Input list='productName' style='text-transform:uppercase'
type='text' id = 'LUIPanel' name = 'LUIPanel' ></center></td></tr>";
echo "<tr>
<td>Transformer No. </td>
<td><center>
<input style='text-transform:uppercase' type = 'text' value ='' name = 'tname'>
</center></td>
</tr>
<tr>
<td>KVA </td>
<td><center>
<input type = 'number' value ='15' name = 'kva'>
</center></td>
</tr>
<tr>
<td>Loss percentage </td>
<td><center>
<input type = 'number' value ='0' name = 'loss'></center>
</td>
</tr>
<tr>
<td>Transformer Type </td>
<td>
<center>
<select id = 1 name='type'>
<option value='Connected load from panel'>Connected load from panel</option>
<option value='KVA only'>KVA only</option>
<option value='Direct'>Direct</option>
</select></center>
</td>
</tr>
<tr>
<td>Enter Job Number If Connecting From Other Projects</td>
<td><center><Input style='text-transform:uppercase' type='text' id = 'Job' name = 'Job' ></center></td>
</tr>";
$tran1->draw_table_e();
echo "
<input type= hidden name = 'tran1' value = 'tran1'>
<input type= hidden name = 'panel' value = '$panelName'>
<input class= 'button' type='submit' value= 'OK' name = 'ShowTable'>";
$tran1->draw_foot();

if(isset($_POST['tran1']))
{
  $tran2 = new popwin("More Information","1","tf2","ctf2");
  $tran2->draw_head();

  $djn = $_POST['Job'];
  if(empty($_POST['Job'])) $djn = $_GET['jn'];

  $con_panel = new panel($djn,$_POST['LUIPanel']);
  $dpanel = strtoupper($_POST['LUIPanel']);
  $d_type = $con_panel->get_intype();
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
  else if($t_type==1 && $d_type==2 ||$t_type==1 && $d_type==3)
  {
    echo "<script>alert('Wrong Type For Connection');</script>";
    return 0;
  }
  else if(empty($_POST['tname']))
  {
    echo "<script>alert('Transformer Name Can Not Be Empty');</script>";
    return 0;
  }

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

  $tran2->draw_table_s();
  echo "<tr><td>Connect Panel To Circuit </td><td>";
  echo "<select id = 1 name='selectedC' style='width:200px;'>";
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
  echo "</select></td></tr>";

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
  $tran2->draw_table_e();
  $vts = $cur_panel->get3PH();
  echo "
  <input type= hidden name = 'strans2' value = 't2'>
  <input type= hidden name = 'loss' value =  '$_POST[loss]'>
    <input type= hidden name = 'volt' value =  '$vts'>
  <input type= hidden name = 'tname' value =  '$_POST[tname]'>
  <input type= hidden name = 'kva' value =  '$_POST[kva]'>
  <input type= hidden name = 'type' value = '$_POST[type]'>
  <input type= hidden name = 'panel' value = '$panelName'>
  <input type= hidden name = 'dpanel' value = '$_POST[LUIPanel]'>
  <input type= hidden name = 'djn' value = '$djn'>";
  echo "<input class= 'button' type='submit' value= 'OK' name = 'ShowTable'>";
  $tran2->draw_foot();
  echo "<script>document.getElementById('tf2').style.display = 'block';</script>";
  //echo "<script>alert('Hello you here');</script>";
}
?>
