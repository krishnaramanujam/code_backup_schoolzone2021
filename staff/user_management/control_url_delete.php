<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 05-09-2019
 * Time: 01:41 PM
 */

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
session_start();
include_once '../../config/database.php';

$header_id     = $_POST['c_id'];
$delete_header = QUERY::run( 'DELETE FROM `setup_links` WHERE `Id` = ?', [ $header_id ] );

echo json_encode( 'Deleted successfully' );
