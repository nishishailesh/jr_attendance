<?php
require_once 'base/verify_login.php';
	////////User code below/////////////////////

echo '            <link rel="stylesheet" href="project_common.css">
                  <script src="project_common.js"></script>';
  

$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

$user=get_user_info($link,$_SESSION['login']);
print_r($user);
$auth=explode(',',$user['authorization']);

//////////////user code ends////////////////
tail();
echo '<pre>start:post';print_r($_POST);echo '</pre>';
//echo '<pre>start:session';print_r($_SESSION);echo '</pre>';


?>
