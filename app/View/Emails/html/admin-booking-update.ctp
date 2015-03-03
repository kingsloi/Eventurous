<?php

	/*
		NAME
	*/
	if($dataForView['emailOnBehalfOf'] == true && isset($dataForView['bookedBy'])){
		$greetingFullName = $dataForView['bookedBy']['fullname'];
		$bookedForFlag		= true;
	}else{
		$greetingFullName = $dataForView['bookedFor']['fullname'];
		$bookedForFlag		= false;
	}
	//explode full name
	$greetingNameArray  = explode(" ", $greetingFullName);
	//get first name
	$greetingFirstName  = $greetingNameArray[0];


	/*
		Booked for user block
	*/
	$bookedForData = "";
	if($bookedForFlag == true){
		$bookedForData = $this->element('email-booked-for', array('data'=>$dataForView['bookedFor']));
	}



	/*
		Course Contact Information
	*/
	if(isset($dataForView['BookingCourseDetails'])){
		$contactInfoData = $this->element('email-contact-info', array('data'=>$dataForView['BookingCourseDetails']));
	}else{
		$contactInfoData = "";
	}


	/*
		Button action text
	*/
	if(isset($dataForView['btnAction'])){
		$btnAction = $dataForView['btnAction'];
	}else{
		$btnAction = 'Please login to your account to view the changes to your booking(s)';
	}

	/*
		sidebar element
	*/
	if($dataForView['changeType'] == 'status'){
		$sidebarData = $this->element('email-status-change', array('data'=>$dataForView));
	}
	elseif($dataForView['changeType'] == 'event'){
		$sidebarData = $this->element('email-event-change', array('data'=>$dataForView));
	}



?>



<table class="body">
	<tr>
		<td class="center" align="center" valign="top">
			<center>

				<table class="row header">
					<tr>
						<td class="center" align="center">
							<center>

								<table class="container">
									<tr>
										<td class="wrapper last">

											<table class="twelve columns">
												<tr>
													<td class="six sub-columns " style="text-align:left; vertical-align:middle;">
														<span class="template-label">Bookr - Phones 4u Event Booking</span>
													</td>
													<td class="six sub-columns last" style="text-align:right; vertical-align:middle;">
														<img src="http://i.imgur.com/I1mb01a.png" style="float:right">
													</td>


												</tr>
											</table>

										</td>
									</tr>
								</table>

							</center>
						</td>
					</tr>
				</table>

				<br>

				<table class="container">
					<tr>
						<td>

							<!-- content start -->

							<table class="row">
								<tr>
									<td class="wrapper">

										<table class="seven columns">
											<tr>
												<td>
													<h4>Hi, <?php echo $greetingFirstName; ?>!</h4>
													<p class="top-spacing">
														<?php echo $dataForView['text'];?>
													</p>
													<p>
														<?php echo $dataForView['additionalText'];?>
													</p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>
										<table class="seven columns">
											<tr>
												<td>
													<a class="button" href="#">
														<table>
															<tr>
																<td>
																	<?php 
																		echo $btnAction;
																	?>
																</td>
															</tr>
														</table>
													</a>
												</td>
												<td class="expander"></td>
											</tr>
											<tr>
												<td>
													<p><i>Please note, this is an automated email. Please do not reply.</i></p>
													<p><i>Please contact the course leader for more information or any queries related to your booking(s).</i></p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>
									</td>
									<td class="wrapper last">

										<?php 
											echo $bookedForData;
										?>


										<?php 
											echo $sidebarData;
										?>


										<?php
											echo $contactInfoData;
										?>

									</td>
								</tr>
							</table>
							<br>
							<br>
							<!-- Legal + Unsubscribe -->            
							<table class="row">
								<tr>
									<td class="wrapper last">

										<table class="twelve columns">
											<tr>
												<td align="center">
													<center>
														<p style="text-align:center;"><a href="#">Bookr</a></p>
													</center>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>

							<!-- container end below -->
						</td>
					</tr>
				</table> 

			</center>
		</td>
	</tr>
</table>