<?php
if(isset($_POST['strans2']))
{
  $djn = $_POST['djn'];
  if(empty($djn)) $djn = $_GET['jn'];
  if(!empty($_POST['pos']))
  {
    $sqle = "UPDATE ps.circuit set type = '$_POST[pos]'
    WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' AND circuit = '$_POST[selectedC]'";
    UpdateDB($sqle);
  }
    $con_panel = new panel($djn,$_POST['dpanel']);
    $cur_panel = new panel($_GET['jn'],$_POST['panel']);
    $t_type = $cur_panel->get_intype();
  $volts = $con_panel->get3PH();
  //echo "<script>alert('$volts,$djn,$_POST[dpanel]');</script>";
  $sql = "INSERT INTO ps.transformer(jn,rating,source,svolt,dvolt,destination,
    phases,remark,loadtype,loss,djn,name,service)
    VALUES('$_GET[jn]','$_POST[kva]','$_POST[panel]',$_POST[volt],$volts,'$_POST[dpanel]',
      '1','Connecting $_POST[panel] to $_POST[dpanel]',
      '$_POST[type]','$_POST[loss]','$djn','$_POST[tname]','default')";
      if($conn->query($sql))
      {
        $last_id = $conn->insert_id;
        $array = array($_POST['panel'],$_POST['dpanel'],$_POST['selectedC'],$t_type,$_POST['tname'],$djn,$_GET['jn']);
        TranPanel($array);
      }
      else{


        echo "<h1 style='color:red;'> $conn->error</h1>";
          echo "<h1 style='color:red;'> $conn->error</h1>";
            echo "<h1 style='color:red;'> $conn->error</h1>";
              echo "<h1 style='color:red;'> $conn->error</h1>";
                echo "<h1 style='color:red;'>$sql</h1>";

      }

}

if(isset($_POST['slink2']))
{
  $djn = $_POST['djn'];
  $array = array($_POST['panel'],$_POST['dpanel'],$_POST['selectedC'],$_POST['types'],$djn,$_GET['jn']);
  if(!empty($_POST['pos']))
  {
    $sqle = "UPDATE ps.circuit set type = '$_POST[pos]'
    WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' AND circuit = '$_POST[selectedC]'";
    $conn->query($sqle);
    //UpdateDB($sqle);
  }
  LinkPanel($array);
}
if(isset($_POST['dispan']))
{
  $abc = new panel($_POST['pd'],$_POST['lpd']);
  $dty = $abc->get_intype();
  $sql = "SELECT * from ps.circuit
  WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' and circuit = '$_POST[ShowTable]'";
  $result = $conn->query($sql);
  $phase;
  while($row = $result->fetch_assoc()) {
    $phase = $row['linktrans'];
  }
  $sql = "DELETE FROM ps.transformer
  WHERE jn = '$_GET[jn]' and name = '$phase'";
  $conn->query($sql);

  $sql = "SELECT * FROM ps.circuit
  WHERE jn = '$_GET[jn]' and panel = '$panelName' and circuit = '$_POST[ShowTable]'";
  $result = $conn->query($sql);
  $a;$b;
  while($row = $result->fetch_assoc()){
    $a = $row['linkpanel'];
    $b = $row['phase'];
  }
  GetPanelType($a,$b);
  $sql = "UPDATE ps.circuit SET code = 'S',uses =' ',watts1 = 0, watts2 = 0,watts3 =0,
  linkpanel = '', linkphase = 0
  WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' and circuit = '$_POST[ShowTable]'";

  if(mysqli_query($conn,$sql)){
  }
  else {
    echo " <br>ERROR: Could not able to execute $sql. " . mysqli_error($conn);
  }

  $t = CompareType($_POST['tp']);
  $c1 = $_POST['ShowTable'] + 2;
  $c2 = $c1 + 2;
  if($t ==2)
  {
    $sql = "UPDATE ps.circuit SET code = 'S',uses =' ',watts1 = 0, watts2 = 0,watts3 =0,
    linkpanel = '', linkphase = 0
    WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' and circuit = '$c1'";
    $conn->query($sql);
    if($dty != 4 && $dty != 1)
    {
      $sql = "UPDATE ps.circuit SET code = 'S',uses =' ',watts1 = 0, watts2 = 0,watts3 =0,
      linkpanel = '', linkphase = 0
      WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' and circuit = '$c2'";
      $conn->query($sql);
    }
  }
  else if($t == 1 || $t == 4)
  {
    $sql = "UPDATE ps.circuit SET code = 'S',uses =' ',watts1 = 0, watts2 = 0,watts3 =0,
    linkpanel = '', linkphase = 0
    WHERE jn='$_GET[jn]' and panel = '$_POST[panel]' and circuit = '$c1'";
    $conn->query($sql);
  }
}

?>
