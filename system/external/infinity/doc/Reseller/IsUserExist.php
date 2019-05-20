<?php
require_once('../../iosapi.php');

$User = 'User';
$Result = $Api->UserExist($User);

echo '<pre>'.print_r($Result, true).'</pre>';

/*
[User] => User           <= user name to check
[id] => 0                <= transaction id
[Result] => 0            <= means User is exist
[Operation] => UserExist <= operation name
[Debug] => 0      

[User] => User
[id] => 0
[Result] => 255          <= means User is not exist
[Operation] => UserExist
[Debug] => 0    
*/