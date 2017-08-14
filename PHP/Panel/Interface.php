<!DOCTYPE html>
<!--<input type='text' value = 'quan' id='debug'>-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PANEL SCHEDULE SYSTEM</title>
  <!-- Datatables libraries -->
  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/jquery.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/jquery.dataTables.min.js"></script>
  <script src="../../js/dataTables.bootstrap.min.js"></script>
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link href="../../css/dataTables.bootstrap.min.css" rel="stylesheet">
  <!-- Customer Libraries -->
  <link href="../../css/Customer/popupstyle.css?v=4" rel="stylesheet">
  <link href="../../css/Customer/button.css?v=1" rel="stylesheet">
  <link href="../../css/Customer/TableStyle.css?v=4" rel="stylesheet">

</head>

<?php
error_reporting(0);
include ('../Database/db.php');   //Connecting to the Database
//Customer Libraries
include("../Libraries/Panel.php");
include("../Libraries/Popup.php");
include("../Libraries/Calculator.php");
include("../Libraries/CreatePanel.php");
include("../Addon/HandleLink.php");

include("../Project/LoadProject.php");
include("../Tools/CreatePanel.php");
include("../Tools/DeletePanel.php");
include("../Project/EditProject.php");

include("panellist.php");

include("Table.php");
include("../Addon/TranTable.php");
include("../Addon/KeyTable.php");

include("../Addon/LinkPanel.php");
include("../Addon/TranPanel.php");
include("../Addon/DisConnect.php");
?>
<script src="../../Scripts/LinkPanel.js?v=2"></script>
