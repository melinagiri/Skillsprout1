<?php session_start() ?>
<?php include 'db_connect.php'; ?>
<div class="container-fluid">
	<form action="" id="update-cover">
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
				<img src="assets/uploads/<?php echo $_SESSION['login_cover_pic'] ?>" alt="" id="cover" class="img-fluid img-thumbnail">
			</div>
		</div>
	</form>
</div>
<script>
	$('#update-cover').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:"ajax.php?action=update_cover",
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