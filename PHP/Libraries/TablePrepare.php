<?php


if(strcmp($_POST['redo'],"c1")==0)
{
  $selected_circuit = $_POST['c'];

  if($selected_circuit < $circuit)
  {
    $dr = $selected_circuit + 1;
    $sqls = "SELECT * FROM ps.circuit
    WHERE jn = '$_GET[jn]' and panel = '".$panelName."'";
    $results = $conn->query($sqls);
    while($reco = $results->fetch_assoc()){
      if($reco[circuit] > $selected_circuit)
      {
        //echo $circuit;
        $desql ="DELETE FROM ps.circuit
        WHERE id = $reco[id]";
        $conn->query($desql);
      }
    }
    $desql = "UPDATE ps.panel set circuit = $selected_circuit
    Where panel = '$panelName' AND jn = '$_GET[jn]'";
    $conn->query($desql);
  }
  else if($selected_circuit > $circuit)
  {
    $idarr = array();
    $dr = $selected_circuit + 1;
    $sqls = "SELECT * FROM ps.circuit
    WHERE jn = '$_GET[jn]' and panel = '".$panelName."'";
    $results = $conn->query($sqls);
    while($reco = $results->fetch_assoc()){
      //echo $circuit;
      if(empty($reco['watts1'])) $reco['watts1'] = 0;
      if(empty($reco['watts2'])) $reco['watts2'] = 0;
      if(empty($reco['watts3'])) $reco['watts3'] = 0;
      array_push($idarr, $reco['id']);
      $desql ="INSERT INTO ps.circuit
      (panel,circuit,code,linkphase,uses,bftype,jn,watts1,watts2,
        watts3,linkpanel,linktrans,type,phase)
        VALUES('$reco[panel]',$reco[circuit],'$reco[code]','$reco[linkphase]','$reco[uses]',
          '$reco[bftype]','$_GET[jn]','$reco[watts1]','$reco[watts2]','$reco[watts3]',
          '$reco[linkpanel]','$reco[linktrans]','$reco[type]','$reco[phase]')";
          $conn->query($desql);
        }
        for($i = $circuit+1; $i <= $selected_circuit ;$i++)
        {
          if($t_type == 1 || $t_type == 2)
          {
            $desql = "INSERT INTO ps.circuit
            (panel,circuit,code,linkphase,uses,bftype,jn)
            VALUES('$panelName',$i,'S',0,' ','20A-1P','$_GET[jn]')";
          }
          else if($t_type == 4)
          {
            $desql = "INSERT INTO ps.circuit
            (panel,circuit,code,linkphase,uses,bftype,jn)
            VALUES('$panelName',$i,'S',0,' ','20A-2P','$_GET[jn]')";
          }
          else
          {
            $desql = "INSERT INTO ps.circuit
            (panel,circuit,code,linkphase,uses,bftype,jn)
            VALUES('$panelName',$i,'S',0,' ',' ','$_GET[jn]')";
          }

          $conn->query($desql);
        }

        for($i = 0; $i < count($idarr); $i++)
        {
          $desql ="DELETE FROM ps.circuit
          WHERE id = $idarr[$i]";
          $conn->query($desql);
        }

        $desql = "UPDATE ps.panel set circuit = $selected_circuit
        Where panel = '$panelName' AND jn = '$_GET[jn]'";
        $conn->query($desql);

      }
    }
    //Calculate the loads of total connected
    $l = RecursiveCalCulateConnectLoad($_POST['panel'],$_GET['jn']);
    //calculate the demand load by the building type and get that by ratio
    $d=RecursiveTL($_POST['panel'],$_GET['jn']);
    $dataes = calculate($l[3],$d);
    $totalc = $l[0] + $l[1] + $l[2];
    $ratio = ($dataes[1])/($totalc);
    $d1 = number_format((float)(($l[0])*$ratio),2,'.','');
    $d2 = number_format((float)(($l[1])*$ratio),2,'.','');
    $d3 = number_format((float)(($l[2])*$ratio),2,'.','');
    $total2 = number_format((float)($dataes[1]),2,'.','');
    $keys = array();
    $keyDerating = array();
    $sql2 = "SELECT * FROM ps.keys WHERE jn ='$_GET[jn]'";
    $result1 = $conn->query($sql2);
    while($row = mysqli_fetch_array($result1))
    {
      $keys +=[strtoupper($row['keyname']) => $row['description']];
      $keyDerating +=[strtoupper($row['keyname']) => $row['derating']];
    }
    echo "<div class ='panel_table'>";

    if($cur_panel->get_intype == 1)
    $totalA = ($total2*1000) / ($cur_panel->get3PH());
    else
    $totalA = ($total2*1000)/(sqrt(3)*$cur_panel->get3PH());
    $totalA = number_format((float)($totalA),1,'.','');

    ?>
