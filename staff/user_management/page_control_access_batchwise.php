<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 17-10-2019
 * Time: 12:00 PM
 */

session_start();
include_once '../../config/database.php';
$SectionMaster_Id = $_SESSION['schoolzone']['SectionMaster_Id'];
$usertype = $_POST['usertype'];
$batch_sel = $_POST['batch_sel'];


?>

<div class="box">

  <div class="box-header">
    <h3 align=center class="box-title">Student/Candidate Control Panel</h3>
    <br />
    <h4 align=center class="box-title"><?php echo $fetch_users['fullName']; ?></h4>
  </div>

  <div class="box-body">
    <button class="btn"
            style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:15px;"
            onclick="getPage('./user_management/student_candidate_control_access.php');">Go Back
    </button>
    <form id="SMS_FormData"  method="POST" onsubmit="return false">

      <input style="display:none;"
             class="form-control input-md"
             type="text"
             name="batch_sel"
             id="batch_sel"
             value="<?php echo $batch_sel; ?>"
             readonly>


      <input style="display:none;"
             class="form-control input-md"
             type="text"
             name="usertype"
             id="usertype"
             value="<?php echo $usertype; ?>"
             readonly>

      <table id="header_list" class="table table-bordered table-striped" style="width:100%">
        <thead>
        <tr>
      
          <th width="15%">Path</th>
          <th width="15%" style="text-align:center">Immediate Parent</th>
          <th width="15%">Name</th>
          <th width="15%">Access Status</th>
          <th width="5%"><input type="checkbox" id="master"><br />Control Access</th>
        </tr>
        </thead>
        <tbody>

        <?php

        $q = "SELECT
        child.Id AS c_id,
        CONCAT(
          (
            CASE WHEN gran.header IS NULL THEN '' ELSE CONCAT('/',
            gran.header)
          END
        ),
        (
          CASE WHEN parent.header IS NULL THEN '' ELSE CONCAT('/',
          parent.header)
        END
      ),
      '/',
      child.header
      ) AS PATH,
      (
        CASE WHEN parent.header IS NULL AND child.url IS NULL THEN '--root--' WHEN parent.header IS NOT NULL AND child.url IS NULL THEN '-subroot-' ELSE child.url
      END
      ) AS c_url,
      child.header AS c_head,
      (
        CASE WHEN parent.header IS NULL THEN '-' ELSE parent.header
      END
      ) AS p_head
      FROM
        setup_links child
      LEFT JOIN
        setup_links parent ON parent.Id = child.parent
      LEFT JOIN
        setup_links gran ON gran.Id = parent.parent
      LEFT JOIN
        setup_links greatgran ON greatgran.Id = gran.parent
         JOIN setup_modulemapping ON setup_modulemapping.modulelist_Id = child.modulelist_Id
      WHERE
        parent.header IS NOT NULL AND child.link_user_type = '$usertype' AND child.access_type IN (0,3,4)  AND setup_modulemapping.sectionmaster_Id = '$SectionMaster_Id' AND  setup_modulemapping.userType_Id = '$usertype' ";

        $header_result = QUERY::run( $q );

        $id = 0;

        foreach ( $header_result as $header ) {
          $tr_data = '
        <tr>
          
          <td style="vertical - align:middle;text - align:center">' . $header['PATH'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['p_head'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['c_head'] . '</td> ';

          $users_func = QUERY::run('SELECT 1 
                                  FROM
                                    `setup_batchmaster`
                                    LEFT JOIN `batchwise_setup_links_access` ON `setup_batchmaster`.`Id` = `batchwise_setup_links_access`.`batchmaster_Id` 
                                  WHERE
                                    `setup_batchmaster`.`Id` = ?
                                    AND `batchwise_setup_links_access`.`function_Id` = ?',
                                  [ $batch_sel, $header['c_id'] ] )->fetch();

          if ( $users_func )
            $tr_data .= "
            <td>Activated</td>
            <td><input type='checkbox' class='sub_chk' checked name='check[]' value='".$header['c_id']."'></td>";
          else
            $tr_data .= "
            <td>Disabled</td>
            <td><input type='checkbox' class='sub_chk' name='check[]' value='".$header['c_id']."'></td>";

          echo $tr_data;
          ++$id;
        }
        ?>
        </tbody>
      </table>
      <div class="col-sm-offset-6 col-sm-4" style="text-align:right; padding-bottom:1em;">
        <button class="btn btn-success input-lg"
                style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:16px;"
                type="button" id="submit_smsinstance">Submit form
        </button>
      </div>
    </form>
  </div>
  <!-- /.box-body -->
</div>

<script>

  
$('#header_list').DataTable( {
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

  $('#header_list').dataTable().yadcf([
    {column_number: 1, filter_match_mode: "exact"},{column_number: 3, filter_match_mode: "exact"}
  ]);

  $(function () {
    $('#master').on('click', function (e) {
      if ($(this).is(':checked', true))
      {
        $(".sub_chk").prop('checked', true);
      }
      else
      {
        $(".sub_chk").prop('checked', false);
      }
    });
  });

  $('#submit_smsinstance').click(function(e){
    e.preventDefault();
    var usertype = $('#usertype').val();
    var batch_sel = $('#batch_sel').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#SMS_FormData').serializeArray();
    
    $.ajax({
        url:'./user_management/user_management_api.php?BatchWise_PageAccess='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Access Successfully Changed');
                $.ajax({
                        url:'./user_management/page_control_access_batchwise.php',
                        type:'POST',
                        data: {batch_sel:batch_sel, usertype:usertype},
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
</script>
