<?php
class popwin
{
  //variables
  var $title;
  var $data;
  var $modal_id;
  var $close_id;
  //Declaration
  function __construct($title,$data,$modal_id,$close_id)
  {
    $this->title     = $title;
    $this->data      = $data;
    $this->modal_id  = $modal_id;
    $this->close_id  = $close_id;
  }
  function draw_head()
  {
    echo "<form method = 'post'>";
    echo "<div id = '".$this->modal_id."' class = 'modal'>";
    echo "<div class = 'modal-content'>";
    echo "<span id = '".$this->close_id."' class = 'close'>&times;</span>";
    echo "<center>";
    echo "<H2>$this->title</H2>";
  }
  function draw_foot()
  {
    echo "</center>";
    echo "</div>";
    echo "</div>";
    echo "</form>";
  }
  function draw_table_s()
  {
    echo "<table border = '2'
    class = 'table table-hover border'
    style= 'table, th, td
    {
      border: 1px solid black;
    }
    '>";
  }
  function draw_table_e()
  {
    echo "</table>";
  }
  function draw_content($c_title,$c_data)
  {
    //c_data gonna be an array
    //contains [0]type,[1]name,[2]value,[3]id
    echo "<tr>";
    echo "<td><center>$c_title</center></td>";
    echo "<td><center><input type='$c_data[0]' name ='$c_data[1]' value = '$c_data[2]' id = '$c_data[3]' $c_data[4] ></center></td>";
    echo "</tr>";
  }
  function draw_selection($c_title,$c_data,$array)
  {
    echo "<tr>";
    echo "<td><center>$c_title</center></td>";
    echo "<td><center>";
    echo "<select name = '$c_data' style='width:175px;height:30px;'>";
    foreach($array as $k => $d)
    {
      echo "<option value='$k'>$d</option>";
    }
    echo "</select></center></td></tr>";
  }
  function get_title()
  {
    return $this->title;
  }
  function get_data()
  {
    return $data;
  }
}
?>
