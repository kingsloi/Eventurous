<aside id="sidebar" style="overflow: hidden;" tabindex="5000">
	<div>
		<ul class="sidebar-menu">
			<li class="<?php echo ($currentPage == 'view-categories' ? 'active' : '');?>">
				<a href="/categories" class="">
					<span class="glyphicon glyphicon-th-large"></span>
					<span>View Categories</span>
				</a>
			</li>
			<?php if ($this->Session->read('categoryID')){?>
				<li class="<?php echo ($currentPage == 'view-courses' ? 'active' : '');?>">
					<a href="/category/<?php echo $this->Session->read('categoryID'); ?>/courses" class="">
						<span class="glyphicon glyphicon-list"></span>
						<span>View Courses</span>
					</a>
				</li>								
			<?php }?>
			<?php if ($this->Session->read('courseID')){?>
			<li class="sub-menu <?php echo ($currentPage == 'add-booking' ? 'active' : '');?>">
				<a class="" href="/nominate-booking">
					<span class="glyphicon glyphicon-plus"></span>
					<span>Add Booking</span>
				</a>
				<ul class="sub hidden">
					<li class="<?php echo ($currentPage == 'add-additional-info' ? 'active' : '');?>"><a href="/add-additional-info" 	class="void-click">Additional Info</a></li>
					<li class="<?php echo ($currentPage == 'booking-complete' ? 'active' : '');?>"><a href="/booking-complete" 	class="void-click">Booking Complete</a></li>
				</ul>
			</li>
			<?php }?>
			<li class="<?php echo ($currentPage == 'faq' ? 'active' : '');?>">
				<a href="/pages/frequently-asked-questions" class="">
					<span class="glyphicon glyphicon-book"></span>
					<span>FAQ</span>
				</a>
			</li>
			<?php if($isAdmin){?>
				<li class="admin-section">
					<div class="admin-section-text"><span>Admin</span></div>
				</li>
				<li class="<?php echo ($currentPage == 'admin-dashboards' ? 'active' : '');?>">
					<a href="/admin/dashboards/" class="">
						<span class="glyphicon glyphicon-dashboard"></span>
						<span>Dashboards</span>
					</a>
				</li>			
				<li class="sub-menu">
					<a class="" href="javascript:;">
						<span class="glyphicon glyphicon-cog"></span>
						<span>Manage</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li class="<?php echo ($currentPage == 'mng-categories' ? 'active' : '');?>">
							<a href="/admin/manage/coursecategories" class="">Categories</a>
						</li>
						<li class="<?php echo ($currentPage == 'mng-courses' ? 'active' : '');?>">
							<a href="/admin/manage/bookingcourses" class="">Courses</a>
						</li>
						<li class="<?php echo ($currentPage == 'mng-events' ? 'active' : '');?>">
							<a href="/admin/manage/events" class="">Events</a>
						</li>
						<li class="<?php echo ($currentPage == 'mng-users' ? 'active' : '');?>">
							<a href="/admin/manage/users" class="">Users</a>
						</li>
					</ul>
				</li>
				<li class="sub-menu">
					<a class="" href="javascript:;">
						<span class="glyphicon glyphicon-book"></span>
						<span>Reports</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li class="<?php echo ($currentPage == 'rpt-all' ? 'active' : '');?>">
							<a href="/admin/reports/index" 	class="">All Reports</a>
						</li>
						<li class="<?php echo ($currentPage == 'rpt-users' ? 'active' : '');?>">
							<a href="/admin/reports/users" 		class="">Users</a>
						</li>
						<li class="<?php echo ($currentPage == 'rpt-regions' ? 'active' : '');?>">
							<a href="/admin/reports/regions" 	class="">Regions</a>
						</li>
						<li class="<?php echo ($currentPage == 'rpt-stores' ? 'active' : '');?>">
							<a href="/admin/reports/stores" 	class="">Stores</a>
						</li>
						<li class="<?php echo ($currentPage == 'rpt-bookings' ? 'active' : '');?>">
							<a href="/admin/reports/bookings" 	class="">Bookings</a>
						</li>
					</ul>
				</li>
				<?php } ?>
		</ul><!-- /sidebar-menu -->
	</div>
	<div class="app-developer-contact">
		<?php 
			//set system contact details
			$appAdminEmail 	= Configure::read('APP_ADMIN_EMAIL');
			$appAdminName 	= Configure::read('APP_ADMIN_NAME');
			$appAdminPhone 	= Configure::read('APP_ADMIN_PHONE');
		?>
		<ul>
			<li class="heading"><span>Problems?</span></li>
			<li><a href="mailto:<?php echo $appAdminEmail;?>?subject=[Booking System]"><span class="glyphicon glyphicon-envelope"></span><span><?php echo $appAdminName;?></span></a></li>
			<li><a href="tel:<?php echo $appAdminPhone;?>"><span class="glyphicon glyphicon-earphone"></span><span><?php echo $appAdminPhone;?></span></a></li>
		</ul>
	</div>
</aside>