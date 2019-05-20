<?php
require_once('../../iosapi.php');

$User = 'User';
$NumberOfCredits = 5;
$NoteForSender = '';
$NoteForReceiver = '';

$Result = $Api->CreditMoveTo($User, $NumberOfCredits, $NoteForSender, $NoteForReceiver);

echo '<pre>'.print_r($Result, true).'</pre>';

/*
[Comment] => User           <= Username or error message
[id] => 0                   <= Number of Credits transferred to Username
[Result] => 0               <= means Credits transferred well
[Operation] => CreditMoveTo <= operation name
[Debug] => 0     
*/