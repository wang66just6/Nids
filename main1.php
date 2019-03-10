<?php

  $start = time();
   require("base_conf.php");
   include_once("$BASE_path/includes/base_auth.inc.php");
   include_once("$BASE_path/includes/base_db.inc.php");
   include_once("$BASE_path/includes/base_output_html.inc.php");
   include_once("$BASE_path/base_common.php");
   include_once("$BASE_path/base_db_common.php");
   include_once("$BASE_path/includes/base_cache.inc.php");
   include_once("$BASE_path/includes/base_state_criteria.inc.php");
   include_once("$BASE_path/includes/base_log_error.inc.php");
   include_once("$BASE_path/includes/base_log_timing.inc.php");

  RegisterGlobalState();
  

  $_SESSION = NULL;
  InitArray($_SESSION['back_list'], 1, 3, "");
  $_SESSION['back_list_cnt'] = 0;

  PushHistory();
  

  $roleneeded = 10000;
  $BUser = new BaseUser();

  if ($Use_Auth_System == 1)
  {
      if ($BUser->hasRole($roleneeded) == 0)
          base_header("Location: $BASE_urlpath/index.php");
  }


  if (isset($_GET['archive']))
  {
      "no" == $_GET['archive'] ? $value = 0 : $value = 1;
      setcookie('archive', $value);
      base_header("Location: $BASE_urlpath/base_main.php");
  }
  
  function DBLink()
  {
      
      GLOBAL $archive_exists;
   
      if ( (isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1) || (isset($_GET['archive']) && $_GET['archive'] == 1)) {
          echo '<a href="base_main.php?archive=no">' . _USEALERTDB . '</a>';
      } elseif ($archive_exists != 0) {
          echo ('<a href="base_main.php?archive=1">' . _USEARCHIDB . '</a>');
      }
  }
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html class="x-admin-sm">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo _CHARSET; ?>">
  <meta http-equiv="pragma" content="no-cache">

<?php
PrintFreshPage($refresh_stat_page, $stat_page_refresh_time);
$archiveDisplay = (isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1) ? "-- ARCHIVE" : "";
echo ('<title>' . _TITLE . $BASE_VERSION . $archiveDisplay . '</title>
<link rel="stylesheet" type="text/css" href="styles/' . $base_style . '">');
?>

</head>
<body>
<?php
if ($debug_mode == 1) {
    PrintPageHeader();
}

/* Check that PHP was built correctly */
$tmp_str = verify_php_build($DBtype);
if ($tmp_str != "") {
    echo $tmp_str;
    die();
}

/* Connect to the Alert database */
$db = NewBASEDBConnection($DBlib_path, $DBtype);
$db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

/* Check that the DB schema is recent */
$tmp_str = verify_db($db, $alert_dbname, $alert_host);
if ($tmp_str != "") {
    echo $tmp_str;
    die();
}
?>


<hr />
<table style='border:0' width='100%'>
  <tr>
    <td width='30%' valign='top'>
<?php
/* mstone 20050309 avoid count(*) if requested */

PrintGeneralStats($db, 0, $main_page_detail, "", "", $avoid_counts != 1);
 

/* mstone 20050309 make show_stats even leaner! */
if ($main_page_detail == 1) {
    echo '
    </td>
    <td width="70%" valign="top">
    <strong>'._TRAFFICPROBPRO.'</strong>';
    PrintProtocolProfileGraphs($db);
}
?>
    </td>
  </tr>
</table>

<p>
<hr />

<td align="right" valign="top">
<div class="systemstats">
  <?php 
if ($event_cache_auto_update == 1) {
    UpdateAlertCache($db);
}

if (!setlocale(LC_TIME, _LOCALESTR1)) {
    if (!setlocale (LC_TIME, _LOCALESTR2)) {
        setlocale (LC_TIME, _LOCALESTR3);
    }
    
    printf("<strong>"._QUERIED." </strong> : %s<br />" , strftime(_STRFTIMEFORMAT));
    if (isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1) {
        printf("<strong>"._DATABASE."</strong> %s &nbsp;&nbsp;&nbsp;(<strong>"._SCHEMAV."</strong> %d) \n<br />\n", 
      ($archive_dbname.'@'.$archive_host. ($archive_port != "" ? ':'.$archive_port : "") ),
            $db->baseGetDBversion()
        );
    } else {
        printf("<strong>"._DATABASE."</strong> %s &nbsp;&nbsp;&nbsp;(<strong>"._SCHEMAV."</strong> %d) \n<br />\n", 
      ( $alert_dbname.'@'.$alert_host. ($alert_port != "" ? ':'.$alert_port : "") ),
            $db->baseGetDBversion()
        );
    }
    
    StartStopTime($start_time, $end_time, $db);
    if ($start_time != "") {
        printf("<strong>"._TIMEWIN."</strong> [%s] - [%s]\n", $start_time, $end_time);
    } else {
        printf("<strong>"._TIMEWIN."</strong> <em>"._NOALERTSDETECT."</em>\n");
    }
}
?>
      </div>
    </td>

</body>
</html>
