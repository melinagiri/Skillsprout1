<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'save_post'){
	$save = $crud->save_post();
	if($save)
		echo $save;
}
if($action == 'delete_post'){
	$delete = $crud->delete_post();
	if($delete)
		echo $delete;
}
if($action=='like'){
	$save = $crud->like();
	if($save)
		echo $save;
}
if($action == 'save_comment'){
	$save = $crud->save_comment();
	if($save)
		echo $save;
}
if($action == 'update_cover'){
	$save = $crud->update_cover();
	if($save)
echo $save;
}
if($action == 'update_profile'){
	$save = $crud->update_profile();
	if($save)
echo $save;
}
ob_end_flush();
?>
