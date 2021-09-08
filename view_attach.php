<?php session_start() ?>
<?php include 'db_connect.php' ?>
<?php 
$posts = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as name,u.profile_pic from posts p inner join users u on u.id = p.user_id  where p.id = {$_GET['id']}");
foreach($posts->fetch_array() as $k => $v){
    $$k = $v;
}
$gal = scandir('assets/uploads/'.$_GET['id']);
unset($gal[0]);
unset($gal[1]);
$count =count($gal);
$i = 0;
$content = str_replace("\n","<br/>",$content);
$is_liked =  $conn->query("SELECT * FROM likes where user_id = {$_SESSION['login_id']} and post_id = {$_GET['id']} ")->num_rows ? "text-primary" : "";
$liked =  $conn->query("SELECT * FROM likes where post_id = {$_GET['id']} ")->num_rows;
$commented =  $conn->query("SELECT * FROM comments where post_id = {$_GET['id']} ")->num_rows;
?>
<style>
.slide img,.slide video{
    max-width:100%;
    max-height:100%;
}
#uni_modal .modal-footer{
    display:none
}
</style>
<div class="container-fluid" style="height:75vh">
<div class="row h-100">
    <div class="col-md-7 bg-dark h-100">
        <div class="d-flex h-100 w-100 position-relative justify-content-between align-items-center">
            <a href="javascript:void(0)" id="prev" class="position-absolute d-flex justify-content-center align-items-center" style="left:0;width:calc(15%);z-index:1"><h4><div class="fa fa-angle-left"></div></h4></a>
            <?php
                foreach($gal as $k => $v):
                    $mime = mime_content_type('assets/uploads/'.$_GET['id'].'/'.$v);
                    $i++;
            ?>
            <div class="slide w-100 h-100 <?php echo ($i == 1) ? "d-flex" : 'd-none' ?> align-items-center justify-content-center" data-slide="<?php echo $i ?>">
            <?php if(strstr($mime,'image')): ?>
                <img src="assets/uploads/<?php echo $_GET['id'].'/'.$v ?>" class="" alt="Image 1">
            <?php else: ?>
                <video controls class="">
                        <source src="assets/uploads/<?php echo $_GET['id'].'/'.$v ?>" type="<?php echo $mime ?>">
                </video>
            <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <a href="javascript:void(0)" id="next" class="position-absolute d-flex justify-content-center align-items-center" style="right:0;width:calc(15%);z-index:1"><h4><div class="fa fa-angle-right"></div></h4></a>
        </div>
    </div>
    <div class="col-md-5 h-100" style="overflow:auto">
        <div class="card card-widget post-card" data-id="<?php echo $id ?>">
            <div class="card-header">
                <div class="user-block w-100">
                    <img class="img-circle" src="assets/uploads/<?php echo $profile_pic ?>" alt="User Image">
                    <span class="username"><a href="#"><?php echo $name ?></a></span>
                    <span class="description">Posted - <?php echo date("M d,Y h:i a",strtotime($date_created)) ?></span>
                </div>
            </div>
            <div class="card-body">
                <p id="content-field"><?php echo $content ?></p>
                <br>
                <button type="button" class="btn btn-default btn-sm like <?php echo $is_liked ?>" data-id="<?php echo $_GET['id'] ?>"><i class="far fa-thumbs-up"></i> Like</button>
                <span class="float-right text-muted counts"><span class="like-count"><?php echo number_format($liked) ?></span> <?php echo $liked > 1 ? "likes" : "like" ?> - <span class="comment-count"><?php echo number_format($commented) ?></span> comments</span>
            </div>
            <div class="card-footer card-comments">
				<?php 
					$comments = $conn->query("SELECT c.*,concat(u.firstname,' ',u.lastname) as name,u.profile_pic FROM comments c inner join users u on u.id = c.user_id where c.post_id = {$_GET['id']} order by unix_timestamp(c.date_created) asc ");
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
                    <textarea cols="30" rows="1" class="form-control comment-textfield" style="resize:none" placeholder="Press enter to post comment" data-id="<?php echo $_GET['id'] ?>"></textarea>
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
        </div>
    
    </div>
</div>
</div>
<script>
    $('#next').click(function(){
        var cslide = $('.slide:visible').attr('data-slide')
        if(cslide == '<?php echo $i ?>'){
            return false;
        }
        $('.slide:visible').removeClass('d-flex').addClass("d-none")
        $('.slide[data-slide="'+(parseInt(cslide) + 1)+'"]').removeClass('d-none').addClass('d-flex')
    })
    $('#prev').click(function(){
        var cslide = $('.slide:visible').attr('data-slide')
        if(cslide == 1){
            return false;
        }
        $('.slide:visible').removeClass('d-flex').addClass("d-none")
        $('.slide[data-slide="'+(parseInt(cslide) - 1)+'"]').removeClass('d-none').addClass('d-flex')
    })
   
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
						var cc = $('.post-card[data-id="'+post_id+'"]').find('.comment-count').first().text();
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
$('.like').click(function(){
		var _this = $(this)
		$.ajax({
			url:'ajax.php?action=like',
			method:'POST',
			data:{post_id:$(this).attr('data-id')},
			success:function(resp){
				if(resp == 1){
					$('.post-card[data-id="'+_this.attr('data-id')+'"]').find('.like').addClass('text-primary')
					var lc = $('.post-card[data-id="'+_this.attr('data-id')+'"]').find('.like-count').first().text();
							lc = lc.replace(/,/g,'');
							lc = parseInt(lc) + 1
					$('.post-card[data-id="'+_this.attr('data-id')+'"]').find('.like-count').text(lc)
				}else if(resp==0){
                    $('.post-card[data-id="'+_this.attr('data-id')+'"]').find('.like').removeClass('text-primary')
					var lc = $('.post-card[data-id="'+_this.attr('data-id')+'"]').find('.like-count').first().text();
							lc = lc.replace(/,/g,'');
							lc = parseInt(lc) - 1
					$('.post-card[data-id="'+_this.attr('data-id')+'"]').find('.like-count').text(lc)
				}
			}
		})
	})
</script>