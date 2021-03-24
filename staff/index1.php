<?php include('./header.php'); ?>

<?php 
$_SESSION['schoolzone']['SectionMaster_Id'] = '1';
$_SESSION['schoolzone']['ActiveStaffLogin_Id'] = '1';
?>

<!-- NAVBAR -->
<?php include('../config/database.php'); ?>

<?php
    if ( isset( $_SESSION['schoolzone']['SectionMaster_Id'] ) AND  isset( $_SESSION['schoolzone']['ActiveStaffLogin_Id'] ) ) 
    {
        $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
        $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
    
    }else{
        header( "Location: https://dvsl.in/schoolzone2021/staff/auth/login.php" );
    }
?>


<!-- NAVBAR -->
<?php include('./sidenav.php'); ?>

   

<div class="main">
Content Place Heere
</div>

            


<?php include('./footer.php'); ?>





