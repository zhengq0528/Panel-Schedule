<?php
$cirarr = array();
if($t_type==2)
{
  for($index = 1;$index < 84; $index++)
  {
    $index+=5;
    array_push($cirarr,$index);
  }
}
else if($t_type==1)//single
{
  $cirarr = array('4','6','8','12','16','20',
  '24','30','32','36','40','42');
}
else if($t_type==4)//iso
{
  $cirarr = array('8','10','12','14','16');
}
else if($t_type==3)//Distribution
{
  for($index = 1;$index < 84; $index++)
  {
    $index++;
    array_push($cirarr,$index);
  }
}
$codesexx = array('x','X','s','S');
$sqls = "SELECT * FROM ps.circuit
WHERE jn = '$_GET[jn]' and panel = '".$panelName."'";
$results = $conn->query($sqls);
$max = 0;
while($rows = $results->fetch_assoc()){
  if(empty($rows['watts1'])) $rows['watts1']=0;
  if(empty($rows['watts2'])) $rows['watts2']=0;
  if(empty($rows['watts3'])) $rows['watts3']=0;
  if(!in_array($rows['code'],$codesexx))
  {
    if($rows['circuit'] > $max)
    {
      $max = $rows['circuit'];
    }
  }
  else if($rows['watts1']>0 || $rows['watts2']> 0 || $rows['watts3']>0)
  {
    if($rows['circuit'] > $max)
    {
      $max = $rows['circuit'];
    }
  }
}

$EditCircuit = new popwin('This Selection will Change the Panel Size!',1,'ecs','lecs');
$EditCircuit->draw_head();
echo "<select id = 1 name='c' style='height:50px; width:300px;'>";
echo "<option value='$circuit'>$circuit</option>";
for($i = 0; $i < count($cirarr); $i++)
{
  if($cirarr[$i] > $max)
  echo "<option value='$cirarr[$i]'>$cirarr[$i]</option>";
}
echo "</select>";
echo "<br>";
echo "<br>";
echo "<input type='hidden' value= '$_POST[panel]' name = 'panel'>";
echo "<input type='hidden' value= 'c1' name = 'redo'>";
echo "<input class='button' type = 'submit' name = 'ShowTable' value = 'Update'>";
$EditCircuit->draw_foot();

?>
