<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit','-1');
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();

include_once '../../config/database.php';



?>

<div class="col-md-12"><h4><i><b>  Batch Access</b></i>
     <a onclick="getPage('./user_management/homescreen.php');">
        <button class="btn btn-danger pull-right">Return
        </button>
     </a>
</h4></div>



<div class="container">
    <div class="row">
       <div class="col-md-12"> 



        <form id="ReceiptForm">
       <div class="form-group form-inline">
    
        <div class="row">

                <div class="col-md-1" style="text-align:center;">
                    <label for="email">Batch:</label>
                </div>
                
                <div class="col-md-3"> 
                    <select name="batch_sel" id="batch_sel" class="form-control" style="margin-right:50px;" required>
                                <option value="">---- Select Batch ----</option>
                                <?php 
                                    $batch_fetch = mysqli_query($mysqli,"SELECT * FROM `setup_batchmaster` Where academicYear_Id = '$Acadmic_Year_ID'  ORDER BY `academicYear_Id` DESC ");
                                    while($r_batch = mysqli_fetch_array($batch_fetch)){ ?>
                                    <option value="<?php echo $r_batch['Id']; ?>" <?php if($r_batch['Id'] == $_GET['batch_sel']){ echo 'Selected'; } ?>  class="<?php echo $r_batch['Batch_Name']; ?>"><?php echo $r_batch['Batch_Name']; ?></option>
                                <?php }   ?>
                                

                                
                    </select>
                    <input type="hidden" name="batch_name" id="batch_name" value="">
                </div>


                <div class="col-md-2" style="text-align:center;">      
                    <input type="button" name="search" id="search_access" value="Search" class="btn btn-primary"/></div>
                </div>


        </div><!--Row1 Close-->
        
        </div>
        </form> 



<!-- -------------------------------------------------------------------------------------------------- -->
<?php 
    if(isset($_GET['Generate_View'])){

        extract($_POST);
        $batch_sel = $_GET['batch_sel'];
        $batch_name = $_GET['batch_name'];

        $accessquery = "SELECT user_stafflogin.username as fullName, user_stafflogin.Id AS Staff_LoginId, comm_batch_access.id AS batchaccessid, comm_batch_access.batchMaster_Id AS BM_Id, user_stafflogin.username AS UserName FROM user_stafflogin LEFT JOIN comm_batch_access ON comm_batch_access.userId = user_stafflogin.Id AND comm_batch_access.batchMaster_Id = '$batch_sel' WHERE 1 GROUP BY user_stafflogin.Id  ";

        // echo $applicationquery;
        $access_list_q = mysqli_query($mysqli, $accessquery);



    ?>


<div class="col-md-10">
    <form id="ApplicationForm">
    <input type="hidden" id="batch_sel_val" name="batch_sel" value="<?php echo $batch_sel; ?>" >
    <table class="table table-striped" id="ApplicationTable" style="overflow-y: scroll;overflow-x: scroll;">
        <thead>
            <tr><th style="text-align:center;" colspan="4">batch Name: <span class="text-primary"><?php echo $batch_name; ?></span></th>
            </th>
            </tr>
            <tr><th>Sr No.</th><th style="text-align:left;">Teacher Name</th><th>UserName</th><th>Operations <br>
               <input type="checkbox" class="subcheck" id="check">
            </th>
            </tr>
        </thead>
        <tbody>

        <?php 
                
                                      
             
                    
                    $row_access_list = mysqli_num_rows($access_list_q);

                    if($row_access_list > 0){

                        

                        $i = 1; while($r_access_list = mysqli_fetch_array($access_list_q)){ 
                  
                        ?> 

                            

                            <tr class="tr<?php echo $r_access_list['Staff_LoginId']; ?>">    
                                <td><?php echo $i; ?></td>
                                <td><?php echo $r_access_list['fullName']; ?></td>
                                <td><?php echo $r_access_list['UserName']; ?></td>
                              
                                <td>
                                 
                                    


                                    <div class="pretty p-icon p-smooth">
                                             <input type="checkbox" class="subcheck sel_box" id="check<?php echo $r_access_list['Staff_LoginId']; ?>" name="check[]" value="<?php echo $r_access_list['Staff_LoginId']; ?>"  <?php if(!empty($r_access_list['BM_Id'])){ echo 'checked'; } ?> >
                                                <div class="state p-success">
                                                    <i class="icon fa fa-check"></i>
                                                    <label></label>
                                                </div>
                                    </div> 
                                     
                                </td>

                            </tr>  

                                <?php 
                        $i++; }

                    }//row close
                ?>

                
            
  


                      
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2"></th>
                <th><button type="button" class="btn btn-success"id="Submit_Access_User">Allow Access</button>                         
            </tr>
        </tfoot>
    
    </table>
    </form>
</div> <!--Col Close-->


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



$('#Submit_Access_User').click(function(e){
    e.preventDefault();
    var batch_sel = $('#batch_sel_val').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#ApplicationForm').serializeArray();
    
    $.ajax({
        url:'./user_management/user_management_api.php?Allow_Batch_Access='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Access Successfully');
                $.ajax({
                        url:'./user_management/control_batch_access.php?Generate_View='+'u',
                        type:'GET',
                        data: {batch_sel:batch_sel},
                        success:function(srh_response){
                            $('#DisplayDiv').html(srh_response);
                            $("#loader").css("display", "none");
                            $("#DisplayDiv").css("display", "block");
                        },
                });
                    
            }


        },
   });



});



//--------------------------------------------------------------------------------------------------------------------------------------
            //New Receipt Button Clicked
$('#search_access').click(function(e){
    e.preventDefault();

    var batchName = $('select[name="batch_sel"]').find(':selected').attr('class');

    $('#batch_name').val(batchName);

    var formdata = $('#ReceiptForm').serializeArray();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    
    $.ajax({
        url:'./user_management/control_batch_access.php?Generate_View='+'u',
        type:'GET',
        data: formdata,
        success:function(srh_response){
            $('#DisplayDiv').html(srh_response);
            $("#loader").css("display", "none");
            $("#DisplayDiv").css("display", "block");
        },
   });



});


</script>