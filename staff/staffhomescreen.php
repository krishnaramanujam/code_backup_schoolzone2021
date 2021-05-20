<?php
session_start();
include_once '../config/database.php';


$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$ActiveStaffLogin_Id = $_SESSION['schoolzone']['ActiveStaffLogin_Id'];


$data_query = "SELECT user_stafflogin.username,setup_sectionmaster.abbreviation As section_abbreviation,setup_sectionmaster.section_logo FROM user_stafflogin LEFT JOIN setup_departmentmaster ON setup_departmentmaster.Id = user_stafflogin.departmentmaster_Id LEFT JOIN setup_sectionmaster ON setup_sectionmaster.Id = setup_departmentmaster.sectionmaster_Id WHERE user_stafflogin.Id = '$ActiveStaffLogin_Id' AND setup_sectionmaster.Id = '$SectionMaster_Id' ";
$fetch_data_q = mysqli_query($mysqli,$data_query);

$r_Staffdata_fetch = mysqli_fetch_array($fetch_data_q);

?>




<style>
	
	.collapsed-box .box-title
	{
		display:block !important;
	}
	a
	{
		color:white;
		
	}
	li{
		margin-bottom:10px;
	}
	a:hover
	{
		color:navy;
		 font-weight: bold;
	
	}
		.att{
		height: 200px;
	}

  .att1{
		height: 60px;
	}
	
	.small-box {
    box-shadow: 0 0px 0px rgba(0,0,0,0);
}
	@media screen and (max-width: 991px)
	{
		.att{
		height: auto;
	}
	.small-box {
		text-align:left !important;
}
	}
	
	.box-title
	{
		font-size:30px !important;
	}
	

</style>
<div class="container col-md-12">
  
  <div class="row">
    <div class="col-md-6 col-xs-12">
  
      <div class="box box-solid" style="background-color:#62d6d6;">
				<div class="box-header ui-sortable-handle" style="cursor: move; color:white;">
				
					<h3 class="box-title">Welcome <?php echo $r_Staffdata_fetch['username']; ?></h3>
					<div class="box-tools pull-right">

					</div>
				</div>
				<div class="box-body border-radius-none att1">
		  
					<div class="small-box ">
						<div class="inner">
							
				
						</div>
					
                    </div>
			    </div>

			</div>


    </div><!--Close Col-->
    <div class="col-md-6 col-xs-12">

      <div class="box box-solid" style="background-color:#f39c12;">
				<div class="box-header ui-sortable-handle" style="cursor: move; color:white;">
				
					<h3 class="box-title" >ssa</h3>
					<div class="box-tools pull-right">

					</div>
				</div>
				<div class="box-body border-radius-none att1">
		  
					<div class="small-box ">
						<div class="inner">
							
				
						</div>
					
                    </div>
			    </div>

			</div>


    </div><!--Close Col-->

  </div><!--Close Row-->

  <div class="row">
    <div class="col-md-6 col-xs-12">
  
    <div class="box box-solid" style="background-color:#23ce80; color:white">
				<div class="box-header ui-sortable-handle" style="cursor: move; color:white">
					<h3 class="box-title">Recent Pages</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-sm" style="background-color:#07af63; color:white" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<!--<button type="button" class="btn btn-sm" style="background-color:#07af63; color:white" data-widget="remove"><i class="fa fa-times"></i>
						</button>-->
				
					</div>
				</div>
				<div class="box-body border-radius-none att">
		  
					<div class="small-box ">
						<div class="inner">
										
            <ul style="list-style: none;">
								 <!--<li>
									<a onclick="getPage('paidfeepaymentreport7.php');" href="#Admission_Report_Marks_Wise" ><i class="fa fa-user"></i> Admission Report (Marks Wise) </a>
								</li>-->
                <?php 
                $page_activity_q = mysqli_query($mysqli, "SELECT user_activitylog.*, setup_links.header, setup_links_access.id, setup_links.url, MAX(user_activitylog.timeStamp) AS recent_date FROM `user_activitylog` JOIN setup_links ON setup_links.Id = user_activitylog.pagelink_Id JOIN setup_links_access ON setup_links_access.function_id = setup_links.Id AND setup_links_access.user_id = '$ActiveStaffLogin_Id' WHERE user_activitylog.userType = '0' AND user_activitylog.activityType_Id = '2' AND user_activitylog.user_Id = '$ActiveStaffLogin_Id' GROUP BY user_activitylog.pagelink_Id ORDER BY user_activitylog.timeStamp DESC LIMIT 5  ");
                $row_page_activity = mysqli_num_rows($page_activity_q);

                if($row_page_activity > 0){
                  while($r_page_activity = mysqli_fetch_array($page_activity_q)){
                ?>

                  <li>
                    <a  href="#Recent" <?php if(empty($r_page_activity['id'])){ ?>  data-toggle="tooltip" data-placement="top" title="Access Revoke"  <?php }else{ ?> onclick="getPage('<?php echo $r_page_activity['url']; ?>', '<?php echo $r_page_activity['pagelink_Id']; ?>');"  <?php } ?>><i class="fa fa-folder"></i> <?php echo $r_page_activity['header']; ?> </a> <span class="badge badge-warning" style="float: inline-end;"><?php echo date('d/m/Y h:m:s',strtotime(str_replace('/','-',$r_page_activity['recent_date']))); ?></span>
                  </li>
                  
                <?php } }?>

							</ul>
              <div class="icon">
						<i class="fa fa-bars"></i>
					</div>
						</div>
					
                    </div>
			    </div>

			</div>


    </div><!--Close Col-->
    
    
    <div class="col-md-6 col-xs-12">
    <div class="box box-solid bg-aqua"style="color:white">
				<div class="box-header ui-sortable-handle" style="cursor: move; color:white">
					<h3 class="box-title">Personal Details</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-sm" style="background-color:#149cbd;    color: #fff" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<!-- <button type="button" class="btn btn-sm" style="background-color:	#881848c4;    color: #fff" data-widget="remove"><i class="fa fa-times"></i>
						</button> -->
			
					</div>
				</div>
				<div class="box-body border-radius-none att">
		  
					<div class="small-box ">
						<div class="inner">
            <ul style="list-style: none;">
									<!--<li>
									<a onclick="getPage('staffcontrolpanel.php');" href="#Staff"><i class="fa fa-circle-o"></i> Staff Directory</a>
									</li>-->
									
								
								 <li class="submenu_2">
                    <i class="fa fa-circle-o"></i> <a  data-toggle="modal" data-target="#personalChange_dialog">Change Mobile No</a>
                </li>


                <li class="submenu_2">
                  <i class="fa fa-circle-o"></i>  <a  data-toggle="modal" data-target="#personalChange_dialog">Change Email Address</a>
                </li>
                
								 <li class="submenu_2">
                 <i class="fa fa-circle-o"></i>  <a  data-toggle="modal" data-target="#personalChange_dialog">Change Password</a>
                </li>

                
                  
								</ul>
				
						</div>
            <div class="icon">
						<i class="fa fa-book"></i>
					</div>
          </div>
			  </div>

			</div>

    </div><!--Close Col-->

  </div><!--Close Row-->


</div><!--Close COntainer-->