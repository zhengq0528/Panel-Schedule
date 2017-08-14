<?php
//Calculating the dataString
function calculate($l, $d)
{
  error_reporting(0);
  $rtmp = $l['R'];
  $ltmp = $l['L'];
  if($l['R'] > 10000)
  {
    $Rload = 10000;
    $Rrestl = $l['R'] - $Rload;
    $Rrestl = $Rrestl / 2;
    $l['R'] = $Rload + $Rrestl;
  }

  include('PHP/Database/db.php');
  $sql2 = "SELECT * FROM ps.project WHERE jn ='$_GET[jn]'";
  $result2 = $conn->query($sql2);
  $Prow = mysqli_fetch_array($result2);
  $p_type = $Prow['type'];

  if($p_type == 1)
  {
    if($l['L'] > 3000 && $l['L'] <= 120000)
    {
      $Lload = 3000;
      $Lrestl = $l['L'] - $Lload;
      $Lrestl = $Lrestl * 0.35;
      $l['L'] = $Lload + $Lrestl;
    }
    else if($l['L'] > 120000)
    {
      $Lload = 43950;
      $Lrestl = $l['L'] - 120000;
      $Lrestl = $Lrestl * 0.25;
      $l['L'] = $Lload + $Lrestl;
    }
  }
  else if($p_type ==2)
  {
    if($l['L'] > 50000)
    {
      $Lload = 20000;
      $Lrestl = $l['L'] - 50000;
      $Lrestl = $Lrestl * 0.2;
      $l['L'] = $Lload + $Lrestl;
    }
    else
    {
      $l['L'] = $l['L'] * 0.4;
    }
  }
  else if($p_type ==3)
  {
    if($l['L'] > 20000 && $l['L'] <= 100000)
    {
      $Lload = 20000 * 0.5;
      $Lrestl = $l['L'] - 20000;
      $Lrestl = $Lrestl * 0.4;
      $l['L'] = $Lload + $Lrestl;
    }
    else if($l['L'] > 100000)
    {
      $Lload = 42000;
      $Lrestl = $l['L'] - 100000;
      $Lrestl = $Lrestl * 0.3;
      $l['L'] = $Lload + $Lrestl;
    }
    else
    {
      $l['L'] = $l['L'] * 0.5;
    }
  }
  else if($p_type ==4)
  {
    if($l['L'] > 12500)
    {
      $Lload = 12500;
      $Lrestl = $l['L'] - 12500;
      $Lrestl = $Lrestl * 0.5;
      $l['L'] = $Lload + $Lrestl;
    }
  }
  else
  {
      $ltmp = $l['L'];
  }

  $total2C = ($d[0] + $d[1] +$d[2]);
  $total2 = $total2C - $rtmp -$ltmp;
  $total2 = ($total2 + $l['R'] + $l['L']) / 1000;
  $ratio = $total2 / ($total2C/1000);

  return array($ratio,$total2,$rtmp,$ltmp,$l,$d);
}


//Get type name by using panel name and jn
function getTypesJn($panelName,$jn)
{
  include('PHP/Database/db.php');

  $sql = "SELECT type FROM ps.panel WHERE jn='$jn' and panel = '$panelName'";
  $result = $conn->query($sql);
  $type;
  while($row = $result->fetch_assoc()) {
    $type = $row['type'];
  }
  return $type;
}

function CompareType($type)
{
  if(strcmp($type,"SinglePhase") ==0)
  {
    return 1;
  }
  else if(strcmp($type,"ThreePhase")==0)
  {
    return 2;
  }
  else if(strcmp($type,"Isolation")==0)
  {
    return 4;
  }
  else
  {
    return 3;
  }
}

//Update the database, quick call
function UpdateDB($sql)
{
  include('PHP/Database/db.php');
  if($conn->query($sql)){
  }
  else {
    echo "<br>ERROR: Could not able to execute $conn->error";
  }
}

//Generate SQL command for linking the panel. It might also work for transfering panel
function GenSql($watts,$load,$linkpanelName,$panelName,$circuit,$lp,$djn,$jn)
{
  return "UPDATE ps.circuit SET $watts = '$load',code = 'P',
  uses = 'Panel:$linkpanelName',linkpanel = '$linkpanelName',linkphase = '$lp',phase = '$djn'
  WHERE jn='$jn' and  panel = '$panelName' AND circuit = '$circuit'";
}
function GenSql1($watts,$load,$linkpanelName,$panelName,$circuit,$lp,$name,$djn,$jn)
{
  return "UPDATE ps.circuit SET $watts = '$load',code = 'T', linktrans = '$name',
  uses = 'Trans-$name:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '$lp',phase = '$djn'
  WHERE jn='$jn' and  panel = '$panelName' AND circuit = '$circuit'";
}

function GetPanelType($panelName,$jn)
{
  //include('../../PHP/Database/db.php');
  include('PHP/Database/db.php');
  $sql = "SELECT type FROM ps.panel WHERE jn='$jn' and panel = '$panelName'";
  $result = $conn->query($sql);
  $type;
  while($row = $result->fetch_assoc()) {
    $type = $row['type'];
  }
  if(strcmp($type,"SinglePhase") ==0)
  {
    return 1;
  }
  else if(strcmp($type,"ThreePhase")==0)
  {
    return 2;
  }
  else if(strcmp($type,"Isolation")==0)
  {
    return 4;
  }
  else
  {
    return 3;
  }
}
function LinkPanel($infoArray)
{


  include('PHP/Database/db.php');
  //Receiving informations to create connections
  //
  $panel = $infoArray[0];  $DesPanel = $infoArray[1];  $circuit = $infoArray[2];
  $t_type = $infoArray[3];  $desjn = $infoArray[4];  $jn = $infoArray[5];
  $loadA=0; $loadB=0; $loadC=0; $OSer; $DSer;

  $d_type = GetPanelType($DesPanel,$desjn);
  $sql = "SELECT * FROM ps.circuit
  WHERE jn='$desjn' and panel = '$DesPanel'";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
    $loadA += $row['watts1']; $loadB += $row['watts2']; $loadC += $row['watts3'];
  }

  if($t_type == 2)
  {
    $pos = $circuit%6;
    if($d_type == 1)
    {
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql("watts1",$loadA,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts2",$loadB,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
      else if($pos==3 || $pos ==4)
      {
        UpdateDB(GenSql("watts2",$loadA,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts3",$loadB,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
      else
      {
        UpdateDB(GenSql("watts3",$loadA,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts1",$loadB,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
    }
    else if($d_type == 4)
    {
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql("watts1",($loadA/2),$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts2",($loadA/2),$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
      else if($pos==3 || $pos ==4)
      {
        UpdateDB(GenSql("watts2",($loadA/2),$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts3",($loadA/2),$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
      else
      {
        UpdateDB(GenSql("watts3",($loadA/2),$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts1",($loadA/2),$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
    }
    else
    {
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql("watts1",$loadA,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts2",$loadB,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
        UpdateDB(GenSql("watts3",$loadC,$DesPanel,$panel,($circuit+4),3,$desjn,$jn));
      }
      else if($pos==3 || $pos ==4)
      {
        UpdateDB(GenSql("watts2",$loadB,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts3",$loadC,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
        UpdateDB(GenSql("watts1",$loadA,$DesPanel,$panel,($circuit+4),3,$desjn,$jn));
      }
      else
      {
        UpdateDB(GenSql("watts3",$loadC,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts1",$loadA,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
        UpdateDB(GenSql("watts2",$loadB,$DesPanel,$panel,($circuit+4),3,$desjn,$jn));
      }
    }
  }
  else if($t_type == 1)
  {
    $pos = $circuit%4;

    if($d_type == 4)
    {
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql("watts1",($loadA/2),$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts2",($loadA/2),$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
      else {
        UpdateDB(GenSql("watts2",($loadA/2),$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts1",($loadA/2),$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
    }
    else{
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql("watts1",$loadA,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts2",$loadB,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
      else {
        UpdateDB(GenSql("watts2",$loadA,$DesPanel,$panel,$circuit,1,$desjn,$jn));
        UpdateDB(GenSql("watts1",$loadB,$DesPanel,$panel,($circuit+2),2,$desjn,$jn));
      }
    }
  }
  else
  {
    if($d_type == 4 || $d_type == 1)
    {
      $sqle = "SELECT * FROM ps.circuit
      WHERE jn='$jn' and panel = '$panel' and circuit = '$circuit'";
      $resulte = $conn->query($sqle);
      $ditype;
      while($rowe = $resulte->fetch_assoc()) {
        $ditype = $rowe['type'];
      }
      if(empty($ditype)) $ditype ==1;
      if($d_type ==1)
      {
        if($ditype==1)
        {
          $sql = "UPDATE ps.circuit
          SET watts1 = '$loadA',watts2 ='$loadB',code ='P',
          uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
          WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
        }
        else if($ditype==2)
        {
          $sql = "UPDATE ps.circuit
          SET watts2 = '$loadA',watts3 ='$loadB',code ='P',
          uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
          WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
        }
        else if($ditype==3)
        {
          $sql = "UPDATE ps.circuit
          SET watts3 = '$loadA',watts1 ='$loadB',code ='P',
          uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
          WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
        }
      }
      else if($d_type ==4)
      {
        if($ditype==1)
        {
          $als = $loadA/2;
          $sql = "UPDATE ps.circuit
          SET watts1 = '$als',watts2 ='$als',code ='P',
          uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
          WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
        }
        else if($ditype==2)
        {
          $als = $loadA/2;
          $sql = "UPDATE ps.circuit
          SET watts2 = '$als',watts3 ='$als',code ='P',
          uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
          WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
        }
        else if($ditype==3)
        {
          $als = $loadA/2;
          $sql = "UPDATE ps.circuit
          SET watts3 = '$als',watts1 ='$als',code ='P',
          uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
          WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
        }
      }
    }
    else
    {
      $sql = "UPDATE ps.circuit
      SET watts1 = '$loadA',watts2 ='$loadB',watts3 ='$loadC',code ='P',
      uses ='Panel:$DesPanel',linkpanel = '$DesPanel',linkphase = '1',phase = '$desjn'
      WHERE jn='$jn' and panel = '$panel' AND circuit = '$circuit'";
    }
    UpdateDB($sql);
  }

}


function RecursiveCalCulateConnectLoad($panelName,$djn)
{

  $type = getTypesJn($panelName,$djn);
  $t_type = CompareType($type);
  include('PHP/Database/db.php');

  $load1 =0; $load2 =0; $load3 =0;
  $keyArray = array();

  $sql = "SELECT * FROM ps.circuit
  WHERE jn= '$djn' and panel = '$panelName'";
  $result = $conn->query($sql);

  //echo "$sql";
  while($row = $result->fetch_assoc()) {
    $letter = strtoupper($row['code']);

    if($row['linkphase'] == 0)
    {

      $load1 += $row['watts1'];    $load2 += $row['watts2'];      $load3 += $row['watts3'];
      if(!empty($letter))
      {
        if(!array_key_exists(strtoupper($letter),$keyArray))
        $keyArray[$letter] += $row['watts1'] + $row['watts2'] + $row['watts3'];
        else
        $keyArray[$letter] += $row['watts1'] + $row['watts2'] + $row['watts3'];
      }
    }
    else if($row['linkphase'] == 1){
      $array = array($panelName,$row['linkpanel'], $row['circuit'],$t_type,$row['phase'],$row['jn']);
      LinkPanel($array); //updating the panel
      $l = RecursiveCalCulateConnectLoad($row['linkpanel'],$row['phase']);
      if(GetPanelType($row['linkpanel'],$row['phase'])==4)
      {
        $load1 += ($l[0]/2);  $load2 += ($l[0]/2);
      }
      else
      {
        $load1 += $l[0];  $load2 += $l[1];  $load3 += $l[2];
      }
      foreach($l[3] as $key => $value) {
        $keyArray[$key] += $value;
      }
    }
    else if($row['linkphase'] == 4){
      $array = array($panelName,$row['linkpanel'], $row['circuit'],$t_type,$row['linktrans'],$row['phase'],$row['jn']);
      TranPanel($array);
      $l = RecursiveCalCulateConnectLoad($row['linkpanel'],$row['phase']);
      $load1 += $l[0]; $load2 += $l[1]; $load3 += $l[2];
      foreach($l[3] as $key => $value) {
        $keyArray[$key] += $value;
      }
    }
  }
  $loads = array($load1,$load2,$load3,$keyArray);
  return $loads;
}


function TranPanel($infoArray)
{
  //var_dump($infoArray);
  include('PHP/Database/db.php');
  $loadA=0;$loadB=0;$loadC=0;
  $panelName = $infoArray[0]; $linkpanelName = $infoArray[1]; $circuit = $infoArray[2];
  $t_type = $infoArray[3];$TranName = $infoArray[4];$djn = $infoArray[5]; $jn = $infoArray[6];
  $d_type = GetPanelType($linkpanelName,$djn);
  //echo "$d_type and $t_type <br>";
  $sql = "SELECT * FROM ps.circuit
  WHERE jn='$djn' and panel = '$linkpanelName'";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
    $loadA += $row['watts1']; $loadB += $row['watts2']; $loadC += $row['watts3'];
  }
  if($t_type == 2)//if it is three
  {
    $pos = $circuit%6;
    if($d_type == 1)
    {
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql1("watts1",$loadA,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts2",$loadB,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      }
      else if($pos==3 || $pos ==4)
      {
        UpdateDB(GenSql1("watts2",$loadA,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts3",$loadB,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      }
      else
      {
        UpdateDB(GenSql1("watts3",$loadA,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts1",$loadB,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      }
    }
    else if($d_type == 4)
    {
      $als = $loadA/2;
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql1("watts1",$als,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts2",$als,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      }
      else if($pos==3 || $pos ==4)
      {
        UpdateDB(GenSql1("watts2",$als,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts3",$als,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      }
      else
      {
        UpdateDB(GenSql1("watts3",$als,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts1",$als,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      }
    }
    else {
      if($pos==1 || $pos ==2)
      {
        UpdateDB(GenSql1("watts1",$loadA,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts2",$loadB,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts3",$loadC,$linkpanelName,$panelName,($circuit+4),6,$TranName,$djn,$jn));
      }
      else if($pos==3 || $pos ==4)
      {
        UpdateDB(GenSql1("watts2",$loadB,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts3",$loadC,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts1",$loadA,$linkpanelName,$panelName,($circuit+4),6,$TranName,$djn,$jn));
      }
      else
      {
        UpdateDB(GenSql1("watts3",$loadC,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts1",$loadA,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
        UpdateDB(GenSql1("watts2",$loadB,$linkpanelName,$panelName,($circuit+4),6,$TranName,$djn,$jn));
      }
    }
  }
  else if($t_type == 1)
  {
    //echo "<yo there>";
    $pos = $circuit%4;
    if($pos==1 || $pos ==2)
    {
      UpdateDB(GenSql1("watts1",$loadA,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
      UpdateDB(GenSql1("watts2",$loadB,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      //UpdateDB(GenSql1("watts1",$loadC,$linkpanelName,$panelName,($circuit+4),6,$TranName,$djn,$jn));
    }
    else {
      UpdateDB(GenSql1("watts2",$loadA,$linkpanelName,$panelName,$circuit,4,$TranName,$djn,$jn));
      UpdateDB(GenSql1("watts1",$loadB,$linkpanelName,$panelName,($circuit+2),5,$TranName,$djn,$jn));
      //UpdateDB(GenSql1("watts2",$loadC,$linkpanelName,$panelName,($circuit+4),6,$TranName,$djn,$jn));
    }
  }
  else
  {
    if($d_type == 4 || $d_type == 1)
    {
      $sqle = "SELECT * FROM ps.circuit
      WHERE jn='$jn' and panel = '$panelName' and circuit = '$circuit'";
      $resulte = $conn->query($sqle);
      $ditype;

      while($rowe = $resulte->fetch_assoc()) {
        $ditype = $rowe['type'];
      }

      if(empty($ditype)) $ditype ==1;
      if($d_type ==1)
      {
        if($ditype==1)
        {

          $sql = "UPDATE ps.circuit
          SET watts1 = '$loadA',watts2 ='$loadB',code ='T', linktrans = '$TranName',
          uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
          WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
        }
        else if ($ditype==2)
        {
          $sql = "UPDATE ps.circuit
          SET watts2 = '$loadA',watts3 ='$loadB',code ='T', linktrans = '$TranName',
          uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
          WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
        }
        else if ($ditype==3)
        {
          $sql = "UPDATE ps.circuit
          SET watts3 = '$loadA',watts1 ='$loadB',code ='T', linktrans = '$TranName',
          uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
          WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
        }
      }
      else if($d_type==4)
      {
        //echo "$sql quan $d_type $t_type";

        $als = $loadA/2;
        if($ditype==1)
        {
          $sql = "UPDATE ps.circuit
          SET watts1 = '$als ',watts2 ='$als',code ='T', linktrans = '$TranName',
          uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
          WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
        }
        else if ($ditype==2)
        {
          $sql = "UPDATE ps.circuit
          SET watts2 = '$als ',watts3 ='$als',code ='T', linktrans = '$TranName',
          uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
          WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
        }
        else if ($ditype==3)
        {
          $sql = "UPDATE ps.circuit
          SET watts3 = '$als ',watts1 ='$als',code ='T', linktrans = '$TranName',
          uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
          WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
        }
          //echo "$sql";
      }
    }
    else
    {
      $sql = "UPDATE ps.circuit
      SET watts1 = '$loadA',watts2 ='$loadB',watts3 ='$loadC',code ='T', linktrans = '$TranName',
      uses ='Trans-$TranName:($linkpanelName)',linkpanel = '$linkpanelName',linkphase = '4',phase = '$djn'
      WHERE jn='$jn' and panel = '$panelName' AND circuit = '$circuit'";
    }

    UpdateDB($sql);

  }

}

function RecursiveTL($panelName,$djn)
{

  include('PHP/Database/db.php');
  $sql = "SELECT * FROM ps.keys
  WHERE jn= '$_GET[jn]'";
  $result = $conn->query($sql);
  $kv = array();

  while($row = $result->fetch_assoc()) {
    $kv +=[strtoupper($row['keyname']) => $row['derating']];
  }

  $type = getTypesJn($panelName,$djn);
  $t_type = CompareType($type);
  $load1 =0; $load2 =0; $load3 =0;
  $keyArray = array();

  $sql = "SELECT * FROM ps.circuit
  WHERE jn= '$djn' and panel = '$panelName'";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
    $letter = strtoupper($row['code']);
    if($row['linkphase'] == 0)
    {
      $ratio = 0.5;
      if(empty($letter) ||empty($kv[$letter]))
      {
        $ratio = 1;
      }
      else
      {
        $ratio = $kv[$letter] / 100;
      }
      $load1 += ($row['watts1'] * $ratio); $load2 += ($row['watts2'] * $ratio);  $load3 += ($row['watts3'] * $ratio);
      if((strcasecmp($letter,"P")!= 0 || strcasecmp($letter,"T")!=0))
      {
        if(!empty($letter))
        {
          if(!array_key_exists(strtoupper($letter),$keyArray))
          {
            $keyArray[$letter] += $row['watts1'] + $row['watts2'] + $row['watts3'];
          }
          else
          {
            $keyArray[$letter] += $row['watts1'] + $row['watts2'] + $row['watts3'];
          }
        }
      }
    }
    else if($row['linkphase'] == 1){
      $dejn = $row['phase'];
      $array = array($panelName,$row['linkpanel'], $row['circuit'],$t_type,$dejn,$row['jn']);
      $l = RecursiveTL($row['linkpanel'],$dejn);
      $load1 += $l[0];
      $load2 += $l[1];
      $load3 += $l[2];
      foreach($l[3] as $key => $value) {
        $keyArray[$key] += $value;
      }
    }
    else if($row['linkphase'] == 4){
      $dejn = $row['phase'];
      $sql1 = "SELECT * FROM ps.panel WHERE panel = '$panelName' and jn = '$row[jn]'";
      $result1 = $conn->query($sql1);
      $row2 = $result1->fetch_assoc();
      $t = $row2['type'];
      $t_type = CompareType($t);
      $array = array($panelName,$row['linkpanel'], $row['circuit'],$t_type,$row['linktrans'],$dejn,$row['jn']);
      $l = RecursiveTL($row['linkpanel'],$dejn);
      $load1 += $l[0];
      $load2 += $l[1];
      $load3 += $l[2];
      foreach($l[3] as $key => $value) {
        $keyArray[$key] += $value;
      }
    }
  }
  $loads = array($load1,$load2,$load3,$keyArray);

  return $loads;
}


?>
