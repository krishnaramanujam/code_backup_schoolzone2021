<?php 
session_start();
// if ( isset( $_SESSION[$session_schoolzone_svjc_id]['CR_Id'] ) )
// {
// header( 'Location: https://www.dvsl.in/schoolzone/FYJCOffline/index.php' );
// }

include('./header.php'); ?>

<section id="DisplayDiv"></section>

<div id="loader" style="display:none;"></div>

<?php include('./footer.php'); ?>

<!-- Date Picker -->
<link rel="stylesheet" href="../../../extra/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
<link rel="stylesheet" href="../../../extra/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap wysihtml5 - text editor -->

<script src="../../../extra/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="../../../extra/plugins/datepicker/bootstrap-datepicker.js"></script>