<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 17-10-2019
 * Time: 12:00 PM
 */

session_start();
include_once '../../config/database.php';


$root_Id = $_POST['c_id'];


?>

<div class="box">

  <div class="box-header">
    <h3 align=center class="box-title">Manage Page Sequence</h3>
    <br />
    <h4 align=center class="box-title"><?php echo $fetch_users['fullName']; ?></h4>
  </div>

  <div class="box-body">
    <button class="btn"
            style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:15px;"
            onclick="getPage('./user_management/control_url.php');">Go Back
    </button>
    <form id="SMS_FormData"  method="POST" onsubmit="return false">

    <input style="display:none;"
             class="form-control input-md"
             type="text"
             name="root_Id"
             id="root_Id"
             value="<?php echo $root_Id; ?>"
             readonly>


      <table id="header_list" class="table table-bordered table-striped" style="width:100%">
        <thead>
        <tr>
      
          <th>Path</th>
          <th>Name</th>
          <th>Sequence No</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $header_result = QUERY::run( "SELECT
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
      ) AS p_head,
      child.parent_sequence
      FROM
        setup_links child
      LEFT JOIN
        setup_links parent ON parent.Id = child.parent
      LEFT JOIN
        setup_links gran ON gran.Id = parent.parent
      LEFT JOIN
        setup_links greatgran ON greatgran.Id = gran.parent
      WHERE
        parent.header IS NOT NULL AND child.parent = '$root_Id' " );

        $id = 0;

        foreach ( $header_result as $header ) {
          $tr_data = '
        <tr>
          
          <td style="vertical - align:middle;text - align:center">' . $header['PATH'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['c_head'] . '</td> 
          
          <td>
          
            <input type="hidden"   name="page_Id[]" value="' . $header['c_id'] . '" >

            <input type="number" class="form-control"  name="c_sequence[]" placeholder="Enter Sequence" value="' . $header['parent_sequence'] . '"  required>
          
          </td>

        </tr>
          ';
        
          echo $tr_data;


          
          ++$id;
        }
        ?>
        </tbody>
      </table>
      <div class="col-sm-offset-6 col-sm-4" style="text-align:right; padding-bottom:1em;">
        <button class="btn btn-success input-lg"
                style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:16px;"
                type="submit" id="submit_smsinstance">Submit form
        </button>
      </div>
    </form>
  </div>
  <!-- /.box-body -->
</div>

<script>

  

  $('#submit_smsinstance').click(function(e){
    e.preventDefault();
    var root_Id = $('#root_Id').val();

    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");

    var FormData = $('#SMS_FormData').serializeArray();
    
    $.ajax({
        url:'./user_management/user_management_api.php?Sequencing_Pages='+'u',
        type:'POST',
        data: FormData,
        dataType: "json",
        success:function(srh_response){

            if(srh_response['status'] == 'success') {
                alert('Access Successfully Changed');
                $.ajax({
                        url:'./user_management/control_url_sequence.php',
                        type:'POST',
                        data: {c_id:root_Id},
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
