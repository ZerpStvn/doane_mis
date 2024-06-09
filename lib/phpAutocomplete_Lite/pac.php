<?php
error_reporting(E_ALL);
// error_reporting(E_STRICT);
ini_set('display_errors', 1);

// fix missing DOCUMENT_ROOT in IIS
if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['SCRIPT_FILENAME'])){
    $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
}; };
if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['PATH_TRANSLATED'])){
    $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
}; };

require_once(dirname(__FILE__) .'/conf.php');
require_once(dirname(__FILE__) . '/server/classes/cls_phpautocomplete.php');
require_once(dirname(__FILE__) . '/server/classes/cls_util.php');

define('PAC_DEFAULT_SELECT_ID', '_oPac');
?>