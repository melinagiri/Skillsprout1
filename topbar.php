<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-dark py-1">
    <!-- Left navbar links -->
    <div class="container" style="margin:0">
      <ul class="navbar-nav">
        <?php if(isset($_SESSION['login_id'])): ?>
        <li class="nav-item ">
          <div class="d-flex position-relative">
            <h3 class="text-primary" style="top:-25px;"><i style="background:#f600bf -webkit-linear-gradient(#f600bf, blue);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;">SkillSprout</i> <i class="fab fa-studiovinari rounded-circle" style="color:white !important"></i></h3>
          </div>
        </li>
      <?php endif; ?>
        <li>
          <!-- <a class="nav-link text-white"  href="./" role="button"> <large><b>SkillSprout</b></large></a> -->
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
       
      <?php if(isset($_GET['page']) && $_GET['page'] !='additional_info'): ?>
        <li class="nav-item">
          <a class="nav-link nav-home" href="./">
          <i class="fas fa-home"></i><b> Home</b>
          </a>
        </li>
        
      <?php endif; ?>
      <li class="nav-item nav-link disabled">
          <i class="fas fa-bell"></i>
          </a>
        </li>
          <li class="nav-item dropdown">
            <a class="nav-link"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
              <span>
                <div class="d-flex badge-pill align-items-center bg-gradient-primary p-1" style="background: #337cca47 linear-gradient(180deg,#268fff17,#007bff66) repeat-x!important;bprder:50px">
                  <?php if(isset($_SESSION['login_profile_pic']) && !empty($_SESSION['login_profile_pic'])): ?>
                    <div class="rounded-circle mr-1" style="width: 25px;height: 25px;top:-5px;left: -40px;">
                      <img src="assets/uploads/<?php echo $_SESSION['login_profile_pic'] ?>" class="image-fluid image-thumbnail rounded-circle" alt="" style="max-width: calc(100%);height: calc(100%);">
                    </div>
                  <?php else: ?>
                  <span class="fa fa-user mr-2" style="width: 25px;height: 25px;top:-5px;left: -40px"></span>
                  <?php endif; ?>
                  <span ><b><?php echo ucwords($_SESSION['login_firstname']) ?></b></span>
                  <span class="fa fa-angle-down ml-2"></span>
                </div>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
              <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
              <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
            </div>
          </li>
      </ul>
    </div>
  </nav>
  <style>
     .cart-img {
          width: calc(25%);
          max-height: 13vh;
          overflow: hidden;
          padding: 3px
      }
      .cart-img img{
        width: 100%;
        /*height: 100%;*/
      }
      .cart-qty {
        font-size: 14px
      }
  </style>
  <!-- /.navbar -->
  <script>
    $(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      if($('.nav-link.nav-'+page).length > 0){
        $('.nav-link.nav-'+page).addClass('active')
          console.log($('.nav-link.nav-'+page).hasClass('tree-item'))
        if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
          $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
          $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

      }
      $('#manage_my_account').click(function(){
        uni_modal('Manage Account','manage_account.php')
      })
    })
  </script>
