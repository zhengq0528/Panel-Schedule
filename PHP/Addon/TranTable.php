<?php
function CreateTranCell($id,$type,$value)
{
  //Get which is which, and importing to javascript.
  //Javascript/Ajax handling with Database. send post to database. and Modifiying it.
  $sid = $type .'_'.$id;
  $tid = $type .'_input_'.$id;
  echo "<td tabindex='0' id=$id class = '$type'><center>";
  echo "<span id=$sid class='text'>$value</span>";
  echo "<input  type='text' class = 'editbox' id=$tid value='$value' >";
  echo  "</td>";
}
//Get 3PH volt from service
function get3s($Service)
{
  $contain;
  if(strpos($Service,'/')!==false)
  {
    $contain = true;
  }
  if($contain)
  {
    return get_string_between($Service,'/','V');
  }
  else
  {
    $arr = explode("V",$Service);
    return $arr[0];
  }
}
if(isset($_POST['tf']))
{

  echo "<div style='position:absolute; top:200px; left:200px;'>";
  echo "<center><h1>Transformer Schedule</h1></center>";
  ?>
  <table style="margin-left:100px;" width = "800" height = "auto" class ="table-bordered table-hover">
    <thead>
      <tr>
          <th width = "70"><center>TRANSF.NO.</th>
          <th width = "50"><center>KVA</th>
          <th width = "80"><center>SOURCE</th>
          <th width = "80"><center>PRIMARY<BR>VOLTS</th>
          <th width = "80"><center>DESTINATION</th>
          <th width = "80"><center>SECONDARY<BR>VOLTS</th>
          <th width = "60"><center>GRND WIRE<BR>APPVD GRND</th>
          <th><center>REMARKS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * from ps.transformer WHERE jn = '$_GET[jn]'";
          $result = $conn->query($sql);
          while($row = mysqli_fetch_array($result))
          {
            echo "<tr>";
            echo "<td class = 'constant'><center>$row[name]</td>";
            CreateTranCell($row['id'],'rating',$row['rating']);
            //echo "<td class = 'constant'><center>$row[rating]</td>";
            echo "<td class = 'constant'><center>$row[source]</td>";
            $r = new panel($_GET['jn'],$row['source']);
            $a = $r->get3PH();
            echo "<td class = 'constant'><center>$a</td>";
            echo "<td class = 'constant'><center>$row[destination]</td>";
            $r = new panel($row['djn'],"$row[destination]");
            $a = $r->get3PH();
            //".get3s($rows['service'])."
            echo "<td class = 'constant'><center>$a</td>";
            echo "<td class = 'constant'><center>NA</td>";
            CreateTranCell($row['id'],'remark',$row['remark']);
            //CreateKeyCell($row['id'],'derating',$row['derating']);
            echo "</tr>";
          }
           ?>
        </tbody>
      </table>
  <?php
  echo "</div>";
}

?>
<script>
$(document).ready(function()
{

  $(".editbox").mouseup(function()
  {
    return false
  });

  // Outside click action
  $(document).mouseup(function()
  {
    $(".editbox").hide();
    $(".text").show();
  });


  $(".rating").click(function()
  {
    var ID=$(this).attr('id');
    $("#rating_"+ID).hide();
    $("#rating_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#rating_input_"+ID).val();
    var dataString = 'id='+ ID +'&rating='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/edittran.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#rating_"+ID).html(uses);
      }
    });
  });

  $(".remark").click(function()
  {
    var ID=$(this).attr('id');
    $("#remark_"+ID).hide();
    $("#remark_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#remark_input_"+ID).val();
    var dataString = 'id='+ ID +'&remark='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/edittran.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#remark_"+ID).html(uses);
      }
    });
  });

});
</script>
