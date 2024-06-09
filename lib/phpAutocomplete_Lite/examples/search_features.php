<?php
require_once("../conf.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Autocomplete - Search Beginning, Min & Max Input Length,Matcher, Placeholder</title>
</head>
<body>


<h2>Search Beginning, Min & Max Input Length, Matcher, Placeholder</h2>
<?php
$data = array(array('id'=>1, 'text'=>'Apple'),
    array('id'=>2, 'text'=>'Google'),
    array('id'=>3, 'text'=>'Microsoft'),
    array('id'=>4, 'text'=>'Facebook'),
    array('id'=>5, 'text'=>'Twitter'));

$pac = new C_PhpAutocomplete('searchme', $data);

$pac->set_max_input_length(4)->set_min_input_length(2);
$pac->set_placeholder('Choose a company name:');
$pac->set_matcher('search_casesensitive_and_startbeginning'); // only a single matcher can be used at a time
$pac->disable_search();

$pac->display();
?>
<script>
function search_casesensitive_and_startbeginning(term, text){
    return (text.indexOf(term)>=0)
            &&
            text.toUpperCase().indexOf(term.toUpperCase())==0;
}
</script>
<input id="searchme" type="hidden" />



<h2>Disable Search</h2>
Note that when set_max_input_length() or set_min_input_length() method is presented, disable_search() is ignored.<br />
<?php
$pac2 = new C_PhpAutocomplete('foobar');
$pac2->disable_search();
$pac2->display('SELECT');

?>
<select id="foobar">
    <option></option>
    <option id="1">Apple</option>
    <option id="2">Google</option>
    <option id="3">Microsoft</option>
    <option id="4">Facebook</option>
    <option id="5">Twitter</option>
</select>







<div id="bottom" style="position:fixed;bottom: 0;width:98%;text-align: right">
    <a href="http://phpautocomplete.com">phpautocomplete.com</a> &copy; All rights reserved
</div>
</body>
</html>