<?php include 'db_connect.php' ?>
<div class="container-fluid">
	<form action="" id="signup">
		<div class="col-lg-12">
			<div id="msg"></div>
			<div class="row">
				<div class="form-group col-md-6">
					<input type="text" class="form-control" placeholder="First name" name='firstname'>
				</div>
				<div class="form-group col-md-6">
					<input type="text" class="form-control" placeholder="Last name" name='lastname'>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<input type="email" class="form-control" placeholder="Email" name='email'>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<input type="password" class="form-control" placeholder="Password" name='password'>
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
						<option value="<?php echo $i ?>" <?php $i == abs(date("m")) ? "selected" : '' ?>><?php echo ucwords($month[$i]) ?></option>
					<?php endfor; ?>
					</select>
				</div>
				<div class="form-group col-md-4">
					<select name="day" id="day" class="custom-select">
						<?php
							for($i = 1 ; $i <= 31;$i++):
						?>
						<option value="<?php echo $i ?>" <?php $i == abs(date("d")) ? "selected" : '' ?>><?php echo $i ?></option>
					<?php endfor; ?>
					</select>
				</div>
				<div class="form-group col-md-4">
					<select name="year" id="year" class="custom-select">
						<?php
							for($i = abs(date('Y')) ; $i >= abs(date('Y')) - 100;$i--):
						?>
						<option value="<?php echo $i ?>" <?php $i == abs(date("Y")) ? "selected" : '' ?>><?php echo $i ?></option>
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
			             	<input class="form-check-input" type="radio" checked="" id="gfemale" name="gender" value="Female">
			            </div>
					</div>
	            </div>
	            <div class="form-group col-md-4">
					<div class="d-flex w-100 justify-content-between p-1 border rounded align-items center">
						<label for="gmale">Male</label>
						<div class="form-check d-flex w-100 justify-content-end">
			             	<input class="form-check-input" type="radio" id="gmale" name="gender" value="Male">
			            </div>
					</div>
	            </div>
			</div>
			<div class="row justify-content-center">
				<button class="btn btn-block btn-success col-sm-5 align-self-center"><b>Sign Up</b></button>
			</div>
		</div>
	</form>
</div>
<style>
	#uni_modal .modal-footer{
		display:none 
	}
</style>
<script>
	$('#signup').submit(function(e){
		e.preventDefault()
		$('#msg').html('')
		start_load()
		$.ajax({
			url:"ajax.php?action=signup",
			method:"POST",
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					location.replace("index.php?page=additional_info")
				}else if(resp ==2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>")
					end_load()
				}
			}
		})
	})
</script>
