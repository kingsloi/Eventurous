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
										<table class="twelve columns">
											<tr>
												<td>
													<?php echo $dataForView['message'];?>
												</td>
												<td class="expander"></td>
											</tr>
											<?php if(isset($dataForView['btnAction'])){?>
												<tr>
													<td>
														<a class="button" href="<?php echo $dataForView['btnAction'];?>">
															<table>
																<tr>
																	<td>
																		Click here to reset your password
																	</td>
																</tr>
															</table>
														</a>
														<br/>
														<p><i>If the above link doesn't work, copy and paste the following link into your web browser</i>:
															<br/>
															<?php echo $dataForView['btnAction'];?>
														</p>
													</td>
													<td class="expander"></td>
												</tr>
											<?php } ?>
										</table>
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