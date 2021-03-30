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

if ( isset( $_POST['user_stafflogin_id'] ) )
{
  $user_stafflogin_id = $_POST['user_stafflogin_id'];
  $fetch_users = QUERY::run('SELECT *
                              FROM
                                `user_stafflogin`
                    
                              WHERE `user_stafflogin`.`Id` = ?', [ $user_stafflogin_id ] )->fetch();
}
if ( isset( $_POST['admin_id'] ) ) {
  $user_stafflogin_id = $_POST['admin_id'];
  $reg_no             = $_POST['r'];
  $chk                = $_POST['c'];

  $delete_access_query = 'DELETE FROM
                              setup_links_access 
                              WHERE
                                user_id = ?';
  $stmt = $database->prepare( $delete_access_query );
  $stmt->execute( [ $user_stafflogin_id ] );

  foreach ( $reg_no as $key => $value ) {
    if ( isset( $chk[$value] ) ) {
      $is_users_func_activated = QUERY::run('SELECT 1 
                                              FROM
                                                `user_stafflogin`
                                                LEFT JOIN `setup_links_access` 
                                                    ON `user_stafflogin`.`Id` = `setup_links_access`.`user_id` 
                                              WHERE
                                                `user_stafflogin`.`Id` = ?
                                                AND `setup_links_access`.`function_id` = ?',
                                              [ $user_stafflogin_id, $value ] )->fetch();

      $is_users_func_parent_activated = QUERY::run( '
										 SELECT
                       `parent`.`Id` AS `QQ1`,
											 `gran`.`Id` AS `QQ2`,
											 `greatgran`.`Id` AS `QQ3`,
											 `greateSquare`.`Id` AS `QQ4`,
											 `greateCube`.`Id` AS `QQ5`,
											 `greatBiquadrate`.`Id` AS `QQ6`,
											 `greateQuintic`.`Id` AS `QQ7`
                     FROM
                       `setup_links` `child`
                       LEFT JOIN `setup_links` `parent`    ON `parent`.`Id`    = `child`.`parent` 
                       LEFT JOIN `setup_links` `gran`      ON `gran`.`Id`      = `parent`.`parent`
                       LEFT JOIN `setup_links` `greatgran` ON `greatgran`.`Id` = `gran`.`parent`
                         
                       LEFT JOIN `setup_links` `greateSquare`    ON `greateSquare`.`Id`    = `greatgran`.`parent` 
                       LEFT JOIN `setup_links` `greateCube`      ON `greateCube`.`Id`      = `greateCube`.`parent`
                       LEFT JOIN `setup_links` `greatBiquadrate` ON `greatBiquadrate`.`Id` = `greatBiquadrate`.`parent`
                       LEFT JOIN `setup_links` `greateQuintic`   ON `greateQuintic`.`Id`   = `greateQuintic`.`parent`
											 WHERE `child`.`Id` = ? ', [ $value ] )->fetch();

      foreach ( $is_users_func_parent_activated as $k => $v )
      {
        if ($v) {
          $is_users_func_activ = QUERY::run( 'SELECT 1 
                                              FROM
                                                `user_stafflogin`
                                                LEFT JOIN `setup_links_access` 
                                                    ON `user_stafflogin`.`Id` = `setup_links_access`.`user_id` 
                                              WHERE
                                                `user_stafflogin`.`Id` = ?
                                                AND `setup_links_access`.`function_id` = ?',
                                             [ $user_stafflogin_id, $v ] )->fetch();
          if ( !$is_users_func_activ )
          {
            $insert_access_q = 'INSERT INTO 
                                `setup_links_access` 
                                  ( 
                                    `function_id`,
                                    `user_id`
                                  )
                                VALUES ( ?, ? )';


            $stmt            = $database->prepare( $insert_access_q );
            $stmt->execute( array( $v, $user_stafflogin_id ) );
          }
        }
      }

      if ( !$is_users_func_activated ) {
        $insert_access_query = 'INSERT INTO 
                                setup_links_access 
                                  ( 
                                    function_id,
                                    user_id
                                  )
                                VALUES ( ?, ? )';
        $stmt                = $database->prepare( $insert_access_query );
        $stmt->execute( array( $value, $user_stafflogin_id ) );
      }
    }
//    else {
//      $delete_access_query = 'DELETE FROM
//                              setup_links_access
//                              WHERE
//                                user_id = ?
//                                AND function_id = ?';
//      $stmt                = $database->prepare( $delete_access_query );
//      $stmt->execute( array( $user_stafflogin_id, $value ) );
//    }
  }
  $fetch_users = QUERY::run('SELECT *
                              FROM
                                user_stafflogin
                                
                              WHERE user_stafflogin.Id = ?',
                              [ $user_stafflogin_id ] )->fetch();

  echo '<div class="row justify-content-md-center">
          <section style="width: 20%;min-height:0px;" class="content">
            <div class="alert alert-success alert-dismissible" style="text-align: center;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Control Access</h4>
                        Changes Done.
                      </div>
            </section>
        </div>';
}
?>

<div class="box">

  <div class="box-header">
    <h3 align=center class="box-title">User Access Control Panel</h3>
    <br />
    <h4 align=center class="box-title"><?php echo $fetch_users['fullName']; ?></h4>
  </div>

  <div class="box-body">
    <button class="btn"
            style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:15px;"
            onclick="getPage('./user_management/homescreen.php');">Go Back
    </button>
    <form id="functionaccessform" action="./user_management/page_access.php" method="POST" onsubmit="return false">

      <input style="display:none;"
             class="form-control input-md"
             type="text"
             name="admin_id"
             value="<?php echo $user_stafflogin_id; ?>"
             readonly>
      <table id="header_list" class="table table-bordered table-striped" style="width:100%">
        <thead>
        <tr>
          <th style='display:none;'>Function Id</th>
          <th width="15%">Path</th>
          <th width="15%" style="text-align:center">Immediate Parent</th>
          <th width="15%">Name</th>
          <th width="5%"><input type="checkbox" id="master"><br />Control Access</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $header_result = QUERY::run( "SELECT
                       child.Id AS c_id,
                       CONCAT
                       (
                         ( CASE WHEN gran.header   IS NULL THEN '' ELSE CONCAT( '/', gran.header   ) END ),
                         ( CASE WHEN parent.header IS NULL THEN '' ELSE CONCAT( '/', parent.header ) END ),
                         '/', child.header
                       ) AS PATH,
                       ( 
                         CASE 
                           WHEN parent.header IS     NULL AND child.url IS NULL THEN '--root--' 
                           WHEN parent.header IS NOT NULL AND child.url IS NULL THEN '-subroot-' 
                           ELSE child.url 
                         END 
                       ) AS c_url,
                       child.header AS c_head,  
                       ( 
                         CASE 
                           WHEN parent.header IS NULL THEN '-'
                           ELSE parent.header
                         END 
                       ) AS p_head
                     FROM
                       setup_links child
                       LEFT JOIN setup_links parent    ON parent.Id    = child.parent 
                       LEFT JOIN setup_links gran      ON gran.Id      = parent.parent
                       LEFT JOIN setup_links greatgran ON greatgran.Id = gran.parent 
                       JOIN setup_modulemapping ON setup_modulemapping.modulelist_Id = child.modulelist_Id
                     WHERE parent.header IS NOT NULL AND child.link_user_type = '0' AND child.access_type = '0' AND setup_modulemapping.sectionmaster_Id = '$SectionMaster_Id' AND  setup_modulemapping.userType_Id = 0" );
        $id = 0;

        foreach ( $header_result as $header ) {
          $tr_data = '
        <tr>
          <td  style="display:none;">
            <input  type="text" class="sub_chk" value="' . $header['c_id'] . '" name="r[' . $header['c_id'] . ']">
          </td>
          <td style="vertical - align:middle;text - align:center">' . $header['PATH'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['p_head'] . '</td>
          <td style="vertical - align:middle;text - align:center">' . $header['c_head'] . '</td> ';

          $users_func = QUERY::run('SELECT 1 
                                  FROM
                                    `user_stafflogin`
                                    LEFT JOIN `setup_links_access` ON `user_stafflogin`.`Id` = `setup_links_access`.`user_id` 
                                  WHERE
                                    `user_stafflogin`.`Id` = ?
                                    AND `setup_links_access`.`function_id` = ?',
                                  [ $user_stafflogin_id, $header['c_id'] ] )->fetch();

          if ( $users_func )
            $tr_data .= "<td><input type='checkbox' class='sub_chk' checked name='c[" . $header['c_id'] . "]'></td>";
          else
            $tr_data .= "<td><input type='checkbox' class='sub_chk' name='c[" . $header['c_id'] . "]'></td>";

          echo $tr_data;
          ++$id;
        }
        ?>
        </tbody>
      </table>
      <div class="col-sm-offset-6 col-sm-4" style="text-align:right; padding-bottom:1em;">
        <button class="btn btn-success input-lg"
                style="border:1px solid #504343; bgcolor:#e7e7e7; font-size:16px;"
                type="submit">Submit form
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
    {column_number: 2, filter_match_mode: "exact"}
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

  $('#functionaccessform').submit(function (event) {
    $("#loader").css("display", "block");
    $("#DisplayDiv").css("display", "none");
    $.ajax({
      url: './user_management/page_access.php',
      type: 'POST',
      dataType: 'html',   //expect return data as html from server
      data: $('#functionaccessform').serialize(),
      success: function (response, textStatus, jqXHR) {
        $('#DisplayDiv').html(response);
        $("#loader").css("display", "none");
        $("#DisplayDiv").css("display", "block");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('error(s):' + textStatus, errorThrown);
      }
    });
  });

</script>
