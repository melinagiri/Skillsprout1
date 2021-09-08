
<?php 
if( isset($_SESSION['login_status'])){

	// if($_SESSION['login_status']== 2){
	// 	echo "<script> location.replace('index.php?page=setup_profile')</script>";
	// }
}
?>
<div class="col-lg-12">
	<div class="container py-2">
		<div class="col-md-12 d-flex h-100 w-100 align-items-center justify-content-center">
			<div class="card" style="width:50rem">
				<div class="card-header">
					<h5 class="card-title"><b>Additional Information</b></h5>
				</div>
				<div class="card-body">
					<div class="col-md-12">
					<form action="" id="additional-info">
						<input type="hidden" name="id" value="<?php echo $_SESSION['login_id'] ?>">
						<input type="hidden" name="status" value="2">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="">Contact</label>
								<input type="text" name="contact" class="form-control form-control-sm" required="">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="">Address</label>
								<textarea id="" cols="30" rows="4" name="address" class="form-control"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="">Describe Yourself</label>
								<textarea id="" cols="30" rows="4" name="bio" class="form-control"></textarea>
							</div>
						</div>
					</form>
					</div>
				</div>
				<div class="card-footer">
					<div class="d-flex w-100 justify-content-end">
						<button class="btn btn btn-primary align-self-end" form="additional-info">Submit</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#additional-info').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:"ajax.php?action=signup",
			method:"POST",
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					location.replace("index.php?page=setup_profile")
				}
			}
		})
	})
</script>