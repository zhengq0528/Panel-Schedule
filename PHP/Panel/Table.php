<script>
function SetClose()
{
  //document.getElementById("quan").innerHTML="NO GOOD";
   window.addEventListener('unload', function(event) {
    var id = document.getElementById('jid').value;
    var Jnum = document.getElementById("jjn").value;
    var host = document.getElementById("jhost").value;
    var panels = document.getElementById("panelJAVA").value;
    var dataString = 'id='+ id +'&jn='+Jnum+'&ppns='+panels+'&host='+host;
    $.ajax({
      type: "POST",
      url: "../Database/quitting.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
      },
      async:false
    });
  });
}
</script>
<?php
//That functions needs for drawing table content
include("../Tools/DrawTableContent.php");

//Displaying table. from here
if(isset($_POST['ShowTable'])){
  //Checking if someone else is using this panel right now
  $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $is_block = 0;
  $sql = "SELECT * FROM ps.tracklog WHERE panel = '$_POST[panel]' and jn = '$_GET[jn]' ORDER BY id DESC LIMIT 0, 1";
  $result = $conn->query($sql);
  $row = mysqli_fetch_array($result);
  if($row['state'] == 0 && !empty($row) && strcmp($hostname,$row['user'])!=0)
  {
    $is_block = 1;
    echo "<script>alert('User:$row[user] is editing this panel');</script>";
    //include('../Functions/Blocking.php');
  }
  else
  {
    echo "<script> SetClose(); </script>";
    $sql = "INSERT INTO ps.tracklog(jn,user,state,panel,note)
    VALUES('$_GET[jn]','$hostname','0','$_POST[panel]','$hostname is editing')";
    if($conn->query($sql))
    {
      $lastid = $conn->insert_id;
    }
    echo "<input type = 'hidden' id = 'jjn' value = '$_GET[jn]'/>";
    echo "<input type = 'hidden' id = 'jid' value = '$lastid'/>";
    echo "<input type = 'hidden' id = 'jhost' value = '$hostname'/>";
    echo "<input type = 'hidden' id = 'jpn' value = '$_POST[panel]'/>";
    echo "<input type = 'hidden' id = 'panelJAVA' value = '$_POST[panel]'/>";

  }


  $panelName = $_POST['panel'];
  echo "<form id='tloadp' method='post'>";
  echo "<input type='hidden' value ='$_POST[panel]' name = 'panel'>";
  echo "<input type='hidden' value= '$_POST[panel]' name = 'ShowTable'>";
  echo "</form>";
  $cur_panel = new panel($_GET['jn'],$_POST['panel']);
  $t_type = $cur_panel->get_intype();
  $circuit = $cur_panel->circuit;
  draw_edit_panel($_POST['panel'],'pedit','peclose',$cur_panel);
  include("../Libraries/TablePrepare.php");

  $cur_panel->header_name();
  $cur_panel->conn_deman_load($d1,$d2,$d3,$total2,$totalA);
  $cur_panel->keys($dataes,$keys,$keyDerating);
  echo "<input type='hidden' value='".$cur_panel->get_intype()."' id = 'types'>";
  echo "<input type='hidden' value='".$cur_panel->getminvolt()."' id = 'volt'>";
  echo "<input type='hidden' value='".$cur_panel->get3PH()."' id = '3volt'>";
  //Draw the table head, All the data will be posting here soon
  echo "<table border='0.5' class ='table-hover table-striped' width='100%'
  color='black' cellspacing='0.5' cellpadding='0.5' id = 'mydata'>";
  $cur_panel->create_thead();
  //Sums array, Will be using it later
  $sums = array(0 => 0,1 => 0,2 => 0,3 => 0,4 => 0,5 => 0);

  //All Content in TableContent
  include("../Tools/TableContent.php");

  echo "<input type='hidden' value = '$min' id='idmin'>";
  echo "<input type='hidden' value = '$max' id='idmax'>";
  //Update the connected load
  $ts1=$sums[0]+$sums[3];
  $ts2=$sums[1]+$sums[4];
  $ts3=$sums[2]+$sums[5];
  echo "<input type='hidden' value = '$ts1' id='ts1'>";
  echo "<input type='hidden' value = '$ts2' id='ts2'>";
  echo "<input type='hidden' value = '$ts3' id='ts3'>";
  $cur_panel->create_tfoot($sums);
  echo "</table>";
  echo "</div>";

  //Edit the circuit size
  include("..//Database/EditCircuitSize.php");
  if($is_block != 1)
  {
    echo "<script src='../../Scripts/EditTable.js?v=5'></script>";
  }
}

?>
<script>

var editpanel = document.getElementById('pedit');
var close_panel = document.getElementById('peclose');

var editcir = document.getElementById('ecs');
var close_editcir = document.getElementById('lecs');

//Handle button calls. if there is no panel selected, you need to be selecting one.
$('#upd').click(function(){
  if(!close_panel)
  {
    alert("You Have Not Select A Panel Yet!");
    return 0;
  }
  editpanel.style.display = "block";
});
if(close_panel)
close_panel.onclick = function() {
  editpanel.style.display = "none";
}

$('#cir').click(function(){
  if(editcir)
  {
    editcir.style.display = "block";
    close_editcir.onclick = function() {
      editcir.style.display = "none";
    }
  }
  else
  {
    alert("You Have Not Select A Panel Yet!");
    return 0;
  }
});

function prints()
{
  var printContents = document.getElementById('print').innerHTML;

  document.body.innerHTML = printContents;

  window.print();
  window.history.back();

}

//var printContents = document.getElementById('print').innerHTML;
//var originalContents = document.body.innerHTML;

//document.body.innerHTML = printContents;

//window.print();
//window.history.back();
//document.body.innerHTML = originalContents;
</script>
