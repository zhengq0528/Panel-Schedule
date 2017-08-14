<?php
//Drawing,creating the panel creator
draw_create_panel(1,"Creating Distribution Panel","create_dist","close_dist","apn","Create Panel");
draw_create_panel(2,"Creating Three Phase Panel","create_three","close_three","apn","Create Panel");
draw_create_panel(3,"Creating Single Phase Panel","create_single","close_single","apn","Create Panel");
draw_create_panel(4,"Creating Isolation Panel","create_isol","close_isol","apn","Create Panel");
draw_create_panel(5,"Creating Ulta Panel","create_ulta","close_ulta","apn","Create Panel");
if(isset($_POST['updatepanel']))
{
  $_POST['location']  = strtoupper($_POST['location']);
  $_POST['note']      = strtoupper($_POST['note']);
  $_POST['breaker']  = strtoupper($_POST['breaker']);
  $_POST['lug']      = strtoupper($_POST['lug']);

  $_POST['location']  = addslashes($_POST['location']);
  $_POST['note']      = addslashes($_POST['note']);
  $_POST['breaker']   = addslashes($_POST['breaker']);
  $_POST['lug']       = addslashes($_POST['lug']);
  $sql = "UPDATE ps.panel SET location = '$_POST[location]',
  groundbus = '$_POST[groundbus]',
  rating = '$_POST[rating]',
  mounting = '$_POST[mounting]',
  service = '$_POST[service]',
  lug = '$_POST[lug]',
  breaker = '$_POST[breaker]',
  notes = '$_POST[note]',
  transize = '$_POST[transize]',
  secvol = '$_POST[secvol]',
  monitor = '$_POST[monitor]',
  fuse = '$_POST[fuse]'
  WHERE panel = '$_POST[panel]' and jn = '$_GET[jn]'";
  if(mysqli_query($conn,$sql))
  {
    echo "<form method ='post' id='panel_update_success'>";
    echo "<input type='hidden' value = '$_POST[panel]' name = panel>";
    echo "<input type='hidden' class = 'button' value= '$_POST[panel]' name = 'ShowTable'>";
    echo "</form>";
    echo "<script>document.getElementById('panel_update_success').submit();</script>";
  }
  else
  {
    echo "<script>You Failed To Update Panel.</script>";
  }
  //echo "<h1><br>$sql </h1>";

}
//If User Clicked creat panel button. This will be called
if(isset($_POST['apn']))
{
  //Translate data to sql format.  Incase of error happen here
  $_POST['panel']     = strtoupper($_POST['panel']);
  $_POST['location']  = strtoupper($_POST['location']);
  $_POST['note']      = strtoupper($_POST['note']);
  $_POST['breaker']  = strtoupper($_POST['breaker']);
  $_POST['lug']      = strtoupper($_POST['lug']);

  $_POST['panel']     = addslashes($_POST['panel']);
  $_POST['location']  = addslashes($_POST['location']);
  $_POST['note']      = addslashes($_POST['note']);
  $_POST['breaker']   = addslashes($_POST['breaker']);
  $_POST['lug']       = addslashes($_POST['lug']);

  //Panel Name Can Not Be Empty
  if(empty($_POST['panel'])||empty($_POST['type']))
  {
    //If the panel is empty, return false
    echo "<script> alert('Panel Name or Panel Type Cannot Be Empty'); </script>";
  }
  else
  {
    //Important needed values
    if(empty($_POST['fuse'])) $_POST['fuse'] = '3';
    if(empty($_POST['rating'])) $_POST['rating'] = 1000;

    //Initial break for circuit
    $dt1 = array('ThreePhase','SinglePhase');
    $breaks = '';
    if(in_array($_POST['type'],$dt1)) $breaks = '20A-1P';
    else if(strcasecmp($_POST['type'],'Isolation')==0) $breaks = '20A-2P';

    //Creating the panel
    $sql = "INSERT INTO ps.panel(jn,panel,type,location,circuit,
      service,mounting,rating,breaker,groundbus,
      fuse,notes,transize,secvol,monitor,lug)
      VALUES
      (
        '$_GET[jn]','$_POST[panel]','$_POST[type]','$_POST[location]','$_POST[circuit]',
        '$_POST[service]','$_POST[mounting]','$_POST[rating]','$_POST[breaker]','$_POST[groundbus]',
        '$_POST[fuse]','$_POST[note]','$_POST[transize]','$_POST[secvol]','$_POST[monitor]','$_POST[lug]'
      )";

      if ($conn->query($sql) === TRUE)
      {
        //Creating all circuits
        $sql = "INSERT INTO ps.circuit (panel,circuit,code,linkphase,uses,bftype,jn) VALUES";
        $cir = 0;
        ///////Checking if that is a ulta panel
        if($_POST['ulta']==1)
        {
          //if this is ultra panel, then it will showup
          $cir = -6;
          while($cir < 0){
            $sql .="('$_POST[panel]',$cir,'S',0,' ','[CONTROL]','$_GET[jn]'),";
            $cir++;
          }
        }
        //Creating circuits for this panel
        $cir = 1;
        while($cir <= $_POST['circuit']){
          if($cir == $_POST['circuit'])
          {
            //The last insert does not have , and end the loop, inserting into database
            $sql .="('$_POST[panel]',$cir,'S',0,'  ','$breaks','$_GET[jn]')";
            if($conn->query($sql)){
              //Submit it to new panel
              echo "<form method ='post' id='panel_update_success'>";
              echo "<input type='hidden' value = '$_POST[panel]' name = panel>";
              echo "<input type='hidden' class = 'button' value= '$_POST[panel]' name = 'ShowTable'>";
              echo "</form>";
              echo "<script>document.getElementById('panel_update_success').submit();</script>";
            }
          }
          $sql .= "('$_POST[panel]',$cir,'S',0,'  ','$breaks','$_GET[jn]'),";
          $cir++;
        }
      }
      else
      {
        $error = addslashes($conn->error);
        echo "<script> alert('$error'); </script>";
      }
    }
  }
  ?>
  <script>
  //Creation buttons
  var create_dist = document.getElementById('create_dist');
  var close_dist = document.getElementById('close_dist');

  var create_three = document.getElementById('create_three');
  var close_three = document.getElementById('close_three');

  var create_single = document.getElementById('create_single');
  var close_single = document.getElementById('close_single');

  var create_isol = document.getElementById('create_isol');
  var close_isol = document.getElementById('close_isol');

  var create_ulta = document.getElementById('create_ulta');
  var close_ulta = document.getElementById('close_ulta');

  //Handle button calls.
  $('#disbut').click(function(){
    create_dist.style.display = "block";
  });
  close_dist.onclick = function() {
    create_dist.style.display = "none";
  }

  $('#three').click(function(){
    create_three.style.display = "block";
  });
  close_three.onclick = function() {
    create_three.style.display = "none";
  }

  $('#single').click(function(){
    create_single.style.display = "block";
  });
  close_single.onclick = function() {
    create_single.style.display = "none";
  }

  $('#iso').click(function(){
    create_isol.style.display = "block";
  });
  close_isol.onclick = function() {
    create_isol.style.display = "none";
  }

  $('#ukey').click(function(){
    create_ulta.style.display = "block";
  });
  close_ulta.onclick = function() {
    create_ulta.style.display = "none";
  }
  </script>
