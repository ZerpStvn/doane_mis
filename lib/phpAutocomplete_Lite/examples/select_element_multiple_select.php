<?php
require_once("../conf.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Autocomplete - Existing Select Element (Multiple)</title>
</head>
<body>

<h2>Existing Select Element (Multiple)</h2>
<?php
$pac3 = new C_PhpAutocomplete('foobar2');
$pac3->display('SELECT');
?>
<select id="foobar2" multiple>
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