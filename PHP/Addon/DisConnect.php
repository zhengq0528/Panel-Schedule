<?php
$DisConnect = new popwin("Which Panel You Want To Disconnect?",'1','dsc','cdsc');
$DisConnect->draw_head();
$DisConnect->draw_table_s();


$sql = "SELECT * FROM ps.circuit
WHERE jn = '$_GET[jn]' AND panel = '".$panelName."'";
$result = $conn->query($sql);
$codes = array('P','p','T','t','1','4');

echo "<input type='hidden' value= '$panelName' name = 'panel'>";
echo "<input type='hidden' value= '2' name = 'dispan'>";
$type = $cur_panel->type;
echo "<input type= hidden name = 'tp' value = '$type'>";
while($row = $result->fetch_assoc()) {
  if(in_array($row['linkphase'],$codes))
  {
    echo "<input type='hidden' value= '$row[linkpanel]' name = 'lpd'>";
    echo "<input type='hidden' value= '$row[phase]' name = 'pd'>";
    echo "<tr><td><center>$row[uses]</center></td><td>";
    echo " <center><input
    type='submit'
    class = 'button'
    value= $row[circuit]
    name = 'ShowTable'> ";
    echo "</center></td>";
  }
}

$DisConnect->draw_table_e();



$DisConnect->draw_foot();
?>
