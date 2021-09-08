<?php session_start() ?>
<?php include 'db_connect.php'; ?>
<div class="container-fluid">
	<form action="" id="update-profile">
		<div class="row">
			<div class="form-group">
			<label for="" class="control-label">Profile Picture</label>
				<div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFile" name="pp" accept="image/*" onchange="displayImgProfile(this,$(this))">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
			</div>
		</div>
		<div class="row">
			<div class="form-group d-flex justify-content-center rounded-circle">
				<img src="assets/uploads/<?php echo $_SESSION['login_profile_pic'] ?>" alt="" id="profile" class="img-fluid img-thumbnail rounded-circle" style="max-width: calc(50%)">
			</div>
		</div>
		
	</form>
</div>
<script>
	$('#update-profile').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:"ajax.php?action=update_profile",
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					location.reload()
				}
			}
		})
	})
	function displayImgProfile(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#profile').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
</script>