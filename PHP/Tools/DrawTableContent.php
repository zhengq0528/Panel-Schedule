<?php

function CreateTabelCell($id,$type,$value)
{
  //Javascript/Ajax handling with Database. send post to database. and Modifiying it.
  $sid = $type .'_'.$id;
  $tid = $type .'_input_'.$id;
  echo "<td tabindex='0' id=$id class = '$type'><center>";
  if(empty($value))
  {
    echo "<span style='padding:0;margin:0;text-transform:uppercase; visibility: hidden' id=$sid class='text' value = '$value' >$value</span>";
  }
  else
  {
    echo "<span style='padding:0;margin:0;text-transform:uppercase' id=$sid class='text'>$value</span>";
  }
  echo "<input style='padding:0;margin:0;text-transform:uppercase' type='text' class = 'editbox' id=$tid value='$value' >";
  echo  "</td>";
}
function CreateTabelCelln($id,$type,$value)
{
  //Javascript/Ajax handling with Database. send post to database. and Modifiying it.
  $sid = $type .'_'.$id;
  $tid = $type .'_input_'.$id;
  echo "<td tabindex='0' id=$id class = '$type'><center>";
  if($value == 0)
  echo "<span style='padding:0;margin:0;text-transform:uppercase' id=$sid class='text'></span>";
  else
  echo "<span style='padding:0;margin:0;text-transform:uppercase' id=$sid class='text'>$value</span>";
  echo "<input style='padding:0;margin:0;text-transform:uppercase' type='text' onkeypress='return event.charCode >= 0 && event.charCode <= 64' class = 'editbox' id=$tid value='$value' >";
  echo  "</td>";
}

//Draw cell for loads, ABC
function CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code)
{
  $codes = array("p","P","t","T");
  if(in_array($code,$codes))
  {
      if($t_type == 1)
      {
        $pos = $circuit%4;
        if($pos==1 || $pos ==2)
        {
          echo "<td ><b><center>$watts1</td>";
          echo "<td bgcolor='#E0E0E0'><center></td>";
        }
        else if($pos==0 || $pos ==3)
        {
          echo "<td style='background:#E0E0E0;' bgcolor='#E0E0E0'><center></td>";
          echo "<td class = 'constant'><b><center>$watts2</td>";
        }
        //echo "<td bgcolor='#E0E0E0'><center></td>";
      }
      //If type 2: ThreePhase
      else if($t_type == 2)
      {
        $pos = $circuit%6;
        if($pos==1 || $pos ==2)
        {
          echo "<td class = 'constant'><b><center>$watts1</td>";
          echo "<td bgcolor='#E0E0E0'><center></td>";
          echo "<td bgcolor='#E0E0E0'><center></td>";
        }
        else if($pos==3 || $pos ==4)
        {
          echo "<td bgcolor='#E0E0E0'><center></td>";
          echo "<td class = 'constant'><b><center>$watts2</td>";
          echo "<td bgcolor='#E0E0E0'><center></td>";
        }
        else
        {
          echo "<td bgcolor='#E0E0E0'><center></td>";
          echo "<td bgcolor='#E0E0E0'><center></td>";
          echo "<td class = 'constant'><b><center>$watts3</td>";
        }
      }
      //If type rest: Distribution & Switchboard
      else
      {
        echo "<td class = 'constant'><b><center>$watts1</td>";
        echo "<td class = 'constant'><b><center>$watts2</td>";
        echo "<td class = 'constant'><b><center>$watts3</td>";
      }
  }
  else
  {
    //If type 1: Single Panel Mode
    if($t_type == 1)
    {
      $pos = $circuit%4;
      if($pos==1 || $pos ==2)
      {
        CreateTabelCelln($id,'watts1',$watts1);
        echo "<td bgcolor='#E0E0E0'><center></td>";
      }
      else if($pos==0 || $pos ==3)
      {
        echo "<td bgcolor='#E0E0E0'><center></td>";
        CreateTabelCelln($id,"watts2",$watts2);
      }
      //echo "<td bgcolor='#E0E0E0'><center></td>";
    }
    //If type 2: ThreePhase
    else if($t_type == 2)
    {
      $pos = $circuit%6;
      if($pos==1 || $pos ==2)
      {
        CreateTabelCelln($id,"watts1",$watts1);
        echo "<td bgcolor='#E0E0E0'><center></td>";
        echo "<td bgcolor='#E0E0E0'><center></td>";
      }
      else if($pos==3 || $pos ==4)
      {
        echo "<td bgcolor='#E0E0E0'><center></td>";
        CreateTabelCelln($id,"watts2",$watts2);
        echo "<td bgcolor='#E0E0E0'><center></td>";
      }
      else
      {
        echo "<td bgcolor='#E0E0E0'><center></td>";
        echo "<td bgcolor='#E0E0E0'><center></td>";
        CreateTabelCelln($id,"watts3",$watts3);
      }
    }
    //If type rest: Distribution & Switchboard
    else
    {
      CreateTabelCelln($id,"watts1",$watts1);
      CreateTabelCelln($id,"watts2",$watts2);
      CreateTabelCelln($id,"watts3",$watts3);
    }
  }
}

//Draw cell for loads, ABC
function CreateLoadCell1($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code)
{
  $codes = array("p","P","t","T");
  if(in_array($code,$codes))
  {
    echo "<td class = 'constant'><center>$watts1</td>";
    echo "<td class = 'constant'><center>$watts2</td>";
  }
  else
  {
    //If type 1: Single Panel Mode
    if($t_type == 1)
    {
      $pos = $circuit%4;
      if($pos==1 || $pos ==2)
      {
        CreateTabelCell($id,'watts1',$watts1);
        echo "<td bgcolor='#E0E0E0'><center></td>";
      }
      else if($pos==0 || $pos ==3)
      {
        echo "<td bgcolor='#E0E0E0'><center></td>";
        CreateTabelCell($id,"watts2",$watts2);
      }
    }
    //If type rest: Distribution & Switchboard
    else
    {
      CreateTabelCell($id,"watts1",$watts1);
      CreateTabelCell($id,"watts2",$watts2);
    }
  }
}
?>
