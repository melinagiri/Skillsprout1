<?php include 'db_connect.php' ?>

<div class="row shadow-sm" id="profile-field">
	<div class="container">
		<div class="col-lg-10 offset-md-1" style="height:60vh">
			<div class="position-relative image-fluid w-100 mb-1" style="height:65%">
				<img src="assets/uploads/<?php echo $_SESSION['login_cover_pic'] ?>" alt="" class="gallery__image cover-pic img-fluid rounded-bottom">
				<div class="position-absolute" style="top:85%;right:3%;z-index:1">
					<button class="btn btn-dark btn-sm bg-dark" type="button" id="edit_cover"><i class="fa fa-camera"></i> Edit Cover Photo</button>
				</div>
				<div class="w-100 d-flex justify-content-center position-absolute" style="top:50%">
					<span class="position-relative">
						<img src="assets/uploads/<?php echo $_SESSION['login_profile_pic'] ?>" alt="" class="img-fluid img-thumbnail rounded-circle" style="width:150px;height: 150px">
						<a href="javascript:void(0)" id="edit_pp" class="text-dark position-absolute rounded-circle img-thumbnail d-flex justify-content-center align-items-center" style="top:75%;left:75%;width:25px;height: 25px"><i class="fa fa-camera rounded-circle"></i></a>
					</span>
				</div>
			</div>
			<div class="d-block w-100 py-2 px-1 text-center">
				<h2 class="text-dark text-center"><b><?php echo ucwords($_SESSION['login_firstname'].' '.$_SESSION['login_lastname']); ?></b></h2>
				<small class="text-muted"><?php echo $_SESSION['login_bio'] ?></small>
			</div>
		</div>
	</div>
</div>
<div class="container py-2 px-1">
	<div class="col-lg-10 offset-md-1">
		<div class="row">
			<div class="col-md-4">
				<div class="card card-primary">
	              <div class="card-header">
	                <h3 class="card-title">About Me</h3>
	              </div>
		              <!-- /.card-header -->
		              <div class="card-body">
		                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

		                <p class="text-muted"><?php echo $_SESSION['login_address'] ?></p>

		                <hr>

		                <strong><i class="fas fa-calendar-day mr-1"></i> Date of Birth</strong>

		                <p class="text-muted"><?php echo date("M d,Y",strtotime($_SESSION['login_dob'])) ?></p>

		                <hr>

		                <strong><i class="fa fa-phone mr-1"></i> Contact</strong>

		                <p class="text-muted"><?php echo $_SESSION['login_contact'] ?></p>
		                <hr>
		                <strong><i class="far fa-user mr-1"></i> Gender</strong>

		                <p class="text-muted"><?php echo $_SESSION['login_gender'] ?></p>
		              </div>
		              <!-- /.card-body -->
	            </div>
			</div>
			<div class="col-md-8">
				<?php 

			$posts = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as name,u.profile_pic from posts p inner join users u on u.id = p.user_id  where p.user_id = {$_SESSION['login_id']} order by unix_timestamp(p.date_created) desc");
			while($row=$posts->fetch_assoc()):
			$row['content'] = str_replace("\n","<br/>",$row['content']);
			$is_liked =  $conn->query("SELECT * FROM likes where user_id = {$_SESSION['login_id']} and post_id = {$row['id']} ")->num_rows ? "text-primary" : "";
			$liked =  $conn->query("SELECT * FROM likes where post_id = {$row['id']} ")->num_rows;
			$commented =  $conn->query("SELECT * FROM comments where post_id = {$row['id']} ")->num_rows;

			?>
			<div class="col-md-12">
				
			<div class="card card-widget post-card" data-id="<?php echo $row['id'] ?>">
              <div class="card-header">
                <div class="user-block">
                  <img class="img-circle" src="assets/uploads/<?php echo $row['profile_pic'] ?>" alt="User Image">
                  <span class="username"><a href="#"><?php echo $row['name'] ?></a></span>
                  <span class="description">Posted - <?php echo date("M d,Y h:i a",strtotime($row['date_created'])) ?></span>
                </div>
                <!-- /.user-block -->
                <div class="card-tools">
                	<div class="dropdown">
	                  <button type="button" class="btn btn-tool" data-toggle="dropdown" title="Manage">
	                    <i class="fa fa-ellipsis-v"></i>
	                  </button>
	                  <div class="dropdown-menu">
              			<a class="dropdown-item edit_post" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Edit</a>
              			<a class="dropdown-item delete_post" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Delete</a>
	                  </div>
	                  </div>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- post text -->
                <p class="content-field"><?php echo $row['content'] ?></p>

              	<a href="javascript:void(0)" class="d-none show-content" >Show More</a>
              	<?php if(is_dir('assets/uploads/'.$row['id'])): ?>
              	<div class="gallery mb-2">
              		<?php
              		$gal = scandir('assets/uploads/'.$row['id']);
					unset($gal[0]);
					unset($gal[1]);
					$count =count($gal);
					$i = 0;
					foreach($gal as $k => $v):
						$mime = mime_content_type('assets/uploads/'.$row['id'].'/'.$v);
						$i++;
						if($i > 4)
						break;
						$style = '';
						if($count == 1){
							$style = "grid-column-start: 1;grid-column-end: 3;grid-row-start: 1;grid-row-end: 3;";
						}elseif($count == 2){
							// if($i==1)
							$style = "grid-column-start: {$i};grid-column-end: ".($i + 1).";grid-row-start: 1;grid-row-end: 3;";
						}elseif ($count == 3) {
							if($i == 1)
							$style = "grid-column-start: {$i};grid-column-end: ".($i + 1).";grid-row-start: 1;grid-row-end: 3;";
						}
              		 ?>
              		 <figure class="gallery__item position-relative" style="<?php echo $style ?>">
					   <?php if($i == 4 && $count > 4): ?>
						<div class="position-absolute d-flex justify-content-center align-items-center h-100 w-100" style="top:0;left:0;z-index:1" >
							<a href="javascript:void(0)" class="text-white view_more" data-id="<?php echo $row['id'] ?>"><h4 class="text-white text-center"><?php echo '+ '.($count-4) ?> More</h4></a>
						</div>
					    <?php endif; ?>
              		 	<?php if(strstr($mime,'image')): ?>
              		 		<a href="assets/uploads/<?php echo $row['id'].'/'.$v ?>" class="lightbox-items" data-toggle="lightbox<?php echo $row['id'] ?>" data-slide="<?php echo $k ?>" data-title="" data-gallery="gallery"  data-id="<?php echo $row['id'] ?>">
					    <img src="assets/uploads/<?php echo $row['id'].'/'.$v ?>" class="gallery__img" alt="Image 1">
					    </a>
					    <?php else: ?>
							<?php if($count > 1): ?>
								<a href="assets/uploads/<?php echo $row['id'].'/'.$v ?>" class="lightbox-items" data-toggle="lightbox<?php echo $row['id'] ?>" data-slide="<?php echo $k ?>" data-title="" data-gallery="gallery">
					    	<?php endif; ?>
					    	<video <?php echo $count == 1 ? "controls" : '' ?> class="gallery__img">
					    		 <source src="assets/uploads/<?php echo $row['id'].'/'.$v ?>" type="<?php echo $mime ?>">
					    	</video>
							<?php if($count > 1): ?>
							</a>
							<a href="javascript:void(0)" class="text-white view_more" data-id="<?php echo $row['id'] ?>" >
							<div class="position-absolute d-flex justify-content-center align-items-center h-100 w-100" style="top:0;left:0;z-index:1" >
							<h3 class="text-white text-center rounded-circle "><i class="fa fa-play-circle "></i></h3>
							</div>
							</a>
					    	<?php endif; ?>

					    <?php endif; ?>
						
					  </figure>
              		<?php endforeach; ?>
              	</div>
              <?php endif; ?>

                <!-- Social sharing buttons -->
                <button type="button" class="btn btn-default btn-sm like <?php echo $is_liked ?>" data-id="<?php echo $row['id'] ?>"><i class="far fa-thumbs-up"></i> Like</button>
                <span class="float-right text-muted counts"><span class="like-count"><?php echo number_format($liked) ?></span> <?php echo $liked > 1 ? "likes" : "like" ?> - <span class="comment-count"><?php echo number_format($commented) ?></span> comments</span>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
				<?php 
					$comments = $conn->query("SELECT c.*,concat(u.firstname,' ',u.lastname) as name,u.profile_pic FROM comments c inner join users u on u.id = c.user_id where c.post_id = {$row['id']} order by unix_timestamp(c.date_created) asc ");
					while($crow = $comments->fetch_assoc()):
				?>
				<div class="card-comment">
					<!-- User image -->
					<img class="img-circle img-sm" src="assets/uploads/<?php echo $crow['profile_pic'] ?>" alt="User Image">

					<div class="comment-text">
					<span class="username">
						<span class="uname"><?php echo $crow['name'] ?></span>
						<span class="text-muted float-right timestamp"><?php echo date("M d,Y h:i A",strtotime($crow['date_created'])) ?></span>
					</span><!-- /.username -->
					<span class="comment">
					<?php echo str_replace("\n","<br/>",$crow['comment']) ?>
					</span>
					</div>
					<!-- /.comment-text -->
				</div>
				<?php endwhile; ?>
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="#" method="post">
                  <i class="img-fluid img-circle img-sm fa fa-comment"></i>
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <textarea cols="30" rows="1" class="form-control comment-textfield" style="resize:none" placeholder="Press enter to post comment" data-id="<?php echo $row['id'] ?>"></textarea>
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
			</div>
		<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<div class="d-none " id="comment-clone">
<div class="card-comment">
	<!-- User image -->
	<img class="img-circle img-sm" src="" alt="User Image">

	<div class="comment-text">
	<span class="username">
		<span class="uname">Maria Gonzales</span>
		<span class="text-muted float-right timestamp">8:03 PM Today</span>
	</span><!-- /.username -->
	<span class="comment">
	It is a long established fact that a reader will be distracted
	by the readable content of a page when looking at its layout.
	</span>
	</div>
	<!-- /.comment-text -->
</div>
</div>
<style>
	.text-dark{
		color:black!important;
	}
	.cover-pic{
		width: 100%;
	    height: 100%;
	    object-fit: cover;
	}
	#profile-field{
		background: rgb(246,246,246);
		background: linear-gradient(0deg, rgba(246,246,246,1) 0%, rgba(132,185,238,1) 62%, rgba(0,151,255,1) 100%);
	}
	.gallery__img {
	    width: 100%;
	    height: 100%;
	    object-fit: cover;
	}
	.gallery {
	    display: grid;
	    grid-template-columns: repeat(2, 50%);
	    grid-template-rows: repeat(2, 30vh);
	    grid-gap: 3px;
	    grid-row-gap: 3px;
	}
	.gallery__item{
		margin: 0
	}
</style>
<script>
	$('#edit_cover').click(function(){
		uni_modal("Update Cover Photo","manage_cover.php")
	})
	$('#edit_pp').click(function(){
		uni_modal("Update Profile Picture","manage_pp.php")
	})
	$('.edit_post').click(function(){
		uni_modal("<center><b>Edit Post</b></center></center>","create_post.php?id="+$(this).attr('data-id'))
	})
	$('.delete_post').click(function(){
	_conf("Are you sure to delete this post?","delete_post",[$(this).attr('data-id')])
	})
	function delete_post($id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_post',
				method:'POST',
				data:{id:$id},
				success:function(resp){
					if(resp==1){
						alert_toast("Data successfully deleted",'success')
						setTimeout(function(){
							location.reload()
						},1500)

					}
				}
			})
		}
	$('.comment-textfield').on('keypress', function (e) {
		if(e.which == 13 && e.shiftKey == false){
			if($('#preload2').length <= 0){
				start_load();
			}else{
				return false;
			}
			var post_id = $(this).attr('data-id')
			var comment = $(this).val()
			$(this).val('')
			$.ajax({
				url:'ajax.php?action=save_comment',
				method:'POST',
				data:{post_id:post_id,comment:comment},
				success:function(resp){
					if(resp){
						resp = JSON.parse(resp)
						if(resp.status == 1){
							var cfield = $('#comment-clone .card-comment').clone()
							cfield.find('.img-circle').attr('src','assets/uploads/'+resp.data.profile_pic)
							cfield.find('.uname').text(resp.data.name)
							cfield.find('.comment').html(resp.data.comment)
							cfield.find('.timestamp').text(resp.data.timestamp)
						$('.post-card[data-id="'+post_id+'"]').find('.card-comments').append(cfield)
						var cc = $('.post-card[data-id="'+post_id+'"]').find('.comment-count').text();
							cc = cc.replace(/,/g,'');
							cc = parseInt(cc) + 1
						$('.post-card[data-id="'+post_id+'"]').find('.comment-count').text(cc)
						}else{
							alert_toast("An error occured","danger")
						}
						end_load()
					}
				}
			})
			return false;
		}
    })
	$('.comment-textfield').on('change keyup keydown paste cut', function (e) {
		if(this.scrollHeight <= 117)
        $(this).height(0).height(this.scrollHeight);
    })
    $('.content-field').each(function(){
		var dom = $(this)[0]
		var divHeight = dom.offsetHeight
		if(divHeight > 117){
			$(this).addClass('truncate-5')
			$(this).parent().children('.show-content').removeClass('d-none')
		}
	})
	$('.show-content').click(function(){
		var txt = $(this).text()
		if(txt == "Show More"){
			$(this).parent().children('.content-field').removeClass('truncate-5')
			$(this).text("Show Less")
		}else{
			$(this).parent().children('.content-field').addClass('truncate-5')
			$(this).text("Show More")
		}
	})
	$('.lightbox-items').click(function(e){
		e.preventDefault()
		uni_modal("","view_attach.php?id="+$(this).attr('data-id'),"large")
	})
	$('.view_more').click(function(e){
		e.preventDefault()
		uni_modal("","view_attach.php?id="+$(this).attr('data-id'),"large")
	})
	$('.like').click(function(){
		var _this = $(this)
		$.ajax({
			url:'ajax.php?action=like',
			method:'POST',
			data:{post_id:$(this).attr('data-id')},
			success:function(resp){
				if(resp == 1){
					_this.addClass('text-primary')
					var lc = _this.siblings('.counts').find('.like-count').text();
							lc = lc.replace(/,/g,'');
							lc = parseInt(lc) + 1
					_this.siblings('.counts').find('.like-count').text(lc)
				}else if(resp==0){
					_this.removeClass('text-primary')
					var lc = _this.siblings('.counts').find('.like-count').text();
							lc = lc.replace(/,/g,'');
							lc = parseInt(lc) - 1
					_this.siblings('.counts').find('.like-count').text(lc)
				}
			}
		})
	})
</script>