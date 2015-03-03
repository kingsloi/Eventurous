<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-categories')); ?>
<div class="page-content">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>Manage Categories</h1>
	</div>
	<p>Select a category to manage</p>
	<div class="helper">
		<span class="badge alert-info steps">1</span>
		<span class="helper-text">Select the course category below</span>
	</div>
	<div class="course-categories row">


		<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      <a class="block" href="/admin/coursecategories/add">
        <div class="equal-height-child btn btn-default btn-block">
          <h2 class="name">Add a New Category...</h2>

        </div>
      </a>
    </div>


		<?php foreach ($categories as $category){?>

			<div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
				<a class="block" href="/admin/coursecategories/edit/<?php echo $category['CourseCategory']['id']?>">
					<div class="equal-height-child btn btn-warning btn-block">
						<h2 class="name"><?php echo $category['CourseCategory']['name'];?></h2>
					</div>
				</a>
			</div>

		<?php } ?>

	</div>
</div><!-- /page-content-->