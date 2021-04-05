<?php
//Session File
include_once '../SessionInfo.php';
//Database File
include_once '../../config/database.php';


?>

<div class="col-md-12"><h4><i><b>  Communication Student</b></i>
</h4></div>



<div class="container">
    <div class="row">
       <div class="col-md-12"> 



        <form id="ReceiptForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Select List Option:</label>
                </div>
                
                <div class="col-md-2"> 
                    <select name="list_sel" id="list_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select ----</option>
                                <option value="BatchSelected" <?php if($_GET['list_sel'] == 'BatchSelected'){ echo 'Selected'; } ?>  class="Batch">Batch</option>
                                <option value="GroupSelected" <?php if($_GET['list_sel'] == 'GroupSelected'){ echo 'Selected'; } ?>  class="Group">Group</option>

                                
                    </select>
                    <input type="hidden" name="list_name" id="list_name" value="">
                </div>

                <div class="col-md-1 BatchView" style="text-align:center;">
                    <label for="email">Batch:</label>
                </div>
                
                <div class="col-md-3 BatchView"> 
                    <select name="batch_sel" id="batch_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select Batch ----</option>
                                <?php 
                                    $batch_fetch = mysqli_query($mysqli,"SELECT setup_batchmaster.* FROM setup_batchmaster JOIN setup_programmaster ON setup_programmaster.Id = setup_batchmaster.programmaster_Id JOIN setup_streammaster ON setup_streammaster.Id = setup_programmaster.streammaster_Id WHERE setup_streammaster.sectionmaster_Id = '$SectionMaster_Id' AND setup_batchmaster.academicyear_Id = '$Acadmic_Year_ID'  ");
                                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                                    <option value="<?php echo $r_batch['Id']; ?>" <?php if($r_batch['Id'] == $_GET['batch_sel']){ echo 'Selected'; } ?>  class="<?php echo $r_batch['batch_name']; ?>"><?php echo $r_batch['batch_name']; ?></option>
                                <?php }   ?>
                                

                                
                    </select>
                    <input type="hidden" name="batch_name" id="batch_name" value="">
                </div>

                <div class="col-md-1 GroupView" style="text-align:center;">
                    <label for="email">Group:</label>
                </div>
                
                <div class="col-md-3 GroupView"> 
                    <select name="group_sel" id="group_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select Group ----</option>
                                <?php 
                                    $batch_fetch = mysqli_query($mysqli,"SELECT comm_group_instance.id As Id, comm_group_instance.group_name FROM `comm_group_instance` JOIN setup_batchmaster ON comm_group_instance.batchMasterId = setup_batchmaster.Id WHERE setup_batchmaster.academicYear_Id = '$Acadmic_Year_ID'");
                                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                                    <option value="<?php echo $r_batch['Id']; ?>" <?php if($r_batch['Id'] == $_GET['group_sel']){ echo 'Selected'; } ?>  class="<?php echo $r_batch['group_name']; ?>"><?php echo $r_batch['group_name']; ?></option>
                                <?php }   ?>
                                

                                
                    </select>
                </div>


                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Send To:</label>
                </div>
                
                <div class="col-md-2"> 
                    <select name="contact_sel" id="contact_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select ----</option>
                                <option value="Father" <?php if($_GET['contact_sel'] == 'Father'){ echo 'Selected'; } ?>  class="Father">Father</option>
                                <option value="Mother" <?php if($_GET['contact_sel'] == 'Mother'){ echo 'Selected'; } ?>  class="Mother">Mother</option>
                                <option value="Student" <?php if($_GET['contact_sel'] == 'Student'){ echo 'Selected'; } ?>  class="Student">Student</option>
                    </select>
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="button" name="search" id="search_access" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
        </form> 

        <script>
        
        $(".GroupView").hide();
        $(".BatchView").hide();

        $(document).on('change', '#list_sel', function(event) {
            var list_sel = $(this).val(); // the selected options’s value
            if(list_sel == 'BatchSelected'){
                $('.BatchView').show();
                $('.GroupView').hide();
            }else if(list_sel == 'GroupSelected'){
                $('.GroupView').show();
                $('.BatchView').hide();
            }else{
                $('.GroupView').hide();
                $('.BatchView').hide();
            }         
            return false;
        });

        </script>



<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){

        extract($_POST);
        $batch_sel = $_GET['batch_sel'];
        $batch_name = $_GET['batch_name'];

        $list_sel = $_GET['list_sel'];
        $list_name = $_GET['list_name'];

        $contact_sel = $_GET['contact_sel'];

        $group_sel = $_GET['group_sel'];

        if($list_sel == 'BatchSelected'){

             $Studentlistquery = "SELECT DISTINCT user_studentregister.Id AS SR_Id, UPPER(
                user_studentregister.Student_Name
              ) AS Student_Name,
              user_studentbatchmaster.Id AS SBM_Id,
              user_studentbatchmaster.roll_no AS Roll_no,
              user_studentbatchmaster.`Div` AS `DIV`,
              setup_batchmaster.batch_name,
              user_studentbatchmaster.admission_no,
              user_studentregister.student_Id,
              user_studentdetails.fathers_Contact,
              user_studentdetails.mothers_Contact,
              user_studentregister.mobile_no As registeredMobileNo,
              user_studentregister.email_address As registeredEmailAddress
            FROM
            user_studentregister
            INNER JOIN
            user_studentbatchmaster ON user_studentbatchmaster.studentRegister_Id = user_studentregister.Id
            INNER JOIN
            setup_batchmaster ON user_studentbatchmaster.batchMaster_Id = setup_batchmaster.Id
            INNER JOIN
            setup_academicyear ON setup_batchmaster.academicyear_Id = setup_academicyear.Id
            INNER JOIN
            user_studentdetails ON user_studentregister.Id = user_studentdetails.studentregister_Id
            WHERE
            user_studentbatchmaster.Admission_Status = 1
            AND setup_batchmaster.Id = '$batch_sel' AND setup_academicyear.Id = '$Acadmic_Year_ID'
            ORDER BY
            user_studentbatchmaster.roll_no ";

            
        }else{

            $Studentlistquery = "SELECT DISTINCT
            user_studentregister.Id,
            UPPER(
              user_studentregister.Student_Name
            ) AS Student_Name,
            user_studentregister.registeredMobileNo,
            user_studentregister.registeredEmailAddress,
            user_studentbatchmaster.roll_no AS Roll_no,
            user_studentbatchmaster.`Div` AS `DIV`,
            setup_batchmaster.batch_name,
            user_studentdetails.fathers_Contact,
            user_studentdetails.mothers_Contact,
            user_studentbatchmaster.admission_no,
            user_studentregister.GSEmail,
            user_studentregister.student_Id,
            user_studentregister.*,
            user_studentbatchmaster.Id AS SBM_Id
          FROM
            user_studentbatchmaster
          JOIN
            comm_group_instance_details ON comm_group_instance_details.student_batchMaster_id = user_studentbatchmaster.Id
          JOIN
            user_studentregister ON user_studentregister.Id = user_studentbatchmaster.studentRegister_Id
          JOIN
            user_studentdetails ON user_studentdetails.studentregister_Id = user_studentregister.Id
          JOIN
            setup_batchmaster ON setup_batchmaster.Id = user_studentbatchmaster.batchMaster_Id
          WHERE
            comm_group_instance_details.group_instance_id = '$group_sel'  ";


        }
       
        // echo $applicationquery;
        $Studentlist_q = mysqli_query($mysqli, $Studentlistquery);

        $rows_Studentlist = mysqli_num_rows($Studentlist_q);




    ?>
        <?php if($list_sel == 'BatchSelected'){ ?> <script>$('.BatchView').show();</script> <?php }else{ ?> <script>$('.GroupView').show();</script> <?php } ?>

        <div class="panel panel-info">
        <div class="panel-heading">Student List</div>
        <div class="panel-body">


        <div class="col-md-12 col-sm-12">
        <form id="StudentListForm">  
        
        <input type="hidden" name="return_batch_sel" id="batch_sel" class="form-control" value="<?php echo $_GET['batch_sel']; ?>">
        <input type="hidden" name="return_batch_name" id="batch_name" class="form-control" value="<?php echo $_GET['batch_name']; ?>">
        <input type="hidden" name="return_list_sel" id="list_sel" class="form-control" value="<?php echo $_GET['list_sel']; ?>">
        <input type="hidden" name="return_list_name" id="list_name" class="form-control" value="<?php echo $_GET['list_name']; ?>">
        <input type="hidden" name="return_contact_sel" id="contact_sel" class="form-control" value="<?php echo $_GET['contact_sel']; ?>">
        <input type="hidden" name="return_group_sel" id="group_sel" class="form-control" value="<?php echo $_GET['group_sel']; ?>">

        <table id="CommunicationReportTable" class="table table-striped table-hover"> 
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Admission No</th>
                    <th>Roll No</th>
                    <th>Division</th>
                    <?php if($list_sel == 'BatchSelected'){ ?> 
                        <th>Fees Paid</th>
                    <?php } ?>
                    <th>Student Id</th>
                    <th>Name</th>
                    <?php if($contact_sel == 'Student'){ ?><th>Student Mobile No</th> <?php } ?>
                    <?php if($contact_sel == 'Father'){ ?><th>Fathers Mobile No</th> <?php } ?>
                    <?php if($contact_sel == 'Mother'){ ?><th>Mothers Mobile No</th> <?php } ?>
                    <th>Email</th>
                    
                    <th>Operations <br>
                        <input type="checkbox" class="subcheck" id="check">
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if($rows_Studentlist > '0'){
                        $i = 1;
                        While($r_Studentlist = mysqli_fetch_array($Studentlist_q)){

                            $Fees_payment_query = mysqli_query($mysqli,"SELECT fee_receipts.amount FROM fee_receipts JOIN setup_batchmaster ON fee_receipts.batchMaster_Id = setup_batchmaster.Id WHERE fee_receipts.feeheadertype = 1 AND fee_receipts.applicationdetails_id = '$r_Studentlist[AD_Id]' group By fee_receipts.feeheadertype ");
                            $row_fees_payment = mysqli_num_rows($Fees_payment_query);

                            if($row_fees_payment > 0){
                                $fees_payment_count = mysqli_fetch_array($Fees_payment_query);                   
                            }else{
                                $fees_payment_count['amount'] = 0;
                            }
                            
                ?>
                
                <tr>
                    <td><?php echo $i; ?></td> 
                    <td><?php echo $r_Studentlist['admission_no']; ?></td> 
                    <td><?php echo $r_Studentlist['Roll_no']; ?></td> 
                    <td><?php echo $r_Studentlist['DIV']; ?></td>
                    
                    <?php if($list_sel == 'BatchSelected'){ ?> 
                        <td><?php echo $fees_payment_count['amount']; ?></td>
                    <?php } ?>

                    <td><?php echo $r_Studentlist['student_Id']; ?></td>
                    <td><?php echo $r_Studentlist['Student_Name']; ?></td>
                    <?php if($contact_sel == 'Student'){ ?><td><?php echo $r_Studentlist['registeredMobileNo']; ?></td> <?php } ?>
                    <?php if($contact_sel == 'Father'){ ?><td><?php echo $r_Studentlist['fathers_Contact']; ?></td> <?php } ?>
                    <?php if($contact_sel == 'Mother'){ ?><td><?php echo $r_Studentlist['mothers_Contact']; ?></td> <?php } ?>
                    <td><?php echo $r_Studentlist['registeredEmailAddress']; ?></td>

                    <td>

                        <div class="pretty p-icon p-smooth">
                                <input type="checkbox" class="subcheck sel_box" id="check<?php echo $r_Studentlist['SBM_Id']; ?>" name="User_Id[]" value="<?php echo $r_Studentlist['SBM_Id']; ?>" >
                                    <div class="state p-success">
                                        <i class="icon fa fa-check"></i>
                                        <label></label>
                                    </div>
                        </div> 
                        
                    </td>
                    
                </tr>
                    
                        <?php $i++; } // close while?>

                <?php } //close if ?>
            </tbody>


        </table>
        </form>

        </div><!--Close Col-md-9-->

        </div><!--Panel BOdy Close-->
        <div class="panel-footer" style="text-align: center;"> 
            <button type="button" class="btn btn-primary" id="save_sms_list">Save Selected Student(SMS)</button>
            <button type="button" class="btn btn-primary" id="save_email_list">Save Selected Student(EMAIL)</button>
            <!-- <button class="btn btn-primary" id="savingSmsList">Fetch JS API(Testing)</button> -->
        </div>
        </div>





        <script>
$('#CommunicationReportTable').DataTable( {
        dom: 'Bifrtp',
		bPaginate:false,
		
        buttons: [
		{
            extend: 'excel',
            footer: true,
			title: "Communication Student list ",
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
        },
		{
            extend: 'pdfHtml5',
			footer: true,
			title: "Communication Student list",
            text: 'PDF',
            customize: function ( doc ) {
				var cols = [];
				cols[0] = {text: new Date().toDateString(), alignment: 'right', margin:[0,0,20] };
				var objFooter = {};
				objFooter['columns'] = cols;
				doc['header']=objFooter;
   
			},
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
 $('#CommunicationReportTable').dataTable().yadcf([
    {column_number: 3, filter_match_mode: "exact"}
  ]);



</script>

    <?php } // close isset ?> 
<!-- -------------------------------------------------------------------------------------------------- -->
            
        </div>
    </div>
</div>



<script>



$('#check').click(function(){
    if($(this).prop("checked") == false){
        $(".sel_box").removeAttr('checked');
    
    }else{

        $(".sel_box").prop('checked', true);

    }   
});


//--------------------------------------------------------------------------------------------------------------------------------------
            //New Receipt Button Clicked
$('#search_access').click(function(e){
    e.preventDefault();

    

    if($('#list_sel').val() == 'BatchSelected'){
        var batchName = $('select[name="batch_sel"]').find(':selected').attr('class');
    }else if($('#list_sel').val() == 'GroupSelected'){
        var batchName = $('select[name="group_sel"]').find(':selected').attr('class');
    }
    var ListName = $('select[name="list_sel"]').find(':selected').attr('class');

    $('#batch_name').val(batchName);
    $('#list_name').val(ListName);

    var formdata = $('#ReceiptForm').serializeArray();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./communication/communication_student.php?Generate_View='+'u',
        type:'GET',
        data: formdata,
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
   });



});



$('#save_sms_list').click(function (e) {
    e.preventDefault();
    var data = $('#StudentListForm').serializeArray();
    $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        $.ajax({
            url:'./communication/Communication_Group_Student_SMS.php',
            type:'POST',
            data: data,
            success:function(res){ 
                $('#DisplayDiv').html(res);
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");
            }, 
        });//close ajax

});




$('#save_email_list').click(function (e) {
    e.preventDefault();
    var data = $('#StudentListForm').serializeArray();
    $("#loader").css("display", "block");
        $("#DisplayDiv").css("display", "none");
        $.ajax({
            url:'./communication/Communication_Group_Student_EMAIL.php',
            type:'POST',
            data: data,
            success:function(res){ 
                $('#DisplayDiv').html(res);
                $("#loader").css("display", "none");
                $("#DisplayDiv").css("display", "block");
            }, 
        });//close ajax

});



</script>