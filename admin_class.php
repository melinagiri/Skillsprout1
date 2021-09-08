<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM users where email = '".$email."' and password = '".md5($password)."'  and type= 2 ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($cpass) && !empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','month','day','year')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($month)){
					$data .= ", dob='{$year}-{$month}-{$day}' ";
		}
		if(isset($email)){
			$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
			if($check > 0){
				return 2;
				exit;
			}
		}
		if(isset($_FILES['pp']) && $_FILES['pp']['tmp_name'] != ''){
			$fnamep = strtotime(date('y-m-d H:i')).'_'.$_FILES['pp']['name'];
			$move = move_uploaded_file($_FILES['pp']['tmp_name'],'assets/uploads/'. $fnamep);
			$data .= ", profile_pic = '$fnamep' ";

		}
		if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != ''){
			$fnamec = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'assets/uploads/'. $fnamec);
			$data .= ", cover_pic = '$fnamec' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					if($k = 'pp'){
						$k ='profile_pic';
					}
					if($k = 'cover'){
						$k ='cover_pic';
					}
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
					if(isset($_FILES['pp']) &&$_FILES['pp']['tmp_name'] != '')
						$_SESSION['login_profile_pic'] = $fnamep;
					if(isset($_FILES['cover']) &&$_FILES['cover']['tmp_name'] != '')
						$_SESSION['login_cover_pic'] = $fnamec;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table')) && !is_numeric($k)){
				if($k =='password')
					$v = md5($v);
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_post(){
		extract($_POST);
		$data = "";

		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','img','imgName')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
					$data .= ", user_id='{$_SESSION['login_id']}' ";


		if(empty($id)){
			$save = $this->db->query("INSERT INTO posts set $data");
			if($save && isset($img)){
				$id= $this->db->insert_id;
				mkdir('assets/uploads/'.$id);
				for($i = 0 ; $i< count($img);$i++){
					list($type, $img[$i]) = explode(';', $img[$i]);
					list(, $img[$i])      = explode(',', $img[$i]);
					$img[$i] = str_replace(' ', '+', $img[$i]);
					$img[$i] = base64_decode($img[$i]);
					$fname = strtotime(date('Y-m-d H:i'))."_".$imgName[$i];
					$upload = file_put_contents('assets/uploads/'.$id.'/'.$fname,$img[$i]);
					$data = " file_path = '".$fname."' ";
				}
			}
		}else{
			$save = $this->db->query("UPDATE posts set $data where id = $id");
			if($save){
				if(is_dir('assets/uploads/'.$id)){
					$gal = scandir('assets/uploads/'.$id);
					unset($gal[0]);
					unset($gal[1]);
					foreach($gal as $k=>$v){
						unlink('assets/uploads/'.$id.'/'.$v);
					}
					rmdir('assets/uploads/'.$id);
				}
				if(isset($img)){
					mkdir('assets/uploads/'.$id);
					for($i = 0 ; $i< count($img);$i++){
						list($type, $img[$i]) = explode(';', $img[$i]);
						list(, $img[$i])      = explode(',', $img[$i]);
						$img[$i] = str_replace(' ', '+', $img[$i]);
						$img[$i] = base64_decode($img[$i]);
						$fname = strtotime(date('Y-m-d H:i'))."_".$imgName[$i];
						$upload = file_put_contents('assets/uploads/'.$id.'/'.$fname,$img[$i]);
						$data = " file_path = '".$fname."' ";
					}
				}
			}
		}
		if($save){
			return 1;
		}
	}
	function delete_post(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM posts where id = $id");
		if($delete){
			if(is_dir('assets/uploads/'.$id)){
				$gal = scandir('assets/uploads/'.$id);
				unset($gal[0]);
				unset($gal[1]);
				foreach($gal as $k=>$v){
					unlink('assets/uploads/'.$id.'/'.$v);
				}
				rmdir('assets/uploads/'.$id);
			}
			return 1;
		}
	}
	function like(){
		extract($_POST);
		$data = " user_id = {$_SESSION['login_id']} ";
		$data .= ", post_id = $post_id ";
		$chk = $this->db->query("SELECT * FROM likes where user_id = {$_SESSION['login_id']} and post_id = $post_id ")->num_rows;
		if($chk > 0){
			$delete = $this->db->query("DELETE FROM likes where user_id = {$_SESSION['login_id']} and post_id = $post_id ");
			if($delete){
				return 0;
				exit;
			}
		}
		$save = $this->db->query("INSERT INTO likes set $data ");
		if($save){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = " user_id = {$_SESSION['login_id']} ";
		$data .= ", post_id = $post_id ";
		$data .= ", comment = '$comment' ";
		$save = $this->db->query("INSERT INTO comments set $data ");
		if($save){
			$id= $this->db->insert_id;
			$d['status'] = 1;
			$qry = $this->db->query("SELECT c.*,concat(u.firstname,' ',u.lastname) as name,u.profile_pic FROM comments c inner join users u on u.id = c.user_id where c.id = $id ")->fetch_array();
			foreach($qry as $k => $v){
				if(!is_numeric($k)){
					if($k == "comment"){
						$v = str_replace("\n","<br/>",$v);
					}
					if($k == 'date_created'){
						$k = 'timestamp';
						$v = date("M d,Y h:i A",strtotime($v));
					}
					$d['data'][$k] = $v;
				}
			}
			return json_encode($d);
		}
	}
	function update_cover(){
		
		if(isset($_FILES['cover']) && $_FILES['cover']['tmp_name'] != ''){
			$fnamec = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'assets/uploads/'. $fnamec);
			$data = " cover_pic = '$fnamec' ";

		}
		if(isset($data)){
			$save = $this->db->query("UPDATE users set $data where id = {$_SESSION['login_id']}");
			if($save){
				if(isset($_FILES['cover']) &&$_FILES['cover']['tmp_name'] != '')
						$_SESSION['login_cover_pic'] = $fnamec;
				return 1;
			}
		}
	}
	function update_profile(){
		
		if(isset($_FILES['pp']) && $_FILES['pp']['tmp_name'] != ''){
			$fnamep = strtotime(date('y-m-d H:i')).'_'.$_FILES['pp']['name'];
			$move = move_uploaded_file($_FILES['pp']['tmp_name'],'assets/uploads/'. $fnamep);
			$data = " profile_pic = '$fnamep' ";

		}
		if(isset($data)){
			$save = $this->db->query("UPDATE users set $data where id = {$_SESSION['login_id']}");
			if($save){
				if(isset($_FILES['pp']) &&$_FILES['pp']['tmp_name'] != '')
						$_SESSION['login_profile_pic'] = $fnamep;
				return 1;
			}
		}
	}
}