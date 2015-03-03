<div id="question-list">
	<?php foreach ($questions as $key => $question):

		//question number - not used for anything else	
		$key++;
		$type 				= 'text';
		//question ID
		$questionKey 		= $question['Question']['id']; 

		//force attributes to get decoded as an ARRAY not an OBJECT
		$attributes 		= json_decode($question['Question']['attributes'], true);

		//get question type (i.e. text/radio/checkbox etc.)
		$questionType 		= $attributes['type'];

		//get all the possible values for radio/checkbox/select question (i.e. yes/no 1,2,3,4,5,6 etc)
		$questionValues 	= $attributes['values'];

		//question validation
		$questionValidation = $attributes['validation'];
		
		//set HTML5 'required' attribute
		$questionRequired 	= $attributes['required'] == 'true' ? 'required' : '';

		//className
		$className			= $attributes['className'];

		//css defult wrapper class
		$inlineWrapperDiv = 'block-group';

		//if question type is sales/score
		//set inline css
		//also set HTML5 type to number
		switch($questionType){
			case 'sales':
			case 'score':
				$type = "number";
				$inlineWrapperDiv = "inline-group clearfix";
			break;
		}


		echo "<div class='$inlineWrapperDiv $className'>";
	?>

	<!-- LEGEND = QUESTION NUM / QUESTION TEXT -->
	<legend class="question-heading <?php echo $questionRequired;?>">
		<?php echo '<span>'.$key.')</span> ';?>
		<?php echo '<span>'.$question['Question']['question'] .'</span>';?>
	</legend>

	<!-- DESCRIPTION = QUESTION DESCRIPTION -->
	<?php if(!empty($question['Question']['description'])){?>
		<p class="question-description">
			<?php echo $question['Question']['description'];?>
		</p>
	<?php } ?>

	<?php 
	//get questiontype, print relevent HTML using CakePHP naming conventions
	switch($questionType){
		//if radio button
		case 'radio':
	?>
			<!-- bootstrap toggle buttons -->
			<div class="btn-group" data-toggle="buttons">
				<?php 
				$radioAttributes = array('div'=>false,'before'=>'<label class="btn btn-default">','separator'=>'</label><label class="btn btn-default">','after'=>'</label>', 'type' => 'radio','legend'=>false,'fieldset'=>false,'label'=>false,'options' => $questionValues[0], 'required'=> $questionRequired, 'hiddenField' => false);
				echo $this->Form->input('Questions.'.$questionKey,$radioAttributes);?>
			</div>
	<?php
		break;
		case 'text':
		case 'score':
		case 'score-total':
		case 'sales':
			echo $this->Form->input('Questions.'.$questionKey, array('label'=>false,'div'=>false,'type'=>$type,'class'=>'form-control','required'=> $questionRequired));
		break;
		case 'date':
			echo '<div class="input-group date">';
			echo $this->Form->input('Questions.'.$questionKey, array('div'=>false, 'label' => false,'class'=>'form-control','required'=> $questionRequired));
			echo '<span class="input-group-addon">';
			echo '<i class="glyphicon glyphicon-calendar"></i>';
			echo '</span>';
			echo '</div>';




		break;
	}
	?>
	</div>
<?php endforeach;?>
</div><!-- /question-list-->