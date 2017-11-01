<div id="copy" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span id = 'copyclose' class="close">&times;</span>
    <center>
      <form action="#" method='post'>
        <table border="0" class ="table  table-hover"  id = "mydata">
          <tr>
            <td> From Job Number: </td>
            <td> <input type="text"  name ="fjn"> </td>
          </tr>
          <tr>
            <td> New Job Number: </td>
            <td> <input type="text"  name ="njn"> </td>
          </tr>
        </table>
        <input type='submit' name='copy' value="Copy">
      </form>
    </center>
  </div>
</div>
<?php
if(isset($_POST['copy']))
{
  include('PHP/Database/db.php');

  $sql = "SELECT * from ps.project where jn = '$_POST[fjn]'";
  //$sql = addslashes($sql);
  $result = $conn->query($sql);

  $row = $result->fetch_assoc();

  if(empty($_POST['njn']) || empty($_POST['fjn']))
  {
    echo "<h1 style'color:red'>Your Input is Empty, Please Enter Jon Numbers</h1>";
  }
  else if(empty($row))
  {
    echo "<h1 style'color:red'>JOB NUMBER $_POST[fjn] DOES NOT EXIST!</h1>";
  }
  else
  {

    $sql = "INSERT INTO ps.project(jn,jobdesc,type)
    VALUES ('$_POST[njn]', '$row[jobdesc]', '$row[type]')";
    //$sql = addslashes($sql);
    if ($conn->query($sql) === TRUE) {

      $sqlkey = "SELECT * from ps.keys where jn = '$_POST[fjn]'";
      //$sqlkey = addslashes($sqlkey);
      $resultk = $conn->query($sqlkey);
      while($rowk = $resultk->fetch_assoc())
      {
        $rowk['description'] = addslashes($rowk['description']);
        $sqlk = "INSERT INTO ps.keys(jn,keyname,description,derating)
        VALUES ('$_POST[njn]','$rowk[keyname]','$rowk[description]','$rowk[derating]')";
        //$sqlk = addslashes($sqlk);
        $conn->query($sqlk);
      }

      $sqltr = "SELECT * from ps.transformer where jn = '$_POST[fjn]'";
      //$sqltr = addslashes($sqltr);
      $resulttr = $conn->query($sqltr);

      while($rowt = $resulttr->fetch_assoc())
      {
        $sqlt = "INSERT INTO ps.transformer(jn,rating,source,svolt,dvolt,destination,phases,remark,loadtype,loss
          ,djn,name,service)
          VALUES ('$_POST[njn]','$rowt[rating]','$rowt[source]','$rowt[svolt]','$rowt[dvolt]',
            '$rowt[destination]','$rowt[phases]','$rowt[remark]','$rowt[loadtype]','$rowt[loss]',
            '$rowt[djn]','$rowt[name]','$rowt[service]')";
            //$sqlt = addslashes($sqlt);
            $conn->query($sqlt);
          }


          $sqlpanel = "SELECT * from ps.panel where jn = '$_POST[fjn]'";
          //$sqlpanel = addslashes($sqlpanel);
          $resultp = $conn->query($sqlpanel);
          while($rowp = $resultp->fetch_assoc()){
            if(empty($rowp['rating'])) $rowp['rating']=0;
            if(empty($rowp['aicrating'])) $rowp['aicrating']=0;
            $sqlp = "INSERT INTO ps.panel
            (jn,panel,type,location,circuit,service,mounting,rating,
              breaker,groundbus,fuse,notes,transize,secvol,monitor,lug)
              VALUES ('$_POST[njn]', '$rowp[panel]', '$rowp[type]', '$rowp[location]'
                , '$rowp[circuit]', '$rowp[service]', '$rowp[mounting]', '$rowp[rating]'
                , '$rowp[breaker]', '$rowp[groundbus]' , '$rowp[fuse]', '$rowp[notes]'
                , '$rowp[transize]', '$rowp[secvol]', '$rowp[monitor]', '$rowp[lug]')";
                //$sqlp = addslashes($sqlp);
                $conn->query($sqlp);
                //echo "hello".$conn->error;
              }

              $sqlc = "SELECT * from ps.circuit where jn = '$_POST[fjn]'";
              //$sqlc = addslashes($sqlc);
              $resultc = $conn->query($sqlc);
              while($rowc = $resultc->fetch_assoc())
              {
                if(empty($rowc['watts1'])) $rowc['watts1']=0;
                if(empty($rowc['watts2'])) $rowc['watts2']=0;
                if(empty($rowc['watts3'])) $rowc['watts3']=0;
                if(empty($rowc['derating'])) $rowc['derating']=0;
                if(empty($rowc['linkphase'])) $rowc['linkphase']=0;
                if(empty($rowc['fuse'])) $rowc['fuse']=0;

                if(strcasecmp($rowc['phase'],$rowc['jn'])==0)
                {
                  $sqlcc = "INSERT INTO ps.circuit (jn,phase,panel,circuit,code,uses,
                    breaker,fuse,bftype,watts1,watts2,watts3,derating,linkpanel,linkphase,linktrans,
                    type)
                    VALUES('$_POST[njn]','$_POST[njn]','$rowc[panel]','$rowc[circuit]','$rowc[code]',
                      '$rowc[uses]','$rowc[breaker]','$rowc[fuse]','$rowc[bftype]','$rowc[watts1]',
                      '$rowc[watts2]','$rowc[watts3]','$rowc[derating]','$rowc[linkpanel]','$rowc[linkphase]',
                      '$rowc[linktrans]','$rowc[type]')";
                  }
                    else
                  {
                      $sqlcc = "INSERT INTO ps.circuit (jn,phase,panel,circuit,code,uses,
                        breaker,fuse,bftype,watts1,watts2,watts3,derating,linkpanel,linkphase,linktrans,
                        type)
                        VALUES('$_POST[njn]','$rowc[phase]','$rowc[panel]','$rowc[circuit]','$rowc[code]',
                          '$rowc[uses]','$rowc[breaker]','$rowc[fuse]','$rowc[bftype]','$rowc[watts1]',
                          '$rowc[watts2]','$rowc[watts3]','$rowc[derating]','$rowc[linkpanel]','$rowc[linkphase]',
                          '$rowc[linktrans]','$rowc[type]')";
                    }
                    //$sqlcc = addslashes($sqlcc);
                        $conn->query($sqlcc);

                    }
                    }
                    else{
                      echo "<h1 style'color:red'>Creation Failed<br> Job Number $jn Already Exist </h1>";
                    }

                    echo "<input type='hidden' value = '$_POST[njn]' id ='cjn'>";
                   ?>
                   <script type="text/javascript">
                   document.getElementById('LUIPanel').value = document.getElementById('cjn').value;
                   document.getElementById('enter').submit();
                   //document.getElementById("error").innerHTML = document.getElementById("message").value;
                   </script>
                   <?php
                  }
                }
                ?>
