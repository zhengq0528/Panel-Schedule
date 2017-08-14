<?php
//Display each circuits
$sql = "SELECT * FROM ps.circuit WHERE jn = '$_GET[jn]' and panel = '".$cur_panel->get_name()."'";
$result = $conn->query($sql);
$codes = array('T','t','P','p');
$min = 99999999;
$max = 0;
while($row = $result->fetch_assoc())
{
  $tmp = $row['id'];
  if($tmp < $min) $min = $tmp;
  if($tmp > $max) $max = $tmp;
  $id = $row['id']; $bftype =$row['bftype']; $circuit = $row['circuit']; $code = $row['code'];
  $watts3 = $row['watts3']; $watts2 = $row['watts2']; $watts1 = $row['watts1']; $uses = $row['uses'];
  $fuse = $row['fuse']; $switch = $row['breaker'];
  $t_type = $cur_panel->get_intype();
  //Draw the header when there is ulta panel
  if($row['circuit'] < 0)
  {
    if($row['circuit'] % 2 == 0)
    {
      echo "<tr><td bgcolor='#E0E0E0'></td>";
      echo "<td bgcolor='#E0E0E0'></td>";echo "<td bgcolor='#E0E0E0'></td>";echo "<td bgcolor='#E0E0E0'></td>";
      if($cur_panel->get_intype() ==2)
      echo "<td bgcolor='#E0E0E0'></td>";
      echo "<td bgcolor='#E0E0E0'></td>";echo "<td bgcolor='#E0E0E0'><center>$row[bftype]</center></td>";
    }
    else
    {
      echo "<td bgcolor='#E0E0E0'><center>$row[bftype]</center></td>";echo "<td bgcolor='#E0E0E0'></td>";  echo "<td bgcolor='#E0E0E0'></td>";
      if($cur_panel->get_intype() == 2)
      echo "<td bgcolor='#E0E0E0'></td>";
      echo "<td bgcolor='#E0E0E0'></td>";  echo "<td bgcolor='#E0E0E0'></td>";  echo "<td bgcolor='#E0E0E0'></td>";echo "</tr>";
    }
  }
  else
  {
    if($cur_panel->get_intype() == 3 || $cur_panel->get_intype() == 2)
    {
      if($circuit % 2 != 0)
      {
        echo "<tr>";
        $sums[0] += $watts1; $sums[1] += $watts2; $sums[2] += $watts3;
        if(in_array($code, $codes))
        {
          echo "<td><b><center>$row[code]</b></td>";
          echo "<td><b><center>$row[uses]</b></td>";
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          echo "<td><b><center>$row[circuit]</b></td>";
        }
        else
        {
          CreateTabelCell($id,'code',$code);
          CreateTabelCell($id,'uses',$uses);
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          echo "<td class = 'constant'><center>$row[circuit]</td>";
        }
        if($cur_panel->fuse == 1)
        {
          CreateTabelCell($id,'breaker',$switch);
          CreateTabelCell($id,'fuse',$fuse);
        }
        else
        CreateTabelCell($id,'bftype',$bftype);
      }
      else
      {
        $sums[3] += $watts1; $sums[4] += $watts2; $sums[5] += $watts3;
        if($cur_panel->fuse == 1)
        {
          CreateTabelCell($id,'breaker',$switch);
          CreateTabelCell($id,'fuse',$fuse);
        }
        else
        CreateTabelCell($id,'bftype',$bftype);
        if(in_array($code, $codes))
        {
          echo "<td><b><center>$row[circuit]</td>";
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          echo "<td><b><center>$row[code]</td>";
          echo "<td><b><center>$row[uses]</td>";
        }
        else
        {
          echo "<td><center>$row[circuit]</td>";
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          CreateTabelCell($id,"code",$code);
          CreateTabelCell($id,"uses",$uses);
        }
        echo "</tr>";
      }
    }
    else if($cur_panel->get_intype() == 1)
    {
      if($circuit % 2 != 0)
      {
        echo "<tr>";
        $sums[0] += $watts1; $sums[1] += $watts2; $sums[2] += $watts3;
        if(in_array($code, $codes))
        {
          echo "<td><b><center>$row[code]</td>";
          echo "<td><b><center>$row[uses]</td>";
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          echo "<td><b><center>$row[circuit]</td>";
        }

        else
        {
          CreateTabelCell($id,'code',$code);
          CreateTabelCell($id,'uses',$uses);
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          echo "<td class = 'constant'><center>$row[circuit]</td>";
        }
        CreateTabelCell($id,'bftype',$bftype);
      }
      else
      {
        $sums[3] += $watts1; $sums[4] += $watts2; $sums[5] += $watts3;
        CreateTabelCell($id,'bftype',$bftype);
        if(in_array($code, $codes))
        {
          echo "<td><b><center>$row[circuit]</td>";
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          echo "<td><b><center>$row[code]</td>";
          echo "<td><b><center>$row[uses]</td>";
        }
        else
        {
          echo "<td class = 'constant'><center>$row[circuit]</td>";
          CreateLoadCell($id,$t_type,$circuit,$watts1,$watts2,$watts3,$code);
          CreateTabelCell($id,"code",$code);
          CreateTabelCell($id,"uses",$uses);
        }
        echo "</tr>";
      }
    }
    else if($cur_panel->get_intype() == 4)
    {
      //ISOLATION PANEL
      if($circuit % 2 != 0)
      {
        echo "<tr>";
        $sums[0] += $watts1; $sums[1] += $watts2; $sums[2] += $watts3;
        CreateTabelCell($id,'code',$code);
        CreateTabelCell($id,'uses',$uses);
        CreateTabelCelln($id,'watts1',$watts1);
        echo "<td class = 'constant'><center>$row[circuit]</td>";
        CreateTabelCell($id,'bftype',$bftype);
      }
      else
      {
        $sums[3] += $watts1; $sums[4] += $watts2; $sums[5] += $watts3;
        CreateTabelCell($id,'bftype',$bftype);
        echo "<td class = 'constant'><center>$row[circuit]</td>";
        CreateTabelCelln($id,'watts1',$watts1);
        CreateTabelCell($id,"code",$code);
        CreateTabelCell($id,"uses",$uses);
        echo "</tr>";
      }
    }
  }
}
?>
