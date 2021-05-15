<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';

?>



<div class="container">

    <div class="row">
        <div class="col-md-11"><h3 style="font-weight:bold;font-style:italic;" class="font_all"><i class="fa fa-clock-o text-primary" aria-hidden="true"></i> Fee Master</h3></div>   
    </div>


    <form id="FilterForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Batch:</label>
                </div>

                <div class="col-md-2"> 
                    <select name="batch_sel" id="batch_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select Batch ----</option>
                                <?php 
                                if($ActiveStaffLogin_Id == '2'){
                                    $q = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID'  ";
                                }else{
                                    $q = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id JOIN comm_batch_access ON comm_batch_access.batchMaster_Id = setup_batchmaster.Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID' AND comm_batch_access.userId = '$ActiveStaffLogin_Id' ";
                                }

                                    

                                    $batch_fetch = mysqli_query($mysqli,$q);

                                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                                    <option value="<?php echo $r_batch['Id']; ?>"  
                                    <?php if($_GET['batch_sel'] == $r_batch['Id']){ echo 'Selected'; } ?>
                                    ><?php echo $r_batch['batch_name']; ?></option>
                                <?php }   ?>
                    </select>
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="submit" name="login" id="filter_result" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
        </form> 


<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){


        $batch_sel = $_GET['batch_sel'];

?>



<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-controls="collapseFive">
                    Download Excel Format
                </a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                        <table id="online_table" class="table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Fee Header No</th>
                                    <th>Gender</th>
                                    <th>Applicable to</th>
                                    <th>Freeship</th>
                                    <th>Amount</th>
                                    <th>Fee Structure No</th>
                                    <th>Bank Account No</th>
                                    <th>Fee Header Type No</th>
                                    <th>Please remove the below data before importing:</th>
                                    <th>Please remove the below data before importing:</th>
                                </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th></th>
                                <th></th>
                                <td>Fee Structure No</td>
                                <td>Fee Structure Name</td>
                            </tr>

                            <?php
                                 $query_de = "SELECT fee_structure_master.* FROM `fee_structure_master` WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id' ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){  ?>

                                   <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th></th>
                                    <td><?php echo $run_d['Id']; ?></td>
                                    <td><?php echo $run_d['Fee_Structure_Name']; ?></td>
                                   </tr>

                                 
                                 <?php
                                 }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th></th>
                                <th></th>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th></th>
                                <th></th>
                                <td>Bank Account No</td>
                                <td>Bank Account Name</td>
                            </tr>

                            <?php
                                 $query_de = "SELECT setup_bankaccountmaster.* ,setup_bankmaster.abbreviation FROM `setup_bankaccountmaster` JOIN
                                 setup_bankmaster ON setup_bankmaster.Id = setup_bankaccountmaster.bankmaster_Id  WHERE setup_bankaccountmaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_bankaccountmaster.status = '1' ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){  ?>

                                   <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th></th>
                                    <td><?php echo $run_d['Id']; ?></td>
                                    <td><?php echo $run_d['abbreviation']; ?></td>
                                   </tr>

                                 
                                 <?php
                                 }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th></th>
                                <th></th>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th></th>
                                <th></th>
                                <td>Fee Header Type No</td>
                                <td>Fee Header Type Name</td>
                            </tr>

                            <?php
                                 $query_de = "SELECT fee_headertype.* FROM `fee_headertype`";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){  ?>

                                   <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th></th>
                                    <td><?php echo $run_d['Id']; ?></td>
                                    <td><?php echo $run_d['headertype_name']; ?></td>
                                   </tr>

                                 
                                 <?php
                                 }
                            ?>
                                
                            </tbody>
                        </table>
                </div>
            </div>
            </div> <!--Close Panel -->
        </div>	

        <div class="col-md-2">
            <a onclick="$('#contact_dialog').modal('show');" class="btn btn-default">
                    <i class="fa fa-upload" aria-hidden="true"></i> Import
            </a>
        </div>



        <div class="DetailsDisplay">
        <div class="modal fade" id="contact_dialog" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import File</h4>
                    </div>
                    <div class="modal-body" style="padding-bottom:10%">
                        <form id="import_file_form" method="post" enctype="multipart/form-data">
                            
                            <input type="hidden" name="batch_sel_import" id="batch_sel_import" val="">
                            
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-4 input-md control-label" for="name">Select an excel file </label>
                                    <div class="col-md-4">
                                        <input type="file" name="upload_file">
                                    
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" id="import_file_submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>





<div class="row">
    
    <div class="col-md-12"> 

     <div class="col-md-9">
         <form id="All_instance_form">
         <table class="table table-striped" id="InstanceMaster_Table">
             <thead>
                 <tr><th>Sr No.</th><th style="text-align:left;">Fee Header Name</th><th>Gender</th><th>Applicable</th><th>Freeship</th><th>Amount</th><th>Fee Structure Name</th><th>Bank Account Name</th><th>Operations</th></tr>
             </thead>
             <tbody>
                 <?php  $instance_fetch_q = mysqli_query($mysqli,"SELECT
                        fee_feemaster.*,
                        fee_structure_master.Fee_Structure_Name,
                        setup_bankmaster.abbreviation,
                        fee_headermaster.header_name,
                        CASE WHEN fee_feemaster.freeship = 1 THEN 'Yes' WHEN fee_feemaster.freeship = 0 THEN 'No' ELSE 'NA' END AS freeship_val,
                        CASE WHEN fee_feemaster.applicable_to = 0 THEN 'All' WHEN fee_feemaster.applicable_to = 2 THEN 'New Admission' WHEN fee_feemaster.applicable_to = 1 THEN 'Select Students' ELSE 'NA' END AS applicable_to_val
                        FROM
                        `fee_feemaster`
                        JOIN
                        setup_batchmaster ON setup_batchmaster.Id = fee_feemaster.batchmaster_Id
                        JOIN
                        fee_structure_master ON fee_structure_master.Id = fee_feemaster.feestructure_Id
                        JOIN
                        setup_bankaccountmaster ON setup_bankaccountmaster.Id = fee_feemaster.bankaccountmaster_Id
                        JOIN
                        setup_bankmaster ON setup_bankmaster.Id = setup_bankaccountmaster.bankmaster_Id
                        JOIN
                        fee_headermaster ON fee_headermaster.Id = fee_feemaster.feeheader_Id WHERE fee_feemaster.sectionmaster_Id = '$SectionMaster_Id' AND fee_feemaster.batchmaster_Id = '$batch_sel'   ");
                 $i = 1; while($r_instance_fetch = mysqli_fetch_array($instance_fetch_q)){  ?>
                     <tr> 
                         <td style="width:10%"><?php echo $i; ?></td>
                         <td style="width:15%;text-align:left;"><?php echo $r_instance_fetch['header_name']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['Gender']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['applicable_to_val']; ?></td>
                         <td style="width:15%"><?php echo $r_instance_fetch['freeship_val']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['Amount']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['Fee_Structure_Name']; ?></td>
                         <td style="width:10%"><?php echo $r_instance_fetch['abbreviation']; ?></td>
                         <td style="width:20%">
                         <div class="btn-group btn-group-xs" role="group" aria-label="...">
                            
                             <div class="btn-group" role="group">
                                 <a href="#Edit_Scroll"><button type="button" class="btn btn-default edit_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Edit Fee Instance" data-toggle="tooltip"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="color:#33b5e5;"></span></button></a>
                             </div>
                             <div class="btn-group" role="group">
                                 <button type="button" class="btn btn-default delete_instance_btn" id="<?php echo $r_instance_fetch['Id']; ?>" data-placement="top" title="Delete Fee Instance" data-toggle="tooltip"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#ff3547;"></span></button>
                             </div>
                             </div>

                             <input type="hidden" value="<?php echo $r_instance_fetch['Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Id">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['batchmaster_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_batchmaster_Id">

                             <input type="hidden"  value="<?php echo $r_instance_fetch['feeheader_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_feeheader_Id">

                             <input type="hidden" value="<?php echo $r_instance_fetch['feeheadertype_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_feeheadertype_Id">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['batchcourse_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_batchcourse_Id">

                             <input type="hidden"  value="<?php echo $r_instance_fetch['academicyear_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_academicyear_Id">
                      
                             <input type="hidden"  value="<?php echo $r_instance_fetch['feestructure_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_feestructure_Id">

                             <input type="hidden"  value="<?php echo $r_instance_fetch['groupmaster_Id']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_groupmaster_Id">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['Category']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Category">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['Amount']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Amount">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['Gender']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_Gender">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['applicable_to']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_applicable_to">
                             <input type="hidden"  value="<?php echo $r_instance_fetch['freeship']; ?>" class="<?php echo $r_instance_fetch['Id']; ?> all_fields" name="fetch_edit_freeship">
                            
                            

                         </td>
                     </tr>
                 <?php $i++; } ?>

             </tbody>
         </table>
         </form>
     </div> <!--Col Close-->

     <div class = "col-md-3">
     <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<!------------------------------------------------------------------------------------------------------------------------->
         <div class="panel panel-default InstanceCreate_Model">
             <div class="panel-heading" role="tab" id="headingOne">
             <h4 class="panel-title">
                 <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                 Create Fee Master Instance
                 </a>
             </h4>
             </div>
             <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
             <div class="panel-body">
                 <div class="form-group">
                     <label for="email">Enter Header Name*  </label>
                     <select class="form-control drop_sel" id="add_feeheader_Id" name="add_feeheader_Id">
                        <option value="">Select</option>
                     <?php
                            $query_de = "Select fee_headermaster.* from fee_headermaster WHERE fee_headermaster.sectionmaster_Id = '$SectionMaster_Id'";
                            $run_de = mysqli_query($mysqli,$query_de);
                            while($run_d = mysqli_fetch_array($run_de)){ 
                                echo "<option value=".$run_d['Id'].">".$run_d['header_name']."</option>";
                            }
                    ?>
                      </select>

                     <br>
                     <label for="email">Enter Gender*  </label>
                     <select class="form-control drop_sel" id="add_Gender" name="add_Gender" required>
                     <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                     </select>
                     <br>
                     <label for="email">Enter Applicable To*  </label>
                     <select class="form-control drop_sel" id="add_applicable_to" name="add_applicable_to" required>
                     <option value="">Select</option>
                        <option value="0">All</option>
                        <option value="1">Select Students</option>
                        <option value="2">New Admission</option>
                     </select>
                     <br>
                     <label for="email">Enter Freeship*  </label>
                     <select class="form-control drop_sel" id="add_freeship" name="add_freeship" required>
                     <option value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                     </select>
                     <br>
                     <label for="email">Enter Amount*  </label>
                     <input type="text" class="form-control" id="add_Amount" name="add_Amount" placeholder="Enter Amount">
                     <br>
                     <label for="email">Select Structure Name*  </label>
                     <select class="form-control drop_sel" id="add_feestructure_Id" name="add_feestructure_Id">
                        <option value="">Select</option>
                         <?php
                                 $query_de = "SELECT fee_structure_master.* FROM `fee_structure_master` WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['Fee_Structure_Name']."</option>";
                                 }
                         ?>

                     </select>

                     <br>
                     <label for="email">Batch*  </label>
                     <select class="form-control drop_sel" id="add_batchmasterid" name="add_batchmasterid" readonly style="pointer-events: none;">
                         <?php
                                 $query_de = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID' AND setup_batchmaster.Id = '$batch_sel' ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['batch_name']."</option>";
                                 }
                         ?>

                     </select>
                     <br>
                     <label for="email">Select Bank Name*  </label>
                     <select class="form-control drop_sel" id="add_bankaccountmaster_Id" name="add_bankaccountmaster_Id">
                        <option value="">Select</option>
                         <?php
                                 $query_de = "SELECT setup_bankaccountmaster.*, setup_bankmaster.abbreviation FROM `setup_bankaccountmaster` JOIN
                                 setup_bankmaster ON setup_bankmaster.Id = setup_bankaccountmaster.bankmaster_Id WHERE setup_bankaccountmaster.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['abbreviation']."</option>";
                                 }
                         ?>

                     </select>
                    
                     <br>
                     <label for="email">Select Header Type*  </label>
                     <select class="form-control drop_sel" id="add_feeheadertype_Id" name="add_feeheadertype_Id">
                     <option value="">Select</option>
                         <?php
                                 $query_de = "SELECT fee_headertype.* FROM `fee_headertype` ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['headertype_name']."</option>";
                                 }
                         ?>

                     </select>
                    
                 </div>
             </div>
             <div class="panel-footer">
                  <input type="submit" name="submit_addinstance" value="Save Changes" id="submit_addinstance" class="btn btn-primary">  
             </div>

             </div>
         </div>
<!------------------------------------------------------------------------------------------------------------------------->           
<!------------------------------------------------------------------------------------------------------------------------->
         <div class="panel panel-default EventSelect_Model" >
             <div class="panel-heading" role="tab" id="headingTwo">
             <h4 class="panel-title">
             <button type="button" class="close add_instance"><span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#3c8dbc;"></span></button>
                 <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                 Edit Fee Master Instance 
                 </a>
             </h4>
             </div>
             <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
             <form id="Edit_FormData">
             <div class="panel-body">
                 <div class="form-group">
                     <input type="hidden" class="form-control" id="edit_InstanceId" name="edit_InstanceId">
                     <label for="email">Enter Header Name*  </label>
                     <select class="form-control drop_sel" id="edit_feeheader_Id" name="edit_feeheader_Id" required>
                        <option value="">Select</option>
                     <?php
                            $query_de = "Select fee_headermaster.* from fee_headermaster WHERE fee_headermaster.sectionmaster_Id = '$SectionMaster_Id'";
                            $run_de = mysqli_query($mysqli,$query_de);
                            while($run_d = mysqli_fetch_array($run_de)){ 
                                echo "<option value=".$run_d['Id'].">".$run_d['header_name']."</option>";
                            }
                    ?>
                      </select>

                     <br>
                     <label for="email">Enter Gender*  </label>
                     <select class="form-control drop_sel" id="edit_Gender" name="edit_Gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                     </select>
                     <br>
                     <label for="email">Enter Applicable To*  </label>
                     <select class="form-control drop_sel" id="edit_applicable_to" name="edit_applicable_to" required>
                        <option value="0">All</option>
                        <option value="1">Select Students</option>
                        <option value="2">New Admission</option>
                     </select>
                     <br>
                     <label for="email">Enter Freeship*  </label>
                     <select class="form-control drop_sel" id="edit_freeship" name="edit_freeship" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                     </select>
                     <br>
                     <label for="email">Enter Amount*  </label>
                     <input type="text" class="form-control" id="edit_Amount" name="edit_Amount" placeholder="Enter Amount">
                     <br>
                     <label for="email">Select Structure Name*  </label>
                     <select class="form-control drop_sel" id="edit_feestructure_Id" name="edit_feestructure_Id">
                        <option value="">Select</option>
                         <?php
                                 $query_de = "SELECT fee_structure_master.* FROM `fee_structure_master` WHERE fee_structure_master.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['Fee_Structure_Name']."</option>";
                                 }
                         ?>

                     </select>

                     <br>
                     <label for="email">Batch*  </label>
                     <select class="form-control drop_sel" id="edit_batchmasterid" name="edit_batchmasterid" readonly style="pointer-events: none;">
                         <?php
                                 $query_de = "SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID' AND setup_batchmaster.Id = '$batch_sel' ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['batch_name']."</option>";
                                 }
                         ?>

                     </select>

                     <br>
                     <label for="email">Select Bank Name*  </label>
                     <select class="form-control drop_sel" id="edit_bankaccountmaster_Id" name="edit_bankaccountmaster_Id">
        
                         <?php
                                 $query_de = "SELECT setup_bankaccountmaster.*, setup_bankmaster.abbreviation FROM `setup_bankaccountmaster` JOIN
                                 setup_bankmaster ON setup_bankmaster.Id = setup_bankaccountmaster.bankmaster_Id WHERE setup_bankaccountmaster.sectionmaster_Id = '$SectionMaster_Id'";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['abbreviation']."</option>";
                                 }
                         ?>

                     </select>

                     <br>
                     <label for="email">Select Header Type*  </label>
                     <select class="form-control drop_sel" id="edit_feeheadertype_Id" name="edit_feeheadertype_Id">
        
                         <?php
                                 $query_de = "SELECT fee_headertype.* FROM `fee_headertype` ";
                                 $run_de = mysqli_query($mysqli,$query_de);
                                 while($run_d = mysqli_fetch_array($run_de)){ 
                                     echo "<option value=".$run_d['Id'].">".$run_d['headertype_name']."</option>";
                                 }
                         ?>

                     </select>
                    
                 </div>
             </div>
             </form>
             <div class="panel-footer">
                  <input type="submit" name="submit_editinstance" value="Save Changes" id="submit_editinstance" class="btn btn-primary">  
             </div>
             </div>
         </div>

<!------------------------------------------------------------------------------------------------------------------------->
     </div>
     </div> <!--Close Col-->

   </div> <!--Div Wrap Close-->




<?php } // close isset ?> 
<!-- -------------------------------------------------------------------------------------------------- -->
            
    </div><!--Row Close-->

</div><!--Close Container-->


<script>
$('#FilterForm').submit(function(e){
    e.preventDefault();
    var batch_sel = $('#batch_sel').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./fee_management/admission_fees.php?Generate_View='+'u',
        type:'GET',
        data: {batch_sel:batch_sel},
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");

            $('#add_batchmasterid').val(batch_sel);
            
            $('#batch_sel_import').val(batch_sel);
        },
   });



});

//Model Always Show
$('div').removeClass("modal-backdrop");
$('.InstanceCreate_Model').collapse('show');
$('.EventSelect_Model').hide();


$('.add_instance').click(function(event){

    $('.EventSelect_Model').hide();
    $('.InstanceCreate_Model').collapse('show');

});


//INSTANCE ADD-----------------------------------------------------------------------------------------------------------

$('#submit_addinstance').click(function(event){
   var add_feeheader_Id = $('#add_feeheader_Id').val();
   var add_Gender = $('#add_Gender').val();
   var add_applicable_to = $('#add_applicable_to').val();
   var add_freeship = $('#add_freeship').val();

   var add_Amount = $('#add_Amount').val();
   var add_feestructure_Id = $('#add_feestructure_Id').val();
   var add_batchmasterid = $('#add_batchmasterid').val();
   
   var add_bankaccountmaster_Id = $('#add_bankaccountmaster_Id').val();
   var add_feeheadertype_Id = $('#add_feeheadertype_Id').val();
   var batch_sel = $('#batch_sel').val();


    if(add_feeheader_Id == '' || add_Gender == '' || add_applicable_to == '' || add_freeship == '' || add_Amount == ''|| add_feestructure_Id == '' || add_batchmasterid == '' || add_bankaccountmaster_Id == '' || add_feeheadertype_Id == ''){
        iziToast.warning({
            title: 'Empty Fields',
            message: 'All fields is mandatory',
        });
        return false;
    }

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    $.ajax({
        url:'./fee_management/fee_management_api.php?Add_FeeMasterInstance='+'u',
        type:'POST',
        data: {add_feeheader_Id:add_feeheader_Id,add_Gender:add_Gender,add_applicable_to:add_applicable_to, add_freeship:add_freeship, add_Amount:add_Amount, add_feestructure_Id:add_feestructure_Id, add_batchmasterid:add_batchmasterid, add_bankaccountmaster_Id:add_bankaccountmaster_Id, add_feeheadertype_Id:add_feeheadertype_Id},
        dataType: "json",
        success:function(add_instance_res){  
            if(add_instance_res['status'] == 'success'){
                $.ajax({
                    url:'./fee_management/admission_fees.php?Generate_View='+'u',
                    type:'GET',
                    data: {batch_sel: batch_sel},
                    success:function(st_logs){
                        $('#DisplayDiv').html(st_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'FeeMaster Instance Added',
                        });

                                    
                        $('#add_batchmasterid').val(batch_sel);
                        
                        $('#batch_sel_import').val(batch_sel);
                    },
                });   

            }else if(add_instance_res['status'] == 'EXISTS'){
                       
                       $("#loader").css("display", "none");
                       $("#DisplayDiv").css("display", "block");
                       iziToast.error({
                           title: 'Duplicate',
                           message: 'FeeMaster Already Exist',
                       });
           }

        },//Close Success
    });// close Ajax 

});

//Instance Add Close----------------------------------------------------------------------------------------------------


//Instance Delete----------------------------------------------------------------------------------------------------
$('.delete_instance_btn').click(function(event){
    var delete_instance_Id = $(this).attr('id');
    var batch_sel = $('#batch_sel').val();


    if (confirm('Are you sure you want to Delete Existing Instance?')) {
        $.ajax({
            url:'./fee_management/fee_management_api.php?Delete_FeeMasterInstance='+'u',
            type: 'POST',
            data: {delete_instance_Id:delete_instance_Id},
            success:function(del_msg){
                if(del_msg == '200'){
                    
                    $.ajax({
                        url:'./fee_management/admission_fees.php?Generate_View='+'u',
                        type:'GET',
                        data: {batch_sel: batch_sel},
                        success:function(st_logs){
                            $('#DisplayDiv').html(st_logs);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                            iziToast.success({
                                title: 'Success',
                                message: 'FeeMaster Deleted',
                            });

                                            
                            $('#add_batchmasterid').val(batch_sel);
                            
                            $('#batch_sel_import').val(batch_sel);
                        },
                    });   

                }
            },
        });
    }

});
//Instance Delete Close----------------------------------------------------------------------------------------------------

//Instance Edit----------------------------------------------------------------------------------------------------
$('.edit_instance_btn').click(function(event){
    var edit_instance_Id = $(this).attr('id');
    $('.EventSelect_Model').show();
    $('.InstanceCreate_Model').collapse('hide');

    //Disabled All Checkbox 
    $(".all_fields").prop('disabled', true);  

    //Enable Selected one
    $('.' + edit_instance_Id).prop('disabled', false);  

    var Edit_Form = $('#All_instance_form').serialize();

    //Creating Fake Url And Appending Data
    var fakeURL = "http://www.example.com/t.html?" +  Edit_Form;
    var createURL = new URL(fakeURL)


    var fetch_Edited_Id = createURL.searchParams.get('fetch_edit_Id');
    var fetch_Edited_feeheader_Id = createURL.searchParams.get('fetch_edit_feeheader_Id');
    var fetch_Edited_Gender = createURL.searchParams.get('fetch_edit_Gender');
    var fetch_Edited_applicable_to = createURL.searchParams.get('fetch_edit_applicable_to');
    var fetch_freeship = createURL.searchParams.get('fetch_edit_freeship');
    var fetch_Amount = createURL.searchParams.get('fetch_edit_Amount');
    var fetch_feestructure_Id = createURL.searchParams.get('fetch_edit_feestructure_Id');
    var fetch_batchmasterid = createURL.searchParams.get('fetch_edit_batchmasterid');

    //Assign Value To Editable Compoents
    $('#edit_InstanceId').val(fetch_Edited_Id);
    $('#edit_feeheader_Id').val(fetch_Edited_feeheader_Id);
    $('#edit_Gender').val(fetch_Edited_Gender);
    $('#edit_applicable_to').val(fetch_Edited_applicable_to);
    $('#edit_freeship').val(fetch_freeship);
    $('#edit_Amount').val(fetch_Amount);
    $('#edit_feestructure_Id').val(fetch_feestructure_Id);
    $('#edit_batchmasterid').val(fetch_batchmasterid);

});
//Instance Edit Close----------------------------------------------------------------------------------------------------



//Edit Submit----------------------------------------------------------------------------------------------------

$('#submit_editinstance').click(function(event){

    var EditData = $('#Edit_FormData').serializeArray();
    var batch_sel = $('#batch_sel').val();


    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./fee_management/fee_management_api.php?Edit_FeeMasterInstance='+'u',
        type:'POST',
        data: EditData,
        dataType: "json",
        success:function(edit_instance_res){  
            if(edit_instance_res == '200'){
                $.ajax({
                    url:'./fee_management/admission_fees.php?Generate_View='+'u',
                    type:'GET',
                    data: {batch_sel: batch_sel},
                    success:function(sd_logs){
                        $('#DisplayDiv').html(sd_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.success({
                            title: 'Success',
                            message: 'FeeMaster Instance Edited',
                        });

                                    
                        $('#add_batchmasterid').val(batch_sel);
                        
                        $('#batch_sel_import').val(batch_sel);
                    },
                });   

            }else{

                $.ajax({
                    url:'./fee_management/admission_fees.php?Generate_View='+'u',
                    type:'GET',
                    data: {batch_sel: batch_sel},
                    success:function(sd_logs){
                        $('#DisplayDiv').html(sd_logs);
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        iziToast.error({
                            title: 'Invalid Input All field is mandatory',
                            message: 'Enter All Details',
                        });

                                    
                        $('#add_batchmasterid').val(batch_sel);
                        
                        $('#batch_sel_import').val(batch_sel);
                    },
                });  

            }

        },//Close Success
    });// close Ajax 

});

//Instance Edit Submit Close----------------------------------------------------------------------------------------------------



$('#import_file_submit').on('click', function(event){
    event.preventDefault();

    $('#contact_dialog').modal('hide');
    let form = $('#import_file_form')[0];
    let data = new FormData(form);
    var batch_sel = $('#batch_sel').val();
    setTimeout(function(){
        $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        jQuery.ajax({
            url: './fee_management/fee_management_api.php?Add_FeeMasterInstance_InBulk=u',
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: data,
            dataType: "json",
            success: function (update_instance_res, textStatus, xhr) {
                
                    var UploadedFilePath = update_instance_res['UploadedFilePath'];
              
                    var i = 0;
                    var successString = '';
                    
            

                    update_instance_res['displayMessage'].forEach(function(entry) {
            
                            successString = successString.concat( update_instance_res['displayMessage'][i], "</br>");

                        i++;

                    });

                    if(successString != ''){
            
                        // getAddAlert(successString, data, false, '');
						iziToast.success({
							title: 'Update Log',
							message: successString,
						});
            
                    }

                    if(UploadedFilePath === 'NA' || UploadedFilePath === ''){
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");

                        alert('Not able to Create the log')
                    }else if(UploadedFilePath != '' || UploadedFilePath != 'NA'){
                        $("#loader").css("display", "none");
                        $("#DisplayDiv").css("display", "block");
                        window.open(UploadedFilePath, '_blank');
                    }



					jQuery.ajax({
                        url:'./fee_management/admission_fees.php?Generate_View='+'u',
						type: "GET",
                        data: {batch_sel:batch_sel},
						success:function(data){
							$('#DisplayDiv').html(data);
							$("#loader").css("display", "none");
							$("#DisplayDiv").css("display", "block");
                            $('#add_batchmasterid').val(batch_sel);
                            
                            $('#batch_sel_import').val(batch_sel);
							}
                            
					});

                            
                  


            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    
    }, 180);
});



$('#online_table').DataTable( {
    dom: 'Bifrtp',
    bPaginate:false,
    
    buttons: [
    {
        extend: 'excel',
        footer: true,
		title: "Download Format",
		text: 'Download Format',
        exportOptions : {
            columns : ':visible',
            format : {
                header : function (mDataProp, columnIdx) {
            var htmlText = '<span>' + mDataProp + '</span>';
            var jHtmlObject = jQuery(htmlText);
            jHtmlObject.find('div').remove();
            var newHtml = jHtmlObject.text();
            console.log('My header > ' + newHtml);
            return newHtml;
                }
            }
        }
    }
    ]
} );
</script>


<style>
.font_all{
    font-family: 'Josefin Sans', sans-serif;
}

input[type=text]{
border:0px;
border-bottom: 2px solid #3c8dbc;
}

.design_btn{
    background-color:white;
    color: black;
    border:2px solid #3c8dbc;

}

th{
    text-align: center;
    padding: 25px;
    border-bottom: 0;
    font-style: italic;
    font-weight:bold;
}

td{
    text-align:center;
}

.detailsinput{
    border-bottom: 2px solid #3c8dbc;
    font-weight: bold;
}

#sel_course{
    height: 34px;
    width: 200px;
    border:0px;
    border-bottom: 2px solid #3c8dbc;
    -webkit-appearance: none;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: 1ex;
    background-origin: content-box;
    background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIgogICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIgogICB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiCiAgIHhtbG5zOnN2Zz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgdmVyc2lvbj0iMS4xIgogICBpZD0ic3ZnMiIKICAgdmlld0JveD0iMCAwIDM1Ljk3MDk4MyAyMy4wOTE1MTgiCiAgIGhlaWdodD0iNi41MTY5Mzk2bW0iCiAgIHdpZHRoPSIxMC4xNTE4MTFtbSI+CiAgPGRlZnMKICAgICBpZD0iZGVmczQiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNyI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjAyLjAxNDUxLC00MDcuMTIyMjUpIgogICAgIGlkPSJsYXllcjEiPgogICAgPHRleHQKICAgICAgIGlkPSJ0ZXh0MzMzNiIKICAgICAgIHk9IjYyOS41MDUwNyIKICAgICAgIHg9IjI5MS40Mjg1NiIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXdlaWdodDpub3JtYWw7Zm9udC1zaXplOjQwcHg7bGluZS1oZWlnaHQ6MTI1JTtmb250LWZhbWlseTpzYW5zLXNlcmlmO2xldHRlci1zcGFjaW5nOjBweDt3b3JkLXNwYWNpbmc6MHB4O2ZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MXB4O3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICB4bWw6c3BhY2U9InByZXNlcnZlIj48dHNwYW4KICAgICAgICAgeT0iNjI5LjUwNTA3IgogICAgICAgICB4PSIyOTEuNDI4NTYiCiAgICAgICAgIGlkPSJ0c3BhbjMzMzgiPjwvdHNwYW4+PC90ZXh0PgogICAgPGcKICAgICAgIGlkPSJ0ZXh0MzM0MCIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXZhcmlhbnQ6bm9ybWFsO2ZvbnQtd2VpZ2h0Om5vcm1hbDtmb250LXN0cmV0Y2g6bm9ybWFsO2ZvbnQtc2l6ZTo0MHB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6Rm9udEF3ZXNvbWU7LWlua3NjYXBlLWZvbnQtc3BlY2lmaWNhdGlvbjpGb250QXdlc29tZTtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjFweDtzdHJva2UtbGluZWNhcDpidXR0O3N0cm9rZS1saW5lam9pbjptaXRlcjtzdHJva2Utb3BhY2l0eToxIj4KICAgICAgPHBhdGgKICAgICAgICAgaWQ9InBhdGgzMzQ1IgogICAgICAgICBzdHlsZT0iZmlsbDojMzMzMzMzO2ZpbGwtb3BhY2l0eToxIgogICAgICAgICBkPSJtIDIzNy41NjY5Niw0MTMuMjU1MDcgYyAwLjU1ODA0LC0wLjU1ODA0IDAuNTU4MDQsLTEuNDczMjIgMCwtMi4wMzEyNSBsIC0zLjcwNTM1LC0zLjY4MzA0IGMgLTAuNTU4MDQsLTAuNTU4MDQgLTEuNDUwOSwtMC41NTgwNCAtMi4wMDg5MywwIEwgMjIwLDQxOS4zOTM0NiAyMDguMTQ3MzIsNDA3LjU0MDc4IGMgLTAuNTU4MDMsLTAuNTU4MDQgLTEuNDUwODksLTAuNTU4MDQgLTIuMDA4OTMsMCBsIC0zLjcwNTM1LDMuNjgzMDQgYyAtMC41NTgwNCwwLjU1ODAzIC0wLjU1ODA0LDEuNDczMjEgMCwyLjAzMTI1IGwgMTYuNTYyNSwxNi41NDAxNyBjIDAuNTU4MDMsMC41NTgwNCAxLjQ1MDg5LDAuNTU4MDQgMi4wMDg5MiwwIGwgMTYuNTYyNSwtMTYuNTQwMTcgeiIgLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=");
}

#sel_course::-ms-expand {
	display: none;
}

.design_sel{
    height: 34px;
    border:0px;
    background-color: transparent;
    text-align: center;
    border-bottom: 2px solid #3c8dbc;
    -webkit-appearance: none;
    background-position: right center;
    background-repeat: no-repeat;
    background-size: 1ex;
    background-origin: content-box;
    background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIgogICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIgogICB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiCiAgIHhtbG5zOnN2Zz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgdmVyc2lvbj0iMS4xIgogICBpZD0ic3ZnMiIKICAgdmlld0JveD0iMCAwIDM1Ljk3MDk4MyAyMy4wOTE1MTgiCiAgIGhlaWdodD0iNi41MTY5Mzk2bW0iCiAgIHdpZHRoPSIxMC4xNTE4MTFtbSI+CiAgPGRlZnMKICAgICBpZD0iZGVmczQiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNyI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjAyLjAxNDUxLC00MDcuMTIyMjUpIgogICAgIGlkPSJsYXllcjEiPgogICAgPHRleHQKICAgICAgIGlkPSJ0ZXh0MzMzNiIKICAgICAgIHk9IjYyOS41MDUwNyIKICAgICAgIHg9IjI5MS40Mjg1NiIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXdlaWdodDpub3JtYWw7Zm9udC1zaXplOjQwcHg7bGluZS1oZWlnaHQ6MTI1JTtmb250LWZhbWlseTpzYW5zLXNlcmlmO2xldHRlci1zcGFjaW5nOjBweDt3b3JkLXNwYWNpbmc6MHB4O2ZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MXB4O3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICB4bWw6c3BhY2U9InByZXNlcnZlIj48dHNwYW4KICAgICAgICAgeT0iNjI5LjUwNTA3IgogICAgICAgICB4PSIyOTEuNDI4NTYiCiAgICAgICAgIGlkPSJ0c3BhbjMzMzgiPjwvdHNwYW4+PC90ZXh0PgogICAgPGcKICAgICAgIGlkPSJ0ZXh0MzM0MCIKICAgICAgIHN0eWxlPSJmb250LXN0eWxlOm5vcm1hbDtmb250LXZhcmlhbnQ6bm9ybWFsO2ZvbnQtd2VpZ2h0Om5vcm1hbDtmb250LXN0cmV0Y2g6bm9ybWFsO2ZvbnQtc2l6ZTo0MHB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6Rm9udEF3ZXNvbWU7LWlua3NjYXBlLWZvbnQtc3BlY2lmaWNhdGlvbjpGb250QXdlc29tZTtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjFweDtzdHJva2UtbGluZWNhcDpidXR0O3N0cm9rZS1saW5lam9pbjptaXRlcjtzdHJva2Utb3BhY2l0eToxIj4KICAgICAgPHBhdGgKICAgICAgICAgaWQ9InBhdGgzMzQ1IgogICAgICAgICBzdHlsZT0iZmlsbDojMzMzMzMzO2ZpbGwtb3BhY2l0eToxIgogICAgICAgICBkPSJtIDIzNy41NjY5Niw0MTMuMjU1MDcgYyAwLjU1ODA0LC0wLjU1ODA0IDAuNTU4MDQsLTEuNDczMjIgMCwtMi4wMzEyNSBsIC0zLjcwNTM1LC0zLjY4MzA0IGMgLTAuNTU4MDQsLTAuNTU4MDQgLTEuNDUwOSwtMC41NTgwNCAtMi4wMDg5MywwIEwgMjIwLDQxOS4zOTM0NiAyMDguMTQ3MzIsNDA3LjU0MDc4IGMgLTAuNTU4MDMsLTAuNTU4MDQgLTEuNDUwODksLTAuNTU4MDQgLTIuMDA4OTMsMCBsIC0zLjcwNTM1LDMuNjgzMDQgYyAtMC41NTgwNCwwLjU1ODAzIC0wLjU1ODA0LDEuNDczMjEgMCwyLjAzMTI1IGwgMTYuNTYyNSwxNi41NDAxNyBjIDAuNTU4MDMsMC41NTgwNCAxLjQ1MDg5LDAuNTU4MDQgMi4wMDg5MiwwIGwgMTYuNTYyNSwtMTYuNTQwMTcgeiIgLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=");
}

.design_sel::-ms-expand {
	display: none;
}

.Update_Link {
    font-weight:bold;
    text-decoration:underline;
    cursor:pointer;	
    text-shadow: 1px 1px 1px #337ab7;
}
.Update_Link:hover{
    font-size:13px;
}

.fixed-padding {
  padding-right: 0px !important;
}

table.dataTable thead th {
  border-bottom: 0;
}
table.dataTable tfoot th {
  border-top: 0;
}
</style>
