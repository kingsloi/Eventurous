<?php echo $this->element('layout-header'); ?>
<?php echo $this->element('layout-sidebar', array('currentPage'=>'mng-users')); ?>
<section class="page-content clearfix">
	<?php echo $this->Session->flash(); ?>
	<div class="page-header">
		<h1>User Import</h1>
	</div>
	<p>Upload a new csv using the upload form provided. Once uploaded, select which csv you would like to import by clicking <em>Import</em> to import the new user csv. <strong>Be careful</strong>, importing an incorrect or old user csv could potentially break the system.</p>

	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h2 class="panel-title">Upload a new User .CSV</h2>
			</div>
			<div class="panel-body">
				<p>Please ensure that the .csv to be uploaded is from <b>Charlene (HR Services)</b>. Failure to upload into the correct format could potentially break the system and ruin everyones day/week/month/possibly year. Broken system = unhappy Kingsley :(</p>
				<?php echo $this->Form->create('Upload', array('type' => 'file','class'=>'form-horizontal')); ?>
				<?php echo $this->Form->error('Upload.file', null, array('class' => 'alert alert-danger')); ?>
				<div class="input-group block-group">
					<span class="input-group-btn">
						<span class="btn btn-primary btn-file">
							Browse&hellip; <?php echo $this->Form->input('Upload.file', array('type' => 'file', 'div'=>false,'label'=>false,'error'=>false)); ?>
						</span>
					</span>
					<input type="text" readonly="" class="form-control">
					<?php echo $this->Form->input('Upload.dir', array('type' => 'hidden'));?>
				</div>

				<div class=" block-group clearfix">
					<button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-cloud-upload spaced"></span>Upload User .CSV</button>
				</div>
				<?php echo $this->Form->end(); ?>
				<p class="clearfix"><small><a href="/files/import-example.csv" class="pull-right">See example .csv with correct format</a></small></p>

			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h2 class="panel-title">Import/Overwrite current Users table</h2>
			</div>
			<div class="panel-body">
				<p>To overwrite the current users table (i.e. every single user in the system + their bookings), select which file you would like to import (most like the most recently uploaded(first) and click import. Before you do click import, please make sure you are 100% happy with importing/updating all users.</p>
				<ul class="list-group">
					<?php foreach($allUserCsvUploads as $id => $userCsvUpload):?>

						<?php

							//set variables
							$htmlClass 			= $disabled = $additionalImportInfo = "";
							$disabledActions   	= 'false';
							$csvImportFileName 	= $userCsvUpload['Upload']['file'];
							$csvImportFileID	= $userCsvUpload['Upload']['id'];
							$csvImportFilePath 	= "/files/upload/file/".$csvImportFileName;

							//if hasBeenImported is set
							if(isset($userCsvUpload['Upload']['hasBeenImported'])){

								$hasBeenImported 	= $userCsvUpload['Upload']['hasBeenImported'];
								$importedBy 		= (isset($userCsvUpload['Upload']['importedBy'])? $userCsvUpload['Upload']['importedBy'] : '');


								//if hasBeenImported is oldImportData
								if($hasBeenImported == 'oldImportData'){

									//data import is too old
									$additionalImportInfo 	= 'Data too old to import.';
									$htmlClass 				= 'user-import-too-old';
								}else{

									//if is a date instead of oldImportData (only 2 options)
									//format dates
									$hasBeenImported 		= $this->App->formatDatesPretty($hasBeenImported);

									//additonal info text
									$additionalImportInfo 	= '<b>'. $importedBy . '</b> imported on ';
									$additionalImportInfo 	.= '<b>' . $hasBeenImported . '</b>';
									$htmlClass 				= 'user-import-already-imported';
								}

								//disable actions
								$disabledActions 	= 'true';
								$disabled 			= (($disabledActions == 'true')? 'disabled' : '');

							}

						?>



						<li class="list-group-item <?php echo $htmlClass; ?>">

							<div class="row">
								<h4 class="col-lg-8 col-md-12">
									<a href="<?php echo $csvImportFilePath; ?>">
										<?php echo $csvImportFileName; ?>
									</a>
								</h4>
									<div class="col-lg-4 col-md-12">
										<div class="btn-group pull-right btn-group-sm">
											<?php echo $this->Form->postLink(__('Import'),
												array(
													'controller' 	=> 'Uploads',
													'action' 		=> 'processUserImport', $csvImportFileName, $csvImportFileID
												),
												array(
													'class' => 'btn btn-primary',
													'disabled' => $disabled), __('Are you sure you want to import %s?', $csvImportFileName)
												);
											?>
											<?php echo $this->Form->postLink(__('Delete'),
												array(
													'controller' 	=> 'Uploads',
													'action' 		=> 'deleteUserImport', $csvImportFileName, $csvImportFileID
												),
												array(
													'class' => 'btn btn-danger',
													'disabled' => $disabled), __('Are you sure you want to delete %s?', $csvImportFileName)
												);
											?>
										</div>
									</div>
							</div>
							<div class="row">
								<p class="pull-right small-text"><?php echo $additionalImportInfo;?></p>
							</div>
						</li>
					<?php endforeach; ?>
					<?php if(empty($allUserCsvUploads)): ?>

						<?php echo $this->element('no-results-found', array('text'=>'No uploads found.'));?>

					<?php endif;?>
				</ul>

			</div>
		</div>
	</div>

</section>