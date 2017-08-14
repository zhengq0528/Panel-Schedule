<?php
$editproject = new popwin("Updating A Project","Good","ep1","epc1");
$editproject->draw_head();
$editproject->draw_table_s();
echo "<input type='hidden'  name ='jn' value = '$_GET[jn]'>";
$c_title = "Job Description";
$c_data = array("long-text","jd","$row[jobdesc]","jd");
$editproject->draw_content($c_title,$c_data);
$arr_buildingtype = array(
  $row['type'] => getProjectType($row['type']),
  '5' => "All Others",
  '1' => "Dwelling Units",
  '2' => "Hospitals",
  '3' => "Hotels and Motels",
  '4' => "Warehouses",
);
$editproject->draw_selection("Building Type",'type',$arr_buildingtype);
$editproject->draw_table_e();
echo "<input type='submit' name='upd' value='update' class ='button'>";
$editproject->draw_foot();

?>

<script>
var editproject = document.getElementById('ep1');
var closeeditproject = document.getElementById('epc1');
$('#edit_project').click(function(){
  editproject.style.display = "block";
});
closeeditproject.onclick = function() {
  editproject.style.display = "none";
}
</script>
