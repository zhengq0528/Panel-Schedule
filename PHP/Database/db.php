<?php
  $mysql_hostname = 'localhost';
  $mysql_user = 'PanelSchedule';
  $mysql_password = 'PanelSchedule';
  $mysql_database = "Dickerson Engineer";
  $conn = new mysqli($mysql_hostname, $mysql_user,$mysql_password);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>
