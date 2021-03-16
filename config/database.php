<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL & ~E_DEPRECATED & ~E_NOTICE );
ob_start();

$databaseHost = 'localhost';
$databaseName = 'schoolzone2021';
$databaseUsername='siws';
$databasePassword='kKgnpqSViksbhHDU';


// must end with a slash
define('SITE_URL', 'http://127.0.0.1/schoolzone2021');
date_default_timezone_set( 'Asia/Kolkata' );
$mysqli = mysqli_connect( $databaseHost, $databaseUsername, $databasePassword, $databaseName );
mysqli_query( $mysqli, 'SET character_set_results=utf8' );
mysqli_query( $mysqli, 'SET names=utf8' );
mysqli_query( $mysqli, 'SET character_set_client=utf8' );
mysqli_query( $mysqli, 'SET character_set_connection=utf8' );
mysqli_query( $mysqli, 'SET character_set_results=utf8' );
mysqli_query( $mysqli, 'SET collation_connection=utf8_general_ci' );




if( isset($_SESSION['AY_ID_New']) )
{

  $Q = "
  SELECT
      `setup_academicyear`.`Id` AS `Current_AY_ID`,
      `setup_academicyear`.`Academic_year` AS `Academic_Year`,
      `setup_academicyear`.Abbreviation AS `Academic_Year_Abbreviation`
    FROM
      `setup_academicyear`
  WHERE 
    `setup_academicyear`.Academic_year = '". $_SESSION['AY_ID_New'] . "' AND sectionmaster_Id = '". $_SESSION['schoolzone']['SectionMaster_Id'] . "' Order By sequence_no Asc";

  $AY_result = mysqli_query( $mysqli, $Q );

  $AY_res = mysqli_fetch_assoc( $AY_result );

  $Acadmic_Year_ID = $AY_res['Current_AY_ID'];
  $Academic_Year = $AY_res['Academic_Year'];
  $Academic_Year_Abbreviation = $AY_res['Academic_Year_Abbreviation'];
  $Sem_Type = $_SESSION['Sem_Type'];

}
else
{
  $AY_result = mysqli_query( $mysqli, "
  SELECT
      `setup_academicyear`.`Id` AS `Current_AY_ID`,
      `setup_academicyear`.`Academic_year` AS `Academic_Year`,
      `setup_academicyear`.Abbreviation AS `Academic_Year_Abbreviation`
    FROM
      `setup_academicyear`
  WHERE 
    `setup_academicyear`.isDefault = 1 AND sectionmaster_Id = '". $_SESSION['schoolzone']['SectionMaster_Id'] . "' Order By sequence_no Asc " );

  $AY_res = mysqli_fetch_assoc( $AY_result );

  $_SESSION['AY_ID_New'] = $AY_res['Academic_Year'];
  $Acadmic_Year_ID = $AY_res['Current_AY_ID'];
  $Academic_Year = $AY_res['Academic_Year'];
  $Academic_Year_Abbreviation = $AY_res['Academic_Year_Abbreviation'];

}


//----------------PDO way to connect to the database----------------

define( 'DB_HOST', $databaseHost );
define( 'DB_NAME', $databaseName );
define( 'DB_USER', $databaseUsername );
define( 'DB_PASS', $databasePassword );
// configure PDO options:
$database_options =                                      // important! use actual prepared statements (default: emulate prepared statements)
  array(
    PDO::ATTR_EMULATE_PREPARES => false,                 // throw exceptions in case of errors (default: stay silent)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,         // fetch associative arrays (default: mixed arrays)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC );

// connect to the database - important! specify the character encoding in the DSN string, don't use SET NAMES
$database = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
                     DB_USER,
                     DB_PASS,
                     $database_options );

if ( !function_exists( 'html_escape' ) )
{
  /**
   * Returns HTML escaped variable.
   *
   * @param  mixed $input The input string or array of strings to be escaped.
   * @param  bool $double_encode $double_encode set to FALSE prevents escaping twice.
   * @return  mixed      The escaped string or array of strings as a result.
   */
  function html_escape($input, $double_encode = TRUE)
  {
    if ( is_array( $input ) )
    {
      return array_map( 'html_escape', $input, array_fill( 0, count( $input ), $double_encode ) );
    }

    return htmlspecialchars( $input, ENT_QUOTES, 'utf-8', $double_encode );
  }
}

// blunt PDO Singleton to use universally for procedural code
class QUERY
{
  protected static $instance = null;

  protected function __construct() { }
  protected function __clone() { }

  public static function __callStatic($method, $args)
  {
    return call_user_func_array( array( self::instance(), $method ), $args );
  }

  public static function instance()
  {
    if ( self::$instance === null )
    {
      $database_options =
        array(
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES => FALSE, );

      $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
      self::$instance = new PDO( $dsn,
                                 DB_USER, DB_PASS,
                                 $database_options );
    }
    return self::$instance;
  }

  public static function run($sql, $args = [])
  {
    if ( !$args )
    {
      return self::instance()->query( $sql );
    }
    $stmt = self::instance()->prepare( $sql );
    $stmt->execute( $args );
    return $stmt;
  }
}
//-------------

?>
