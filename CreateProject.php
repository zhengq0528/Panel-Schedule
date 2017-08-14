<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center>
      <H2>Creating A New Project</H2>
      <form action="#" method='post'>
        <table border="0" class ="table  table-hover"  id = "mydata">
          <tr>
            <td> Job Number: </td>
            <td> <input type="text"  name ="jn"> </td>
          </tr>
          <tr>
            <td> Job Description: </td>
            <td> <input type="text"  name ="jd"> </td>
          </tr>
          <tr>
            <td> Building Type: </td>
            <td> <select name='type'>
              <option value='5'>All Others</option>
              <option value='1'>Dwelling Units</option>
              <option value='2'>Hospitals</option>
              <option value='3'>Hotels and Motels</option>
              <option value='4'>Warehouses</option>
            </select>
          </td>
        </tr>
      </table><br>
      <input type='submit' name='add' value="Create">
    </form>
  </center>
</div>
</div>

<div id="myModal1" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center>
      <H2>Updating A Project</H2>
      <form action="#" method='post'>
        <table border="0" class ="table  table-hover"  id = "mydata">
          <tr>
            <td> Job Number: </td>
            <td> <input type="text"  name ="jn"> </td>
          </tr>
          <tr>
            <td> Job Description: </td>
            <td> <input type="text"  name ="jd"> </td>
          </tr>
          <tr>
            <td> Building Type: </td>
            <td> <select name='type'>
              <option value='5'>All Others</option>
              <option value='1'>Dwelling Units</option>
              <option value='2'>Hospitals</option>
              <option value='3'>Hotels and Motels</option>
              <option value='4'>Warehouses</option>
            </select>
          </td>
        </tr>
      </table><br>
      <input type='submit' name='upd' value="update">
    </form>
  </center>
</div>
</div>
<?php
if(isset($_POST['add']))
{
  $jn = $_POST['jn'];
  $des = $_POST['jd'];
  $type = $_POST['type'];
  if(empty($_POST['jn']))
  {
    echo "<input type='hidden' value='Creation Failed <br> Invalid Input' id='message'>";
  }
  else
  {
    $des = addslashes($des);
    $sql = "INSERT INTO ps.project(jn,jobdesc,type)
    VALUES ('$jn', '$des', $type)";
    if ($conn->query($sql) === TRUE) {
      $keys = array(
        "L" => "Light",    "R" => "Receptacle",    "M" => "Motors",
        "E" => "Extra",    "X" => "Space",    "S" => "Spare");
        foreach($keys as $key => $value)
        {
          if(strcmp($key,"M") == 0)
          {
            $sql1 = "INSERT INTO ps.keys(jn,keyname,description,derating) VALUES ('$jn','$key','$value','125')";
          }
          else
          {
            $sql1 = "INSERT INTO ps.keys(jn,keyname,description,derating) VALUES ('$jn','$key','$value','100')";
          }
          $conn->query($sql1);
        }
        //echo "good";
        echo "<input type='hidden' value='You successfully Create A Job: '$jn' id='message'>";
      }
      else{
        echo "<input type='hidden' value='Creation Failed<br> Job Number $jn Already Exist' id='message'>";
      }
    }
    ?>
    <script type="text/javascript">
    document.getElementById("error").innerHTML = document.getElementById("message").value;
    </script>
    <?php
  }
  ?>
