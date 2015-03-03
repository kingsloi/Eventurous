<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'admin-dashboards')); ?>
<div class="page-content">
  <?php echo $this->Session->flash(); ?>
  <div class="page-header">
    <h1>Select a dashboard category</h1>
  </div>
  <p>Please select the category you would like to view.</p>
  <div class="helper">
    <span class="badge alert-info steps">1</span>
    <span class="helper-text">Select the course category below</span>
  </div>
  <div class="course-categories row">
    <?php foreach ($categories as $category){?>

    <div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      <a class="block" href="/admin/dashboards/category/<?php echo $category['CourseCategory']['id']?>/courses">
        <div class="equal-height-child btn btn-primary btn-block">
          <h2 class="name"><?php echo $category['CourseCategory']['name'];?></h2>
        </div>
      </a>
    </div>

    <?php } ?>
  </div>
</div><!-- /page-content-->