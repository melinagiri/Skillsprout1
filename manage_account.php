<?php session_start() ?>
<?php include 'db_connect.php'; ?>
<div class="container-fluid">
	<form action="" id="update-account">
		<input type="hidden" name="id" value="<?php echo $_SESSION['login_id'] ?>">
		<div class="col-lg-12">
					<div id="msg"></div>
					<div class="row">
						<div class="form-group col-md-6">
							<input type="text" class="form-control" placeholder="First name" name='firstname' value="<?php echo $_SESSION['login_firstname'] ?>">
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" placeholder="Last name" name='lastname' value="<?php echo $_SESSION['login_lastname'] ?>">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-12">
							<input type="email" class="form-control" placeholder="Email" name='email' value="<?php echo $_SESSION['login_email'] ?>">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-12">
							<input type="password" class="form-control" placeholder="Password" name='password'>
							<small><i>Leave this blank if you dont want change your password</i></small>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-12">
							<input type="password" class="form-control" placeholder="Confirm Password" name='cpass'>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
					<b><small class="text-muted"><b>Birthday</b></small></b>
					<div class="row">
						<div class="form-group col-md-4">
							<select name="month" id="month" class="custom-select">
								<?php
									$month = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec");
									for($i = 1 ; $i <= 12;$i++):
								?>
								<option value="<?php echo $i ?>" <?php echo $i == abs(date("m",strtotime($_SESSION['login_dob']))) ? "selected" : '' ?>><?php echo ucwords($month[$i]) ?></option>
							<?php endfor; ?>
							</select>
						</div>
						<div class="form-group col-md-4">
							<select name="day" id="day" class="custom-select">
								<?php
									for($i = 1 ; $i <= 31;$i++):
								?>
								<option value="<?php echo $i ?>" <?php echo $i == abs(date("d",strtotime($_SESSION['login_dob']))) ? "selected" : '' ?>><?php echo $i ?></option>
							<?php endfor; ?>
							</select>
						</div>
						<div class="form-group col-md-4">
							<select name="year" id="year" class="custom-select">
								<?php
									for($i = abs(date('Y')) ; $i >= abs(date('Y',strtotime($_SESSION['login_dob']))) - 100;$i--):
								?>
								<option value="<?php echo $i ?>" <?php echo $i == abs(date("Y")) ? "selected" : '' ?>><?php echo $i ?></option>
							<?php endfor; ?>
							</select>
						</div>
					</div>
					<b><small class="text-muted"><b>Birthday</b></small></b>
					<div class="row">
						<div class="form-group col-md-4">
							<div class="d-flex w-100 justify-content-between p-1 border rounded align-items center">
								<label for="gfemale">Female</label>
								<div class="form-check d-flex w-100 justify-content-end">
					             	<input class="form-check-input" type="radio" checked="" id="gfemale" name="gender" value="Female" <?php echo $_SESSION['login_gender'] == "Female" ? "checked" : '' ?>>
					            </div>
							</div>
			            </div>
			            <div class="form-group col-md-4">
							<div class="d-flex w-100 justify-content-between p-1 border rounded align-items center">
								<label for="gmale">Male</label>
								<div class="form-check d-flex w-100 justify-content-end">
					             	<input class="form-check-input" type="radio" id="gmale" name="gender" value="Male"  <?php echo $_SESSION['login_gender'] == "Male" ? "checked" : '' ?>>
					            </div>
							</div>
			            </div>
					</div>
		</div>						
	</form>
</div>
<script>
	$('#update-account').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if($('[name="password"]').val() != '' && $('[name="cpass"]').val() != ''){
			if($('#pass_match').attr('data-status') != 1){
				if($("[name='password']").val() !=''){
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url:"ajax.php?action=signup",
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					location.reload()
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
	$('[name="password"],[name="cpass"]').keyup(function(){
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if(cpass == '' ||pass == ''){
			$('#pass_match').attr('data-status','')
		}else{
			if(cpass == pass){
				$('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>')
			}else{
				$('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImgCover(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cover').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
</script>