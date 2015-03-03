<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'faq')); ?>
<div class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>

	<div class="page-header clearfix">
		<h1>Frequently Asked Questions</h1>
	</div>

	<p>Looking for Help? The most frequently asked questions are answered below. However, if your question isn't answered below, feel free to contact <a href="mailto:<?php echo Configure::read('APP_ADMIN_EMAIL');?>"><?php echo Configure::read('APP_ADMIN_NAME');?></a>.</p>

	<div class="faq-container">

		<div class="col-md-3 faq-questions">
			<div class="sidebar-subnav">
				<ul class="nav nav-pills nav-stacked">
				</ul>
			</div>
		</div>

		<div class="col-md-9">
			<div class="panel-group" id="accordion">
			  <div class="panel panel-primary">
			    <div class="panel-heading">
			      <h2 class="panel-title">
			        <a href="#faq1" data-toggle="collapse">
			          How do I book on to a course?
			        </a>
			      </h2>
			    </div>
			    <div id="faq1" class="panel-collapse collapse in">
			      <div class="panel-body">
			        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
			      </div>
			    </div>
			  </div>
			  <div class="panel panel-primary">
			    <div class="panel-heading">
			      <h2 class="panel-title">
			        <a  data-toggle="collapse" href="#faq2">
			         How do I edit my profile?
			        </a>
			      </h2>
			    </div>
			    <div id="faq2" class="panel-collapse collapse">
			      <div class="panel-body">
			        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
			      </div>
			    </div>
			  </div>
			  <div class="panel panel-primary">
			    <div class="panel-heading">
			      <h2 class="panel-title">
			        <a  data-toggle="collapse" href="#faq3">
			          How can I get in touch with the course leader?
			        </a>
			      </h2>
			    </div>
			    <div id="faq3" class="panel-collapse collapse">
			      <div class="panel-body">
			        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
			      </div>
			    </div>
			  </div>
			</div><!-- accordian-->
		</div><!-- right column-->
	</div><!-- faqs-->
</div><!-- /page-content -->
