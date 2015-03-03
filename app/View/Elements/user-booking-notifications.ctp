<?php
	$requireReview = count($this->requestAction('bookings/review'));
	if($requireReview !== 0){
		echo '<span class="badge badge-success">'.$requireReview.'</span>';
	}
?>