<?php
require_once("../conf.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Autocomplete - Hidden Input with Data</title>
</head>
<body>

<h2>Hidden Input with Data</h2>

<?php
$data = array(array('id'=>1, 'text'=>'Apple'),
    array('id'=>2, 'text'=>'Google'),
    array('id'=>3, 'text'=>'Microsoft'),
    array('id'=>4, 'text'=>'Facebook'),
    array('id'=>5, 'text'=>'Twitter'));

$pac = new C_PhpAutocomplete('my_hidden_input', $data);
$pac->enable_multiple(true);    // hidden input only
$pac->display();
?>
<input id="my_hidden_input" type="hidden" />

<div id="bottom" style="position:fixed;bottom: 0;width:98%;text-align: right">
    <a href="http://phpautocomplete.com">phpautocomplete.com</a> &copy; All rights reserved
</div>
</body>
</html>
