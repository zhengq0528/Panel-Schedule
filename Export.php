<?php

error_reporting(0);
include('PHP/Database/db.php');
include('Calc.php');
$pne = $_GET['pn'];
$jn = $_GET['jn'];
$l = RecursiveCalCulateConnectLoad($pne,$jn);
$d = RecursiveTL($pne,$jn);
$dataes = calculate($l[3],$d);
//echo "hello";
$ctotal = getTotal($pne);
$ratio = $dataes[0];
$total2 = $dataes[1];
$t1= $l[0] + $l[1] + $l[2];
$ratio = ($total2*1000/$t1);
$rtmp = $dataes[2];
$ltmp = $dataes[3];
$l[3] = $dataes[4];
$d1 = number_format((float)(($l[0]/1000)*$ratio),2,'.','');
$d2 = number_format((float)(($l[1]/1000)*$ratio),2,'.','');
$d3 = number_format((float)(($l[2]/1000)*$ratio),2,'.','');
$d = number_format((float)($total2),2,'.','');

$l[0] = $ctotal[0];
$l[1] = $ctotal[1];
$l[2] = $ctotal[2];
$c1 = number_format((float)($l[0]/1000),2,'.','');
$c2 = number_format((float)($l[1]/1000),2,'.','');
$c3 = number_format((float)($l[2]/1000),2,'.','');
$c = ($l[0] + $l[1] + $l[2])/1000;
$ca = 0;

$sql = "SELECT * FROM ps.circuit
WHERE jn = '$jn' and panel = '".$pne."'";
$result = $conn->query($sql);
$counter = 0;
$numResults = $result->num_rows;
$is_ulta = 0;
$cis = array();
while($row = $result->fetch_assoc()){
  if($row['circuit']<=0)
  {
    $is_ulta++;
  }
  $tmpc = array();
  if(empty($row['watts1'])) $row['watts1'] = "0";
  if(empty($row['watts2'])) $row['watts2'] = "0";
  if(empty($row['watts3'])) $row['watts3'] = "0";
  if(empty($row['uses'])) $row['uses'] = " ";
  if(empty($row['breaker'])) $row['breaker'] = " ";
  if(empty($row['fuse'])) $row['fuse'] = " ";
  $As = $row['watts1'];
  $Bs = $row['watts2'];
  $Cs = $row['watts3'];
  $row['code'] = strtoupper($row['code']);
  $tmpc += ['uses' => $row['code'].":".$row['uses']];
  $tmpc = array_merge($tmpc,GeneraStr($tps,$row['circuit'],$As,$Bs,$Cs));
  $tmpc += ['breaker' => $row['bftype']];
  $tmpc += ['fuse' => $row['fuse']];
  $tmpc += ['switch' => $row['breaker']];
  $cis += [$row['circuit'] => $tmpc];
}

$keys = array();
$keyDerating = array();
$sql2 = "SELECT * FROM ps.keys WHERE jn ='$_GET[jn]'";
$keyf = array();
$result1 = $conn->query($sql2);
while($row = mysqli_fetch_array($result1))
{

  $keys +=[strtoupper("$row[keyname]") => ":".$row['description']];
  $keyDerating +=[strtoupper($row['keyname']) => $row['derating']];

}
foreach($l[3] as $key => $value) {

  $a = ($value * ($keyDerating[$key]/100)) /1000;
  $a = number_format((float)($a),2,'.','');
  $keyf += [$key => $a];
  if($keyDerating[$key]==0) $keyDerating[$key] = 100;
  if(empty($keys[$key])) $keys[$key] = " ";
  $keyf += [$keys[$key] => $keyDerating[$key]."%"];
}



$sql = "SELECT * FROM ps.panel WHERE panel = '$pne' and jn = '$_GET[jn]'";
$resultMain = $conn->query($sql);
$row = mysqli_fetch_array($resultMain);
$ph = get3PHVOLTSe($row['service']);
if(strcmp($row['type'],"SinglePhase")==0)
{
  $ca = ($c*1000) / ($ph);
  $da = ($d*1000) / ($ph);
  $ca = number_format((float)($ca),2,'.','');
  $da = number_format((float)($da),2,'.','');
}
else
{
  $ca = ($c*1000) / (sqrt(3)*$ph);
  $da = ($d*1000) / (sqrt(3)*$ph);
  $ca = number_format((float)($ca),2,'.','');
  $da = number_format((float)($da),2,'.','');
}

$tps = CompareType($row['type']);

if($row['fuse'] == 1)
{
  $tps = 5;
}
if($is_ulta > 0 )
{
  if($tps == 1) $tps = 6;
  else if($tps == 2) $tps =7;
}

$arr = array('type' => $tps,
'info' => array(
  'Job Number' => "$_GET[jn]",
  'panel' => "$pne",
  'location'=>"$row[location]",
  'service'=>"$row[service]",
  'neutral bus'=>"$row[neutralbus]",
  'main breaker'=>"$row[breaker]",
  'groundbus'=>"$row[groundbus]",
  'scr'=>"$row[rating]",
  'Main Lug'=>"$row[lug]",
  'Note'=>"$row[notes]",
  'mounting'=>"$row[mounting]")
);
$connload = array(
  'connected load'=>array(
    'phase a'=>$c1,
    'phase b'=>$c2,
    'phase c'=>$c3,
    'total'  =>$c,
    'AMPS' => $ca
  )
);
$demaload = array(
  'demand load'=>array(
    'phase a'=>$d1,
    'phase b'=>$d2,
    'phase c'=>$d3,
    'total'  =>$d,
    'AMPS' => $da
  )
);

$keys = array('keys'=>$keyf);
$circuitj = array('table'=>$cis);
$arr = array_merge($arr,$connload);
$arr = array_merge($arr,$demaload);
$arr = array_merge($arr,$keys);
$arr = array_merge($arr,$circuitj);
//header('Content-type: text/javascript');
echo json_encode($arr,JSON_PRETTY_PRINT);


function GeneraStr($type,$circuit,$Ae,$Be,$Ce)
{
  if($type ==1 || $type ==6)
  {
    if(($circuit % 4) == 1 || ($circuit % 4) == 2)
    {
      return array('A'=>$Ae);
    }
    else
    {
      return array('B'=>$Be);
    }
  }
  else if($type == 2 || $type ==7)
  {
    if(($circuit % 6) == 1||($circuit % 6) == 2)
    {
      return array('A'=>$Ae);
    }
    else if(($circuit % 6) == 3|| ($circuit % 6) == 4)
    {
      return array('B'=>$Be);
    }
    else
    {
      return array('C'=>$Ce);
    }
  }
  else if($type == 4)
  {
    return array('A'=>$Ae);
  }
  else {
    return array('A'=>$Ae,
    'B'=>$Be,
    'C'=>$Ce);
  }
}
//Get 3PH volt from service
function get3PHVOLTSe($Service)
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

function get_string_between($string, $start, $end){
  //Helper function for get3PHvolts
  $string = ' ' . $string;
  $ini = strpos($string, $start);
  if ($ini == 0) return '';
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}
/*
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
*/
function getTotal($panel)
{
  include('PHP/Database/db.php');
  $sql = "SELECT * FROM ps.circuit
  WHERE jn= '$_GET[jn]' and panel = '$panel'";
  $result = $conn->query($sql);
  $arr1 =0;
  $arr2 =0;
  $arr3 =0;
  while($row = $result->fetch_assoc()) {
    $arr1 += $row['watts1'];
    $arr2 += $row['watts2'];
    $arr3 += $row['watts3'];

  }
  $arr = array($arr1,$arr2,$arr3);
  return $arr;
}
?>
