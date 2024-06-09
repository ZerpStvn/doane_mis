<?php
require_once("../conf.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Autocomplete - </title>
</head>
<body>

<h2>Auto-generated Hidden Input</h2>
<?php
$data = array(array('id'=>1, 'text'=>'Apple'),
    array('id'=>2, 'text'=>'Google'),
    array('id'=>3, 'text'=>'Microsoft'),
    array('id'=>4, 'text'=>'Facebook'),
    array('id'=>5, 'text'=>'Twitter'));

$new_data = array(array('id'=>11, 'text'=>'ABC'),
    array('id'=>22, 'text'=>'OPQ'),
    array('id'=>33, 'text'=>'XYZ'));


$pac = new C_PhpAutocomplete('load_data', $data);
$pac->load_data($new_data);  // this will overwrite $data;
$pac->display();
?>
<input id="load_data" type="hidden" />




<div id="bottom" style="position:fixed;bottom: 0;width:98%;text-align: right">
    <a href="http://phpautocomplete.com">phpautocomplete.com</a> &copy; All rights reserved
</div>
</body>
</html>