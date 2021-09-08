

<div class="col-lg-12">
	<div class="container py-2">
		<div class="col-md-12 d-flex h-100 w-100 align-items-center justify-content-center">
			<div class="card" style="width:50rem">
				<div class="card-header">
					<h5 class="card-title"><b>Setup Profile</b></h5>
				</div>
				<div class="card-body">
					<div class="col-md-8 offset-md-2">
					<form action="" id="additional-info">
						<input type="hidden" name="id" value="<?php echo $_SESSION['login_id'] ?>">
						<input type="hidden" name="status" value="1">
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
								<img src="" alt="" id="profile" class="img-fluid img-thumbnail rounded-circle" style="max-width: calc(50%)">
							</div>
						</div>
						<div class="row">
							<div class="form-group">
							<label for="" class="control-label">Cover Image</label>
								<div class="custom-file">
			                      <input type="file" class="custom-file-input" id="customFile" name="cover" accept="image/*" onchange="displayImgCover(this,$(this))">
			                      <label class="custom-file-label" for="customFile">Choose file</label>
			                    </div>
							</div>
						</div>
						<div class="row">
							<div class="form-group d-flex justify-content-center">
								<img src="" alt="" id="cover" class="img-fluid img-thumbnail">
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
<style>
	img#cimg{
		max-height: 15vh;
		/*max-width: 6vw;*/
	}
</style>
<script>
	$('#additional-info').submit(function(e){
		e.preventDefault()
		start_load()
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
					location.replace("index.php")
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