<?php
require_once("../conf.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Autocomplete - Set Initial Selection</title>
</head>
<body>

<h2>Set Initial Selection (SELECT)</h2>
<?php
$pac = new C_PhpAutocomplete('init_selection_SELECT');
$pac->set_init_selection(array(
            array("id"=>5, "text"=>"Twitter"),
            array("id"=>4, "text"=>"Facebook")));
$pac->display('SELECT');

?>
<select id="init_selection_SELECT" multiple>
    <option></option>
    <option id="1">Apple</option>
    <option id="2">Google</option>
    <option id="3">Microsoft</option>
    <option id="4">Facebook</option>
    <option id="5">Twitter</option>
</select>
<a href="#" onclick="get_init_selection('#init_selection_SELECT')">show selected value</a>




<h2>Set Initial Selection (HIDDEN INPUT - Single Select)</h2>
<?php
$data = array(array('id'=>1, 'text'=>'Apple'),
    array('id'=>2, 'text'=>'Google'),
    array('id'=>3, 'text'=>'Microsoft'),
    array('id'=>4, 'text'=>'Facebook'),
    array('id'=>5, 'text'=>'Twitter'));

$pac = new C_PhpAutocomplete('init_selection_INPUT', $data);
$pac->enable_multiple(false);    // hidden input only
$pac->set_init_selection(array("id"=>"1", "text"=>"Apple"));
$pac->display();
?>
<input id="init_selection_INPUT" type="hidden" value="" />
<a href="#" onclick="get_init_selection('#init_selection_INPUT')">show selected value</a>




<h2>Set Initial Selection (HIDDEN INPUT - Multiple Select)</h2>
<?php
$data = array(array('id'=>1, 'text'=>'Apple'),
    array('id'=>2, 'text'=>'Google'),
    array('id'=>3, 'text'=>'Microsoft'),
    array('id'=>4, 'text'=>'Facebook'),
    array('id'=>5, 'text'=>'Twitter'));

$pac = new C_PhpAutocomplete('init_selection_INPUT2', $data);
$pac->enable_multiple(true);    // hidden input only
$pac->set_init_selection(array(
                            array("id"=>"1", "text"=>"Apple"),
                            array("id"=>"5", "text"=>"Twitter")));
$pac->display();
?>
<input id="init_selection_INPUT2" type="hidden" value="" />

<script>
    function get_init_selection(ctrl_id){
        alert($(ctrl_id).select2("val"));
        return false;
    };
</script>
<a href="#" onclick="get_init_selection('#init_selection_INPUT2')">show selected value</a>




<div id="bottom" style="position:fixed;bottom: 0;width:98%;text-align: right">
    <a href="http://phpautocomplete.com">phpautocomplete.com</a> &copy; All rights reserved
</div>
</body>
</html>