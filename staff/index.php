<?php
session_start();
include_once '../config/database.php';
ini_set( 'max_execution_time', 300 );


if ( isset( $_SESSION['schoolzone']['SectionMaster_Id'] ) AND  isset( $_SESSION['schoolzone']['ActiveStaffLogin_Id'] ) ) 
{
    $SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
    $ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];
  
}else{
     header( "Location: https://dvsl.in/schoolzone2021/staff/auth/login.php" );
}

$Admin_Registeration_Id = $ActiveStaffLogin_Id;

//Fetching User Details
$data_query = "SELECT user_stafflogin.username,setup_sectionmaster.abbreviation As section_abbreviation FROM user_stafflogin JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$ActiveStaffLogin_Id' AND setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch = mysqli_fetch_array($fetch_data_q);



//Fetching Academic Year
$AY_array_result = mysqli_query( $mysqli, "SELECT `academic_year` as Academic_Year FROM `setup_academicyear` Where setup_academicyear.sectionmaster_Id = '$SectionMaster_Id' Order By sequence_no Asc " );
$AY_array = array();
while ( $row_AY = $AY_array_result->fetch_assoc() ) $AY_array[] = $row_AY;




//finding accessible pages to this particular id
$query = "
SELECT `setup_links_access`.`function_id`
FROM `user_stafflogin`
    LEFT JOIN `setup_links_access` on `user_stafflogin`.`Id`=`setup_links_access`.`user_id`
WHERE `user_stafflogin`.`Id`='" . $ActiveStaffLogin_Id . "'
";

$result1 = mysqli_query( $mysqli, $query );


//array of accessible functions
$function_array = array();

if ( $result1 != null )
{
  while ( $res = mysqli_fetch_assoc( $result1 ) )
  {
    $function_array[] = $res['function_id'];
  }
}
// check if a permission is set
function hasAccess($permission = [])
{
  global $function_array;
  // check if user has a specific privilege
  return array_intersect( $permission, $function_array );
}



?>



<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Home | SchoolZone</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
        integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
        crossorigin="anonymous">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
  

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
 
  <!-- Theme style -->
  <link rel="stylesheet" href="../extra/dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="../extra/dist/css/skins/_all-skins.min.css">



  <link rel="stylesheet" href="../extra/dist/css/index1_style.css">
  <link rel="stylesheet" href="../extra/plugins/datepicker/datepicker3.css">
</head>
<body class="hold-transition skin-grey sidebar-mini skin-blue fixed-padding" onload=display_c();>

     
<div class="wrapper" style="max-height:100%">

  <header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b> <?php echo $r_Staffdata_fetch['section_name']; ?> </b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" style="text-transform: uppercase;"><b> <?php echo $r_Staffdata_fetch['section_abbreviation']; ?> </b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

        
      
        <li class="dropdown user user-menu academic-year" style='padding-top:8px;'>

        <div class="dropdown">
          <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown" style="border-radius: 50px; border-color: #e08e0b;background: #2c2f31 !important ; ">
            Academic Year :
            <?php echo $Academic_Year; ?>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <?php $j = count( $AY_array );
            for ( $i = 0; $i < $j; $i++ )
            {

          
              if ( $AY_array[$i]['Academic_Year'] == $Academic_Year )
              {
                echo '<li class="active"><a href="#">' . $AY_array[$i]['Academic_Year'] . '</a></li>';
              }
              else
              {
                ?>
                <li>
                  <a data-toggle="modal"
                    data-target="#AY_change_dialog"
                    onclick="$('#modal_ay_change_pass').val('<?php echo $AY_array[$i]['Academic_Year']; ?>');;">
                    <?php echo $AY_array[$i]['Academic_Year']; ?>
                  </a>
                </li>
                <?php
              }
            }
            ?>
          </ul>
        </div>
        </li>


        <li class="dropdown user user-menu" style='padding-top:8px;margin-left: 10px'>
            

            <div class="dropdown">
              <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenu11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="border-radius: 50px; border-color: #e08e0b;background: #2c2f31 !important ;">
              <img src="../extra/dist/img/user2-160x160.jpg" class="user-image" alt="User Image" style="height: 22px;">
             
              <span class="hidden-xs" style="text-transform: uppercase;">
               <?php echo $r_Staffdata_fetch['username']; ?>
              </span>

                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-top" aria-labelledby="dropdownMenu11" >
                <li><a  data-toggle="modal" data-target="#personalChange_dialog">Change Personal Details</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="./auth/logout.php">Logout</a></li>
              </ul>
            </div>

        </li>


          <li class="dropdown user user-menu timeView" style="margin-right: 10px">
            <div align="right" style="min-width: 310px; margin: 0 auto; padding-bottom: 15px; padding-top: 15px">
              <span id="UTCtime" style="color: #fff"></span>
            </div>
          </li>


        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar" style="height: 700px; min-height:1%;box-shadow:0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="https://trekcommunity.in/user/images/default/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p style="text-transform: uppercase;"> <?php echo $r_Staffdata_fetch['username']; ?> <br> </p>
        </div>
      </div>
      <ul class="sidebar-menu">
        <li class="header" style="text-align:center;text-transform: full-width;font-size: 1.2em;color:#fff">Modules Lists</li>
        
        <li class="treeview">
        
        </li>

        <?php
        $masterid_array = array();
        for ( $i = 2; $i <= 23; $i++ )
        {
          $masterid_array[] = $i;
        }?>
        

<!-- ---------------------------------------------------------------------------------------------------------------- -->
      <li class="treeview">
        <a onClick="window.location.reload()">
          <i class="fa fa-folder " style="color:#FA573C"></i> <span>Home</span>
        </a>


        <?php

          /*
          * $tree_views will be our page structure at Level 1, Level 0 being Root
          * $tree_view will be our page structure at Level 2, Level 1 being Parent
          * so on recursively...
          *
          *----ROOT                         Level 0
          *    |
          *    +-- PARENT 1                 Level 1
          *    |   |
          *    |   +-- CHILD 1.1            Level 2
          *    |   |
          *    |   +-- CHILD 1.2            Level 2
          *    |
          *    +-- PARENT 2                 Level1
          *        |
          *        +-- CHILD 2.1            Level2
          *            |
          *            +-- CHILD 2.2        Level3
          *
          */
          ini_set( 'memory_limit', '-1' );
          // get unique PARENT nodes directly under ROOT node
          $search     = 'SELECT DISTINCT `parent`, `setup_links`.* FROM `setup_links` WHERE `depth` = 0  AND link_user_type = 0 ORDER BY `setup_links`.`Id`';
          $tree_views = QUERY::run( $search )->fetchAll();
          $innerFlag  = 0;
          $outerFlag  = 0;

          function printView($tree_views)
          {
            global $Admin_Registeration_Id;

            $html = '' . PHP_EOL;

            foreach ( $tree_views as $tree_view )
            {
              global $outerFlag;

              $searchChild = 'SELECT * FROM `setup_links` WHERE `parent` = ? AND link_user_type = 0 ';
              $views       = QUERY::run( $searchChild, [ $tree_view['Id'] ] )->fetchAll();

          //-- if permission -------------------------------------------------------------
              if ( ($Admin_Registeration_Id == 1 || hasAccess( [ $tree_view['Id'] ] )) && $tree_view['url'] )
              {
                $functionName = "getPage('".$tree_view['url']."', '".$tree_view['Id']."');";
                $html .= '
                  <li class="treelinks">
                    <a onclick="getPage('.$functionName.');" href="#' . $tree_view['header'] . '">
                      <i class="fa fa-folder" style="color: #f5c601"></i>
                      ' . $tree_view['header'] . '
                    </a>
                  </li>' . PHP_EOL;
              }

              elseif ( ($Admin_Registeration_Id == 1 || hasAccess( [ $tree_view['Id'] ] )) && !$tree_view['url'] )
              {

                $html .= '
                    <li  id="' . $tree_view['Id'] . '" class="treeview">

                    <a href="#">
                    <i class="fa fa-folder"  style="color: #f5c601"></i>
                    <span>
                    ' . $tree_view['header'] . '
                    </span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>' . PHP_EOL;
                // if ( $tree_view['depth'] == 0 )
                //  $html .= '<ul class="treeview-menu"></ul>' . PHP_EOL;
                ++$outerFlag;
              }
          //----------------------------------------------------------------------
              $view_inc = 0;
              $numItems = count($views);
              foreach ( $views as $view )
              {
                global $innerFlag;
                global $outerFlag;

                if ( $view['header'] && !$view['url'] && (1) ) // if ? array of array then -> recurse
                {
                  $searchSubChild = 'SELECT * FROM setup_links WHERE Id = ? AND link_user_type = 0 ';
                  $subChilds      = QUERY::run( $searchSubChild, [ $view['Id'] ] )->fetchAll(); // GET UNIQUE SUB-CHILDS

                  $html .= '
          <li  id="' . $view['Id'] * 100 . '" class="treeview-menu">' . PHP_EOL;

                  $html .= printView( $subChilds ); // going in
                  $html .= '
          </li>' . PHP_EOL;

                  ++$innerFlag;
                }
                else
                {
          //------ if permission -------------------------------------------------
                  if ( $Admin_Registeration_Id == 1 || hasAccess( [ $view['Id'] ] ) )
                  {
                    $functionName = "getPage('".$view['url']."', '".$view['Id']."');";

                    if($view_inc == 0){
                      $html.=' <ul  id="' . $view['Id'] * 100 . '" class="treeview-menu">';
                    }
                    

                    
                    $html .= '

            <li class="treelinks">
              <a onclick="'.$functionName.'" href="#' . $view['header'] . '">
                <i class="fa fa-circle-o"></i>
                ' . $view['header'] . '
              </a>
            </li>
          ' . PHP_EOL;


                  if($view_inc + 1 == $numItems){
                    $html.=' </ul>';
                  }

                //   if ($view['Id'] === end($array)) {
                //     echo 'LAST ITEM!';
                //  }

                  }
                  $view_inc ++;
          //----------------------------------------------------------------------
                }
                --$innerFlag;
              }
              for ( $i = 0; $i < $innerFlag; --$innerFlag )
              {
                $html .= '</li>' . PHP_EOL;
              }
              --$outerFlag;
            }
            for ( $i = 0; $i < $outerFlag; --$outerFlag )
            {
              $html .= '</li>' . PHP_EOL;
            }

            return $html;
          }

          $page = printView( $tree_views );
          ini_set( 'memory_limit', '128M' );

          //ob_start();

          //echo $page;

          //$out = ob_get_clean();

          //file_put_contents('out.php', $out);\

          echo $page;
          // file_put_contents( 'out.php', $page );

          // include 'out.php';
          ?>

<!-- ---------------------------------------------------------------------------------------------------------------- -->




    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height:156px">
    <!-- Content Header (Page header) -->
    <section class="content-header"></section>



<!-- ======================================================================================= -->

<div class="modal fade" id="AY_change_dialog" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Academic Year Change</h4>
          </div>
          <form id="adminAY_change_form" action="adminAY_change_pass_confirm.php" method="POST" onsubmit="return false">
            <div class="modal-body">
              <div class="form-group">
                <div class="form-group">
                  <label class="col-md-6 input-md control-label" for="name">Academic Year : </label>
                  <div class="col-md-6">
                    <input class="form-control input-md"
                           readonly
                           id="modal_ay_change_pass"
                           type="text"
                           name="modal_ay_change_pass"></div>
                </div>
              </div>

              <div class="form-group">
                <div class="form-group">
                  <label class="col-md-6 input-md control-label" for="name">Enter Your Login Password : </label>
                  <div class="col-md-6">
                    <input class="form-control input-md" id="passworday" type="password" required name="passworday">
                  </div>
                </div>
              </div>

              <button type="submit" id="submitForm" class="btn btn-default">Submit</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>



    
<div class="modal fade" id="personalChange_dialog" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Change Personal Details</h4>
          </div>
          <form id="personaldetails_change_form" method="POST" onsubmit="return false">
            <div class="modal-body">
              <div class="form-group">
                
                  <label class="col-md-6 input-md control-label" for="name">Operations : </label>
                  <div class="col-md-6">

                    <select class="form-control" name="staff_operation" id="staff_operation" required>
                      <option value=''>Select</option>
                      <option value="CHANGE_MOBILE">Change Mobile No</option>
                      <option value="CHANGE_EMAIL">Change Email Address</option>
                      <option value="CHANGE_PASSWORD">Change Password</option>
                    </select>

                
                  </div>
                </div>

									<div class="form-group">
										<label class="col-md-6 input-md control-label" for="name">Date Of Birth : </label>
										<div class="col-md-6">
                      <input class="form-control input-md" id="staff_dateofbirth" type="text" required name="staff_dateofbirth" placeholder="Enter Date OF Birth">
										</div>
									</div>

              <div class="form-group NewPasswordContainer">
                  <label class="col-md-6 input-md control-label" for="name">Enter New Password : </label>
                  <div class="col-md-6">
                    <input class="form-control input-md" id="staff_newpassword" type="password" name="staff_newpassword" placeholder="Enter New Password">
                  </div>
              </div>

              <div class="form-group NewMobileContainer">
                  <label class="col-md-6 input-md control-label" for="name">Enter New Mobile No : </label>
                  <div class="col-md-6">
                    <input class="form-control input-md" id="staff_newmobileno" type="password" name="staff_newmobileno" placeholder="Enter New Mobile No"> 
                  </div>
              </div>

              <div class="form-group NewEmailContainer">
                  <label class="col-md-6 input-md control-label" for="name">Enter New Email Address : </label>
                  <div class="col-md-6">
                    <input class="form-control input-md" id="staff_newemailaddress" type="password" name="staff_newemailaddress"
                    placeholder="Enter New Email Address">
                  </div>
              </div>

              <button type="submit" id="submitForm" class="btn btn-default">Submit</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>


<!-- ======================================================================================= -->


    <div id="loader" style="display:none;"></div>
    <!-- Main content -->
    <section class="content " id="DisplayDiv">

      <!-- CONTENT -->
    </section>
    <input type="hidden" value="<?php echo $ActiveStaffLogin_Id; ?>" id="UserId">

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Create the tabs -->
      <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      </ul>
      <!-- Tab panes -->
      <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">
          <!-- /.control-sidebar-menu -->
          <!-- /.control-sidebar-menu -->
        </div>
        <!-- /.tab-pane -->
        <!-- /.tab-pane -->
      </div>
    </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
            immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->



  <!-- jQuery UI 1.11.4 -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script> $.widget.bridge('uibutton', $.ui.button); </script>
  
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
  
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="../extra/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="../extra/plugins/datepicker/bootstrap-datepicker.js"></script>


  <script> var AdminLTEOptions = {navbarMenuHeight: "10px",}; </script>
  <script src="../extra/dist/js/app.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../extra/dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../extra/dist/js/demo.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/pretty-checkbox/3.0.0/pretty-checkbox.css" rel="stylesheet" type="text/css">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.css" rel="stylesheet" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" rel="stylesheet" type="text/css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.js"></script>



  <link rel="stylesheet" href="../assets/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.3.2/css/fixedColumns.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">


<link href="../assets/plugins/datatables/jquery.datatables.yadcf.css" rel="stylesheet" type="text/css"/>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
  <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js"></script>
  <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js"></script>
  <!-- <script src="js/vfs_fonts.js"></script> -->

<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.16/sorting/date-eu.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/1.2.1/bootstrap-filestyle.min.js"></script>

<script src="../assets/plugins/datatables/jquery.dataTables.yadcf.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>

<script src="../assets/plugins/datatables/dataTables.fnGetFilteredNodes.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.15/api/sum().js"></script>




  <script type="text/javascript">

    $(document).ready(function () {
      var height = $(window).height();
      var h1 = height - 68;
      var h2 = height - 60;
      $('#DisplayDiv').css('height', h1);
      $('.main-sidebar').css('min-height', h2);
    });
    $('li').click(function () {
      $('.content').scrollTop(0);
      $('.content').scrollLeft(0);

    });






    function display_c() {
      var reschoolzone = 1000; // Reschoolzone rate in milli seconds
      mytime = setTimeout('display_ct()', reschoolzone)
    }

    function display_ct() {
      var strcount;
      var x = new Date();
      var NewFormatDate = x.toString().slice(0, 24);
      document.getElementById('UTCtime').innerHTML = NewFormatDate;
      tt = display_c();
    }



    //reschoolzone_regularization_request_counter();
  </script>
  <script type="text/javascript">
    function getPage(url1, page_Id) {
      //$('#DisplayDiv').html('<img src="LoaderIcon.gif" />');
      $('body').removeClass("sidebar-open");
      $("#loader").css("display", "block");
      $("#DisplayDiv").css("display", "none");
      
      let UserId = $('#UserId').val();
      let UserType = '0';


      $.ajax({
          type:'POST',
          url:'./user_management/user_management_api.php?MenuActivity_Log_Report='+'u',
          dataType: "json",
          data: {Url: url1, UserId: UserId, UserType:UserType, pageId:page_Id},
          success:function(res_data){
   
                  jQuery.ajax({
                    url: url1,
                    type: "POST",
                    success: function (data) {
                      $('#DisplayDiv').html(data);
                      $("#loader").css("display", "none");
                      $("#DisplayDiv").css("display", "block");
                    }
                  });

          },
          
      }); // CLose $.ajax({
    } //c lose get Page

    $(document).ready(function () {
      var height = $(window).height();
      var h1 = height - 20;
      var h2 = height - 30;
      $('#DisplayDiv').css('height', h1);
      $('.main-sidebar').css('min-height', h2);
    });

    $('li').click(function () {
      $('.content').scrollTop(0);
      $('.content').scrollLeft(0);
    });

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    jQuery.ajax({
      url: 'staffhomescreen.php',
      type: "POST",
      success: function (data) {
        $('#DisplayDiv').html(data);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      }
    });


    $('#adminAY_change_form').submit(function (event) {
      var passworday = $('#passworday').val();
      if(passworday == ''){
        alert('Password is Required');
        return false;
      }

      $.ajax({
        url: './auth/onlinelogin_api.php?Change_Academic_Year=u',
        type: 'post',
        dataType: 'html',   //expect return data as html from server
        data: $('#adminAY_change_form').serialize(),
        success: function (response, textStatus, jqXHR) {
          if (response == 'Success')
          {
            location.reload();
          }else{
            alert('Invalid Password');
          }
          
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log('error(s):' + textStatus, errorThrown);
        }
      });
    });


    
    $('#personaldetails_change_form').submit(function (event) {
      var staff_operation = $('#staff_operation').val();
      
      var staff_newpassword = $('#staff_newpassword').val();
      var staff_newmobileno = $('#staff_newmobileno').val();
      var staff_newemailaddress = $('#staff_newemailaddress').val();

      if(staff_operation == ''){
        iziToast.error({
            title: 'Invalid Fields',
            message: 'Please Select Valid Operation from the list',
        });
        return false;
      }

      if(staff_operation == 'CHANGE_MOBILE'){
        
        if(staff_newmobileno == ''){
          iziToast.error({
            title: 'Invalid Fields',
            message: 'Mobile No is Required',
          });
          return false;
        }

        
        if(staff_newmobileno.length != '10'){
            $('#staff_newmobileno').val('');
            iziToast.error({
                title: 'Invalid Fields',
                message: 'Mobile No Must be 10 Digits',
            });
            return false;
        }

      }else if(staff_operation == 'CHANGE_EMAIL'){
        if(staff_newemailaddress == ''){
          iziToast.error({
            title: 'Invalid Fields',
            message: 'Change Email is Required',
          });
          return false;
        }
      }else if(staff_operation == 'CHANGE_PASSWORD'){ 
        if(staff_newpassword == ''){
          iziToast.error({
            title: 'Invalid Fields',
            message: 'New Password is Required',
          });
          return false;
        }
      }


  
  $("#loader").css("display", "block");
  $("#DisplayDiv").css("display", "none");

  var FORMDATA = $('#personaldetails_change_form').serializeArray();

  $.ajax({
      url: './auth/onlinelogin_api.php?Change_PersonalDetails=u',
      type:'POST',
      data: FORMDATA,
      dataType: "json",
      success:function(response){

        if(response['status'] == 'success') {

                
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");

            iziToast.success({
                title: 'Success',
                message: 'Details Updated Successfully',
            });



        }else {
            //FAILED STAY ON Current Page
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");

            iziToast.error({
                title: 'Warning',
                message: 'Invalid Details',
            });

        }// Close Else

      },


      
  });

});


// $('.treeview').click(function(event){
//   if(this.className == 'treeview'){
//     // alert('Open');
//     // $('ul > *').css('display', 'block');
//     // $(this).children('ul').css( "display", "block" );
//     $(this).find('ul').css( "background", "green" );
//     $(this).find('ul').css( "display", "block" );
//     console.log(  $(this).find('ul').attr('id'));
//   }else{
//     $(this).find('ul').css( "background", "red" );
//     $(this).find('ul').css( "display", "none" );
//   }
// });




  </script>

</body>
</html>

<style>
.fixed-padding {
  padding-right: 0px !important;
}
</style>

<script type="text/javascript">
        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });
   </script>



<script>
      $('#staff_dateofbirth').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
      });
      
      $('.NewPasswordContainer').hide();
      $('.NewEmailContainer').hide();
      $('.NewMobileContainer').hide();

      $(document).on('change', '#staff_operation', function(event) {
        var staff_operation = $(this).val(); // the selected options’s value
        
        if(staff_operation == 'CHANGE_MOBILE'){
          $('.NewPasswordContainer').hide();
          $('.NewEmailContainer').hide();
          $('.NewMobileContainer').show();
        }else if(staff_operation == 'CHANGE_EMAIL'){
          $('.NewPasswordContainer').hide();
          $('.NewEmailContainer').show();
          $('.NewMobileContainer').hide();
        }else if(staff_operation == 'CHANGE_PASSWORD'){ 
          $('.NewEmailContainer').hide();
          $('.NewPasswordContainer').show();
          $('.NewMobileContainer').hide();
        }else{
          $('.NewPasswordContainer').hide();
          $('.NewEmailContainer').hide();
          $('.NewMobileContainer').hide();
        }

      });
</script>

<style>

    
@media screen and (max-width: 750px) {

  .navbar {
    display: flex;
  }

}


</style>
