<?php
class panel{
  var $job;       //Job Number of the panel scheduler
  var $name;      //Panel Name
  var $service;   //Service For Panel
  var $location;  //Location of the Panel
  var $circuit;   //Circuit Size
  var $mounting;  //Whether Mounting Or Not
  var $breaker;   //Breakers
  var $lugs;      //Main Lugs
  var $bus;       //Ground Bus
  var $rating;    //Short Circuit Rating
  var $note;      //Note of Panel
  var $type;      //Type of Panel
  var $fuse;      //Fuse or switch
  var $transize;  //Transformer Size
  var $secvol;    //Secondary Voltage
  var $monitor;   //Line Isolation Monitor
  var $exist;     //Check if the panel exist
  //Creating a Panel
  //Late on will create different type of panel by adopting this
  function __construct($jn,$name){
    include ('../Database/db.php');   //Connecting to the Database
    $result = $conn->query("SELECT * FROM ps.panel WHERE jn = '$jn' and panel = '$name'");
    $row = mysqli_fetch_array($result);
    //Getting all useful info from database base on initial user typed in
    $this->job = $jn;
    $this->name = $name;
    if(empty($row))
    $this->exist = 0;
    else
    $this->exist = 1;
    //Storing data from mysql
    $this->type = $row['type'];
    $this->service = $row['service'];
    $this->location = $row['location'];
    $this->circuit = $row['circuit'];
    $this->mounting = $row['mounting'];
    $this->breaker = $row['breaker'];
    $this->lugs = $row['lug'];
    $this->bus = $row['groundbus'];
    $this->rating = $row['rating'];
    $this->note = $row['notes'];
    $this->fuse = $row['fuse'];
    $this->transize = $row['transize'];
    $this->secvol = $row['secvol'];
    $this->monitor = $row['monitor'];
  }

  function header_name(){
    //Header of the panel
    echo "<h1 style='position:relative;left:20px'>PANEL DATA SCHEDULE </h1>";
    echo "<h1 style='position:relative;top:-59px;left:625px'>PANEL NAME: $this->name </h1>";
    $celllength = "150px";
    //Display the header information of the panel, this should be good
    echo "<table style='position:relative; top:-50px; left:20px;'>";
    echo "<tr><td>Job Number:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center> $this->job</center> </td></tr>";
    echo "<tr><td>Service:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center> $this->service </center></td></tr>";
    echo "<tr><td>3 Phase Voltage:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>".$this->get3Ph()."</center> </td></tr>";
    echo "<tr><td>Location:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>$this->location</center> </td></tr>";
    echo "<tr><td>Mounting:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>$this->mounting</center> </td></tr>";
    echo "<tr><td>Main Breaker:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>$this->breaker</center> </td></tr>";
    echo "<tr><td>Main Lugs:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>$this->lugs</center> </td></tr>";
    echo "<tr><td>Ground bus:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>$this->bus</center> </td></tr>";
    echo "<tr><td>Short Circuit Rating:</td>
    <td width=$celllength style='border-bottom: 1px solid #000;'> <center>$this->rating</center> </td></tr>";
    echo "</table>";

  }
  function conn_deman_load($d1,$d2,$d3,$t,$ta)
  {
    $demand_loadheight = '-416px';
    //Draw connected load and Demand Load
    echo "<table style='position:absolute; top:70px; left:350px;' class='inlineTable'>";
    echo "<th colspan='2'style='font-size:22px;'><center> Connected Load <br>&nbsp;<center></th>";
    //echo "<tr><h3><center>CONNECTED LOAD</center></h3></tr>";
    echo "<tr><td>PHASE A:</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;' id='c1'> <center> </center> </td></tr>";
    echo "<tr><td>PHASE B:</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;' id='c2'> <center> ---- KVA</center> </td></tr>";
    if($this->get_intype() != 1 && $this->get_intype() != 4)
    {
      echo "<tr><td>PHASE C:</td>";
      echo "<td width=150px style='border-bottom: 1px solid #000;' id='c3'> <center> ---- KVA</center> </td></tr>";
    }
    echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
    echo "<tr><td>Total:</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;' id='ct'> <center> ---- KVA</center> </td></tr>";
    echo "<tr><td>&nbsp;</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;' id='cta'> <center> ---- AMPS</center> </td></tr>";
    if($this->get_intype() == 4)
    {
      echo "<tr><td>Transformer Size:</td>";
      echo "<td width=150px style='border-bottom: 1px solid #000;' id='cta'> <center> $this->transize </center> </td></tr>";
      echo "<tr><td>Secondary Voltage</td>";
      echo "<td width=150px style='border-bottom: 1px solid #000;' id='cta'> <center> $this->secvol </center> </td></tr>";
    }
    echo "</table>";

    //446
    //Draw connected load and Demand Load
    echo "<table style='position:absolute; top:70px; left:630px;' class='inlineTable'>";
    echo "<th colspan='2'style='font-size:22px;'><center> Demand Load <br>&nbsp;<center></th>";
    //echo "<tr><h3><center>CONNECTED LOAD</center></h3></tr>";
    echo "<tr><td>PHASE A:</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> $d1 KVA</center> </td></tr>";
    echo "<tr><td>PHASE B:</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> $d2 KVA</center> </td></tr>";
    if($this->get_intype() != 1 && $this->get_intype() != 4)
    {
      echo "<tr><td>PHASE C:</td>";
      echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> $d3 KVA</center> </td></tr>";
    }
    echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
    echo "<tr><td>Total:</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> $t KVA</center> </td></tr>";
    echo "<tr><td>&nbsp;</td>";
    echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> $ta AMPS</center> </td></tr>";
    if($this->get_intype() == 4)
    {
      echo "<tr><td>Line Isolation Monitor</td>";
      echo "<td width=150px style='border-bottom: 1px solid #000;'> <center>$this->monitor</center> </td></tr>";
    }
    echo "</table>";
  }
  function keys($dataes,$keys,$keyDerating)
  {
    $light = $dataes[3];
    $recp = $dataes[2];
    echo "<table style='position:absolute; top:70px; left:920px;' class='inlineTable'>";
    echo "<th colspan='2'style='font-size:22px;'><center> Keys <br>&nbsp;<center></th>";
    foreach($dataes[4] as $key => $value) {
      if(strcmp($key,"L")==0)
      {
        echo "<tr><td>[$key] $keys[$key]</td>";
        echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> ".number_format((float)($light/1000),2,'.','')." KVA</center> </td></tr>";
      }
      else if(strcmp($key,"R")==0)
      {
        echo "<tr><td>[$key] $keys[$key]</td>";
        echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> ".number_format((float)($recp/1000),2,'.','')." KVA</center> </td></tr>";
      }
      else
      {
        echo "<tr><td>[$key] $keys[$key]</td>";
        echo "<td width=150px style='border-bottom: 1px solid #000;'> <center> ".number_format((float)($value/1000),2,'.','')." KVA</center> </td></tr>";
      }
    }
    echo "</table>";
    echo "<h5 style='width: 1050px; display: table; position: relative;left:20px; top:-20px;'>";
    echo "<span style='display: table-cell; width:50px;' id ='type' value='lol'>Note:</span>";
    echo "<span style='display: table-cell; border-bottom: 1px solid black;'>".$this->note."</span>";
    echo "</h5>";
  }
  function create_thead()
  {
    if($this->fuse == 1)
    {
      echo "<th width='4%' ><center></th>";
      echo "<th width='15%'><center>CIRCUIT USE</th>";
      echo "<th width='5%' ><center>A</th>";
      echo "<th width='5%' ><center>LOAD <BR>B</th>";
      echo "<th width='5%' ><center>C</th>";
      echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
      echo "<th width='6%' ><center>CIRCUIT <BR>SWITCH</th>";
      echo "<th width='6%' ><center>CIRCUIT <BR>FUSE</th>";
      echo "<th width='6%' ><center>CIRCUIT <BR>SWITCH</th>";
      echo "<th width='6%' ><center>CIRCUIT <BR>FUSE</th>";
      echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
      echo "<th width='5%' ><center>A</th>";
      echo "<th width='5%' ><center>LOAD <BR>B</th>";
      echo "<th width='5%' ><center>C</th>";
      echo "<th width='4%' ><center></th>";
      echo "<th width='15%'><center>CIRCUIT USE</th>";
    }
    else
    {
      if($this->get_intype() != 1 && $this->get_intype() !=4)
      {
        echo "<th width='4%' ><center></th>";
        echo "<th width='15%'><center>CIRCUIT USE</th>";
        echo "<th width='5%' ><center>A</th>";
        echo "<th width='5%' ><center>LOAD <BR>B</th>";
        echo "<th width='5%' ><center>C</th>";
        echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
        echo "<th width='12%' ><center>CIRCUIT <BR>BREAKER</th>";

        echo "<th width='12%' ><center>CIRCUIT <BR>BREAKER</th>";
        echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
        echo "<th width='5%' ><center>A</th>";
        echo "<th width='5%' ><center>LOAD <BR>B</th>";
        echo "<th width='5%' ><center>C</th>";
        echo "<th width='4%' ><center></th>";
        echo "<th width='15%'><center>CIRCUIT USE</th>";
      }
      else if($this->get_intype() == 1)
      {
        echo "<th width='4%' ><center></th>";
        echo "<th width='18%'><center>CIRCUIT USE</th>";
        echo "<th width='5%' ><center>L1</th>";
        echo "<th width='5%' ><center>L2</th>";
        echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
        echo "<th width='14%' ><center>CIRCUIT <BR>BREAKER</th>";

        echo "<th width='14%' ><center>CIRCUIT <BR>BREAKER</th>";
        echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
        echo "<th width='5%' ><center>L1</th>";
        echo "<th width='5%' ><center>L2</th>";
        echo "<th width='4%' ><center></th>";
        echo "<th width='18%'><center>CIRCUIT USE</th>";
      }
      else if($this->get_intype() ==4)
      {
        echo "<th width='4%' ><center></th>";
        echo "<th width='20%'><center>CIRCUIT USE</th>";
        echo "<th width='7%' ><center>L1</th>";
        echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
        echo "<th width='15%' ><center>CIRCUIT <BR>BREAKER</th>";

        echo "<th width='15%' ><center>CIRCUIT <BR>BREAKER</th>";
        echo "<th width='4%' ><center>CCT.<BR>NO.</th>";
        echo "<th width='7%' ><center>L1</th>";
        echo "<th width='4%' ><center></th>";
        echo "<th width='20%'><center>CIRCUIT USE</th>";
      }
    }
  }
  //Drawing tfoot here
  function create_tfoot($sums){
    echo "<tfoot>";
    if($this->fuse == 1)
    {
      echo " <th> </th>
      <th>SUBTOTALS</th>
      <th id = 's1'><center>$sums[0]</th>
      <th id = 's2'><center>$sums[1]</th>
      <th id = 's3'><center>$sums[2]</th>
      <th><center></th>
      <th><center></th><th><center></th>
      <th><center></th><th><center></th>
      <th><center></th>
      <th id = 's4'><center>$sums[3]</th>
      <th id = 's5'><center>$sums[4]</th>
      <th id = 's6'><center>$sums[5]</th>
      <th><center></th>
      <th>SUBTOTALS</th>";
    }
    else
    {
      if($this->get_intype() != 1 && $this->get_intype() !=4)
      {
        echo " <th> </th>
        <th>SUBTOTALS</th>
        <th id = 's1'><center>$sums[0]</th>
        <th id = 's2'><center>$sums[1]</th>
        <th id = 's3'><center>$sums[2]</th>
        <th><center></th>
        <th><center></th>
        <th><center></th>
        <th><center></th>
        <th id = 's4'><center>$sums[3]</th>
        <th id = 's5'><center>$sums[4]</th>
        <th id = 's6'><center>$sums[5]</th>
        <th><center></th>
        <th>SUBTOTALS</th>";
      }
      else if($this->get_intype() == 1)
      {
        echo "<th> </th>
        <th>SUBTOTALS</th>
        <th id = 's1'><center>$sums[0]</th>
        <th id = 's2'><center>$sums[1]</th>
        <th><center></th>
        <th><center></th>
        <th><center></th>
        <th><center></th>
        <th id = 's4'><center>$sums[3]</th>
        <th id = 's5'><center>$sums[4]</th>
        <th><center></th>
        <th>SUBTOTALS</th>";
      }
      else if($this->get_intype() ==4)
      {
        echo "<th> </th>
        <th>SUBTOTALS</th>
        <th id = 's1'><center>$sums[0]</th>
        <th><center></th>
        <th><center></th>
        <th><center></th>
        <th><center></th>
        <th id = 's4'><center> $sums[3]</th>
        <th><center></th>
        <th>SUBTOTALS</th>";
      }
    }
    echo "</tfoot>";
  }
  //Functions, Calls and Calculations
  function get_intype(){
    //Get type as integer 1 = singlephase 2 = threephase 3 = Distribution 4 = isolation
    if(strcmp($this->type,"SinglePhase") ==0)
    return 1;
    else if(strcmp($this->type,"ThreePhase")==0)
    return 2;
    else if(strcmp($this->type,"Isolation")==0)
    return 4;
    else
    return 3;
  }

  function getminvolt(){
    //Special Case For Someone
    if(!empty(strpos($this->service,'/')))
    {
      $arr = explode("/",$this->service);
      return $arr[0];
    }
    else
    {
      $arr = explode("V",$this->service);
      return $arr[0];
    }
  }

  function get3PH(){
    //Parsing out the 3PH VOLTAGE
    if(!empty(strpos($this->service,'/')))
    {
      $string =' '.$this->service;
      $ini = strpos($string, '/');
      if ($ini == 0) return '';
      $ini += strlen('/');
      $len = strpos($string, 'V', $ini) - $ini;
      return substr($string, $ini, $len);
    }
    else
    {
      $arr = explode("V",$this->service);
      return $arr[0];
    }
  }

  //Get Datas from class
  function get_job(){
    return $this->job;
  }
  function get_name(){
    return $this->name;
  }
  function get_service(){
    return $this->service;
  }
  function get_location(){
    return $this->location;
  }
  function get_circuit(){
    return $this->circuit;
  }
  function get_mounting(){
    return $this->mounting;
  }
  function get_breaker(){
    return $this->breaker;
  }
  function get_lugs(){
    return $this->lugs;
  }
  function get_bus(){
    return $this->bus;
  }
  function get_rating(){
    return $this->rating;
  }
  function get_note(){
    return $this->note;
  }
  function get_type(){
    return $this->type;
  }
  function is_exists(){
    return $this->exist;
  }
}

?>
