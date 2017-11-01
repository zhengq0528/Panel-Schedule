<?php
include('PHP/Database/db.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PANEL SCHEDULE SYSTEM</title>
  <script src="js/jquery.js"></script>
  <link href="style.css?v=3" rel="stylesheet">
</head>
<body>
  <H2><CENTER> PANEL SCHEDULE SYSTEM</H2>
    <p><CENTER> Please Enter Job Number</P>
      <form method='get' id = "enter" action = "PHP/Panel/Interface.php">
        <Input list="productName" style="text-transform:uppercase" type="text" id = "LUIPanel" name = "jn" >
          <datalist id="productName">
            <?php
            $sqltx = "SELECT * FROM ps.project ORDER BY date DESC LIMIT 10";
            $resulttx = $conn->query($sqltx);
            while ($rowud = mysqli_fetch_array($resulttx)) {
              echo "<option value='$rowud[jn]'>$rowud[jn]</option>";
            }
            ?>
          </datalist>
          <!-- <input type = "text" name = "jn" value = ""> -->
          <input type = "Submit" value = "Open Project">
        </form>
        <br>
        <form action="DeleteProject.php" method="get" id="form1">
          <input type='hidden' value = 'Delete Project'>
        </form>

          <button id ='myBtn'>Create Project</button>
          <button id ='myBtn2'>Copy Existing Project</button>
          <button type="submit" form="form1" value="Submit">Delete Project</button>
        <br><br>
        <form method="post">
          <input type='submit' value = '2013' name ='year'>
          <input type='submit' value = '2014' name ='year'>
          <input type='submit' value = '2015' name ='year'>
          <input type='submit' value = '2016' name ='year'>
          <input type='submit' value = '2017' name ='year'>
          <input type='submit' value = 'Recent' name ='year'>
        </form>
        <h4 id='error'>
          <h4>
            <table style="width:500px;"border="1px solid black">

              <?php
              if(strcmp($_POST['year'],'Recent')==0 || empty($_POST['year']))
              {
                echo "<h3> 10 Latest Edited Project </h3>";
                $sql = "SELECT * FROM ps.project ORDER BY date DESC LIMIT 10";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc())
                {
                  echo "<tr>";
                  echo "<td> <a href='PHP/Panel/Interface.php?jn=$row[jn]'>$row[jn]</a></td>";
                  echo "<td> $row[jobdesc] </td>";
                  echo "<td> ".substr($row['date'],0,19)." </td>";
                  echo "</tr>";
                }
              }
              else
              {
                echo "<h3> All Projects In Year Of $_POST[year] </h3>";
                $str = $_POST['year'][2].$_POST['year'][3];
                $sql = "SELECT * FROM ps.project WHERE jn like '$str%'";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc())
                {
                  echo "<tr>";
                  echo "<td> <a href='PHP/Panel/Interface.php?jn=$row[jn]'>$row[jn]</a></td>";
                  echo "<td> $row[jobdesc] </td>";
                  echo "<td> ".substr($row[date],0,19)." </td>";
                  echo "</tr>";
                }

              }
              ?>
            </table>
          </body>
          </html>
          <?php  include('CreateProject.php'); ?>
          <?php  include('CopyExistingProject.php');?>

          <script src="script.js?v=1" type="text/javascript"></script>
          <script src="copyjn.js?v=1" type="text/javascript"></script>
