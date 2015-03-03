<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'view-categories')); ?>
<div class="page-content">
  <?php echo $this->Session->flash(); ?>
  <div class="page-header">
    <h1>Choose a category</h1>
  </div>
  <p>Please choose a category </p>
  <div class="helper">
    <span class="badge alert-info steps">1</span>
    <span class="helper-text">Select the course category below</span>
  </div>
  <div class="course-categories row">
    <?php foreach ($categories as $category){?>

    <div class="equal-height-container col-lg-3 col-md-6 col-xs-12">
      <a class="block" href="category/<?php echo $category['CourseCategory']['id']?>/courses">
        <div class="equal-height-child btn btn-primary btn-block">
          <h2 class="name"><?php echo $category['CourseCategory']['name'];?></h2>
          <div class="description">
            <?php echo $category['CourseCategory']['desc'];?>
          </div>
        </div>
      </a>
    </div>

    <?php } ?>
  </div>
</div><!-- /page-content-->
