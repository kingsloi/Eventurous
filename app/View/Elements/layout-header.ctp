<header>
	<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
		<div class="navbar-header">
           <button class="navbar-toggle toggle-sidebar-btn " type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
            </button>
			<a href="/" class="navbar-brand">Eventourous</a>
		</div>
	<?php 
		$user 			= $this->Session->read("loggedInUserName");
		$userInitials 	= $this->Session->read("loggedInUserNameInitials");
	?>
			<div class="user-info-container pull-right">
				<ul class="nav navbar-nav">
					<!-- admin -->
					<?php if ($this->params['prefix'] == 'admin') {?>
					<li class="admin-link">
						<div>
							<span class="glyphicon glyphicon-tower"></span>
							<span class="hidden-xs-inline">Admin</span>
						</div>
					</li>						
					<?php }?>

					<!-- notifications--> 
					<li class="notifications ">
						<a href="/bookings/review">
					    	<span class="glyphicon glyphicon-bell"></span>
							<?php echo $this->element('user-booking-notifications');?>
					    </a>
					</li>

					<!-- help-->
					<li class="helper-icon hidden-xs">
						<a href="">
							<span class="glyphicon glyphicon-info-sign"></span>
						</a>
					</li>

					<!-- my account-->
					<li class="dropdown <?php echo ((isset($currentPage)) && $currentPage == 'my-profile' ? 'active' : '');?>">
						<a href="#" class="dropdown-toggle hidden-xs" data-toggle="dropdown">
							<?php echo $user; ?> <b class="caret"></b>
						</a>
						<a href="#" class="dropdown-toggle visible-xs" data-toggle="dropdown">
							<?php echo $userInitials; ?> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="/profile/details"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
							<li><a href="/profile/edit"><span class="glyphicon glyphicon-cog"></span> Edit Profile</a></li>
							<li class="divider"></li>
							<li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						</ul>
					</li>

				</ul>
			</div>
	</nav>
</header>