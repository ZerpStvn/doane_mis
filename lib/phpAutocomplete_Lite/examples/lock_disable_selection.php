<?php
require_once("../conf.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Autocomplete - Lock & Disable Selection</title>
</head>
<body>


<h2>Lock Selection</h2>

<div>
    Note that lock a selection is only possible when multiple selection is enabled. To disable selection,
    see the implementation detail. The Microsoft is disabled from selection in examples.
</div>
<br />
<h2>Lock & disable selection (SELECT - multiple select)</h2>
<?php
$pac = new C_PhpAutocomplete('lock_selection_SELECT');
$pac->set_init_selection(array(
    array("id"=>"1", "text"=>"Apple", "locked"=>true),
    array("id"=>"5", "text"=>"Twitter")));
$pac->display('SELECT');

?>
<select id="lock_selection_SELECT" multiple>
    <option></option>
    <option id="1">Apple</option>
    <option id="2">Google</option>
    <option id="3" disabled>Microsoft</option>
    <option id="4">Facebook</option>
    <option id="5">Twitter</option>
</select>


<h2>Lock & disable selection (HIDDEN INPUT - multiple select)</h2>
<?php
$data = array(array('id'=>1, 'text'=>'Apple'),
    array('id'=>2, 'text'=>'Google'),
    array('id'=>3, 'text'=>'Microsoft', 'disabled'=>true),
    array('id'=>4, 'text'=>'Facebook'),
    array('id'=>5, 'text'=>'Twitter'));

$pac = new C_PhpAutocomplete('lock_selection_INPUT2', $data);
$pac->enable_multiple(true);    // hidden input only
$pac->set_init_selection(array(
    array("id"=>"1", "text"=>"Apple"),
    array("id"=>"5", "text"=>"Twitter", "locked"=>true)));
$pac->display();
?>
<input id="lock_selection_INPUT2" type="hidden" value="" />




<div id="bottom" style="position:fixed;bottom: 0;width:98%;text-align: right">
    <a href="http://phpautocomplete.com">phpautocomplete.com</a> &copy; All rights reserved
</div>
</body>
</html>