/*
  * filename: 	app.js
  * version: 	1.0
  * author: 	Kingsley Raspe
*/

$(document).ready(function() {

/*------------------------------------------------------------------------------------------------------------
 *	bookings index table JS Data Tables
-----------------------------------------------------------------------*/
	$('.bookings-list-table').not('.no-results').dataTable({
		"bAutoWidth": 		false,
		"iDisplayLength": 	50
	}).columnFilter({
		aoColumns: [{
				type: "text"
			},
			null,{
				type: "text"
			},{
				type: "text"
			},{
				type: "text"
			},{
				type: "text"
			},null,{
				type: "text"
			}
			]
	});


/*------------------------------------------------------------------------------------------------------------
 *	Clickable Table row (using data-url html5 but works in IE 8, too)
-----------------------------------------------------------------------*/
	if($('tr.clickable').length > 0){

		$(document).on('click','tr.clickable td, tr.clickable th',function(e){

			var newURL 		= $(this).parent().data( "url" );

			window.location = newURL;
		});
	}


/*------------------------------------------------------------------------------------------------------------
 *	TODO:
 *	Disabled 'Nomiation' option for enduser
-----------------------------------------------------------------------*/
	$('#BookingCourseCourseTypeId option[value=1]').prop('disabled', true);



/*------------------------------------------------------------------------------------------------------------
 *	Formatinng Help Pop Up window
-----------------------------------------------------------------------*/
	$('.formatting-help').click(function () {
		window.open('/pages/formatting-help','targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=800')
		return false;
	});

	if($('.popupwindow').length > 0){
		$('body').addClass('no-padding');
	}

/*------------------------------------------------------------------------------------------------------------
 *	Global Sidebar
-----------------------------------------------------------------------*/
	$('#sidebar .sub-menu > a').click(function () {

		var last = $('.sub-menu.open', $('#sidebar'));
		last.removeClass("open");
		$('.arrow', last).removeClass("open");
		$('.sub', last).slideUp(200);
		var sub = $(this).next();

		if (sub.is(":visible")) {

			$('.arrow', $(this)).removeClass("open");
			$(this).parent().removeClass("open");
			sub.slideUp(200);
		} else {

			$('.arrow', $(this)).addClass("open");
			$(this).parent().addClass("open");
			sub.slideDown(200);
		}
		var o = ($(this).offset());
		diff = 200 - o.top;
		if(diff>0){

			$("#sidebar").scrollTo("-="+Math.abs(diff),500);
		}else{

			$("#sidebar").scrollTo("+="+Math.abs(diff),500);
		}

	});

	//---------
	// Sidebar Toggle
	//---------
	$('.toggle-sidebar-btn').click(function () {

		if ($('#sidebar').is(":visible") === true) {

			$('.page-content').addClass('small-left-margin');
			$('#sidebar').css({

				'margin-left': '-195px'
			});

			$('#sidebar').hide();
			$("body").addClass("sidebar-closed");
		} else {

			$('.page-content').removeClass('small-left-margin');
			$('#sidebar').show();
			$('#sidebar').css({

				'margin-left': '0'
			});

			$("body").removeClass("sidebar-closed");
		}
	});

/*------------------------------------------------------------------------------------------------------------
 *	Generate FAQ menu links
-----------------------------------------------------------------------*/
	if($('.faq-container').length > 0){

		$(".faq-container .panel-title a").each(function() {

			questionHash 	= $(this).attr('href');
			questionURL 	= window.location.pathname + questionHash;
			cList = $('.faq-container .sidebar-subnav .nav');
			var li = $('<li/>')
				.addClass('subnav-item')
				.attr('role', 'menuitem')
				.appendTo(cList);

			var a = $('<a/>')
				.addClass('subnav-link faq-link')
				.text(jQuery.trim($(this).text()))
				.attr('href',questionURL)
				.appendTo(li);

		})
	}

	$('.faq-link').click(function(e){

		e.preventDefault();
		questionHref = $(this).attr('href');
		questionLink = questionHref.substring(questionHref.indexOf('#'));
		$(questionLink).collapse('toggle');
	})



/*------------------------------------------------------------------------------------------------------------
 *	Courses list Course Criteria overlay
-----------------------------------------------------------------------*/
	$('.criteria-overlay a').click(function (e) {

		e.preventDefault();
	});
	$('.overlay-agree').click(function (e) {

		currentTab = $(this).closest('.tab-pane').attr('id');
		$('#' + currentTab + ' .criteria-overlay').remove();
	});



/*------------------------------------------------------------------------------------------------------------
 *	Self-Booking events confirmation that that would like to book on to the event
-----------------------------------------------------------------------*/
	$('.self-booking-confirm').click(function() {

		if(!$(this).hasClass('event-full')){

			courseID 	= $(this).data('course');
			courseName 	= $.trim($('.course-'+courseID).text());
			eventID		= $(this).data('event');
			eventName 	= $.trim($('.event-'+eventID).text());

			return confirm('Are you sure you would like to book yourself on '+courseName+' - ' + eventName+'?');
		}
	});



/*------------------------------------------------------------------------------------------------------------
 *	Page subnavigation sidebar
-----------------------------------------------------------------------*/
	$('.sidebar-subnav').affix();





/*------------------------------------------------------------------------------------------------------------
 *	Event FULL/CLOSED
 *
 *	displays a message the event is either full or closed
-----------------------------------------------------------------------*/
	$('.event-list .event-full').click(function() {
		alert('Unfortunately, that event is full. Please choose another event.');
		return false;
	});

	$('.event-list .event-closed').click(function() {
		alert('Unfortunately, that event is closed. Please choose another event.');
		return false;
	});


/*------------------------------------------------------------------------------------------------------------
 *	Vertical nav select menu on smalls screens
 *
 *	tabs aren't mobile friend, this little darling creates <select> version of a tabs
-----------------------------------------------------------------------*/

	// Create the <select> box with necessary classes
	$("<select class='form-control visible-xs course-select'/>").appendTo(".tabs-left .mobile-headings");

	//Creat the default options
	$("<option />", {
		"selected": "selected",
		"value"   : "",
		"text"    : "Select..."
	}).appendTo(".tabs-left select.course-select");

	//Get current URL
	//this is used for #tabs
	var pathname 	= window.location.pathname;
	selectCounter 	= 0;


	// Populate dropdown with tab options
	// append those options to select
	$(".tabs-left .tab-heading a").each(function() {

		var el = $(this);
		$("<option />", {
			"value"   : selectCounter++,
			"text"    : el.text()
		}).appendTo(".tabs-left select.course-select");
	});

	//on selecting a course form the dropbox
	//show the corresponding tab
	$('.course-select').change(function() {

		$('.tab-heading li a').eq($(this).val()).tab('show');
	});



/*------------------------------------------------------------------------------------------------------------
 *	Additional Booking Questions approval
 *
 *	When a booking requires additional information to be added i.e. MDP Level 1/2, there's a Y/N approval
 *	this change function handles whether the user selects Y or N
-----------------------------------------------------------------------*/

	$('.booking-details .approval input').change(function(){

		//if Approval is No
		if($(this).val() == 'N'){

			//move questions from <form>
			//move to a div outside of <form> and slide up
			questionsBackupHTML = $('.booking-details .questions').html();
			$('#empty-form-container').html(questionsBackupHTML);
			$('.booking-details .questions').slideUp(function(){
				$('.questions #question-list').remove();
			});


		//if Approval is Yes
		}else if($(this).val() == 'Y'){

			//check if <div> outside of <form> has children
			//if it has children i.e. questions have been moved there
			//move questions back inside <form> and empty <div>
			if($('#empty-form-container').children().length > 0 ) {

				questionsRestoreHTML = $('#empty-form-container').html();
				$('.booking-details .questions').html(questionsRestoreHTML);
				$('.booking-details .questions').slideDown(function(){
					$('#empty-form-container #question-list').remove();
				});
			}else{

				//if div outside of <form> doesn't have children
				//just show the questions
				$('.booking-details .questions').show();
			}
		}

		//show booking notes regardless I think?
		$('.booking-details .booking-notes').show();
	})

	//if POST'd and a list of errors is visibile
	//bro, you best he showin' them questions
	//otherwise things get hella confusing
	if($('.has-errors').length > 0) {
		$('.booking-details .questions').show();
	}

	//bootstrap's sexy radio button styles
	//when a radio button is "checked"
	//apply a class to parent element to show that's it's 'checked'
	$('.btn-group input[type=radio]:checked').each(function(){
		$(this).parent().addClass('active');
	})



/*------------------------------------------------------------------------------------------------------------
 *	HELP
 *
 * 	When clicking the help incon on the top right of the page
-----------------------------------------------------------------------*/
	$('.helper-icon a').click(function (e) {
		e.preventDefault();
		$(this).toggleClass('help-on');
		$('.helper').toggleClass('show');
	});



/*------------------------------------------------------------------------------------------------------------
 *	Page subnav current page markers
-----------------------------------------------------------------------*/

		//if parent has a child which has a link that's currently being viewed
		//add 'active open' classes to parent, so that it opens
		//and shows the children
		$(".sidebar-menu .sub-menu li").each(function() {

			var navItem = $(this);
			if(navItem.hasClass("active")){

				navItem.parent().parent().addClass('active open');
				navItem.parent().removeClass('hidden');
			}
		});

/*------------------------------------------------------------------------------------------------------------
 *	Bootstrap Accordian linking
 *
 *	Adds ability to directly link to a accordian section
-----------------------------------------------------------------------*/

	if(location.hash !== ''){

		//if has hash, append tab_ to hash name, and open the tab being requested!
		$('.nav-tabs a[href="' + location.hash.replace('tab_','') + '"]').tab('show');
	}else{

		//if no hash, show first tab
		$('.nav-tabs a:first').tab('show');
	}

	//on tab change
	//append tab_ tabname to url
	$('.nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

		location.hash = 'tab_'+  e.target.hash.substr(1) ;
		return false ;
	});



/*------------------------------------------------------------------------------------------------------------
 *	Body Classes
-----------------------------------------------------------------------*/

	//if body has "confirm-on-exit" class, confirm that the user wants to live the page
	if($('body').hasClass('confirm-on-exit')){

		$('#BookingAdditionalBookingInfoForm').submit(function(){

			window.onbeforeunload = null;
		});
		window.onbeforeunload = function() {

			return "You're about to end your session, are you sure?";
		}
	}

	//if body has "disabled-buttons" class, disable edit/submit buttons
	if($('body').hasClass('disable-buttons')){

		$('.editLink')	.addClass('disabled');
		$('.disabled a').addClass('void-click');
		$(':submit')	.addClass('disabled');
	}
	$(".disabled").on("click","a.void-click", function() {

		return false;
	});
	$(".void-click").click(function (e) {

		return false;
	});


/*------------------------------------------------------------------------------------------------------------
 *	Cancel Booking
-----------------------------------------------------------------------*/
	$("#cancel-booking").click(function (e) {

		return confirm('Are you sure you would like to cancel your booking for this event?');
	});


/*------------------------------------------------------------------------------------------------------------
 *	Page Error
 *
 *	if page has an error message, scroll to top to make sure user sees it
-----------------------------------------------------------------------*/

	//----------------
	//	if page has div.alert, scroll page to top
	//----------------
	if($('.alert').length > 0) {

		$("html, body").animate({ scrollTop: 0 }, "slow");
	}

	//----------------
	//	if page has danger message, slide message up after 6 seconds
	//----------------
	$('.alert.alert-danger').bind('close.bs.alert', function () {

		$(this).slideUp(400);
	});


/*------------------------------------------------------------------------------------------------------------
 *	jQuery Datepicker magic
 *
 *	add datepicker ability to nicen stuff up
-----------------------------------------------------------------------*/

	$('.input-group.date').datepicker({
		todayBtn: "linked",
		multidate: false,
		autoclose: true
	});


/*------------------------------------------------------------------------------------------------------------
 *	Nomination Search disable moving on until a user has been selected
 *
 *	add datepicker ability to nicen stuff up
 *	Readonly (instead Of Disabled Due To Disabled = No _post)
-----------------------------------------------------------------------*/

	//search button
	$("#SearchNominateBookingForm").submit(function() {

		//get search option - hrms/firstname/surname
		searchOption = $("input:radio[name='data[Search][criteria]']:checked");

		//if search value & search option !empty
		if($("#SearchSearch").val() == "" || searchOption == false){

			$("#SearchSearch").addClass("shake");
			$(".search-option-fields").addClass('has-error');

			//disable default action
			return false;
		}else{

			//if search query less than 4 charachters
			if($("#SearchSearch").val().length < 4){

				$("#responseError").html("Please enter more than 3 characters...").slideDown();
				return false;
			}

			//SEARCH DA USERS
			userSearch();

			//remove classes/animation
			$("#SearchSearch").removeClass("shake");
			$('#SearchSearch').css('animation-name', 			'none');
			$('#SearchSearch').css('-moz-animation-name', 		'none');
			$('#SearchSearch').css('-webkit-animation-name', 	'none');

			//shake
			setTimeout(function() {

				$('#SearchSearch').css('-webkit-animation-name','shake');
			}, 0);

			//disable default action
			return false;
		}
	});

	//nomiate employee form
	$("#BookingNominateBookingForm").submit(function() {

		//if not  username & search box aren't empty
		if ($("#SearchSearch").val() == '' && $('#UserUsername').val() == ''){

			$('#SearchSearch').parent().addClass('has-error');
			$('#SearchSearch').focus();

			//disable actually submitting
			return false;
		}
	});


/*------------------------------------------------------------------------------------------------------------
 *	Nominated booking searching ability
 *
 *	On Click, Append Data From Multiple User Table To Form
-----------------------------------------------------------------------*/

	$('#results tbody').on("click", "tr", function(event){

		$('#results .selected')		.removeClass('selected');
		$(this)						.addClass('selected');
		clickedUserID 				= $(this).attr("id");
		userDetails 				= '#results tbody tr#'+clickedUserID;
		$('#UserUsername')			.val($(userDetails +' .hrms').text());
		$('#ProfileFirstName')		.val($(userDetails +' .fname').text());
		$('#ProfileSurname')		.val($(userDetails +' .sname').text());
		$('#RegionName')			.val($(userDetails +' .region').text());
		$('#StoreName')				.val($(userDetails +' .store').text());
		$('#BookingArd')			.val($(userDetails +' .ard').text());
		$('#ProfileId')				.val($(userDetails +' .userid').text());
	});



/*------------------------------------------------------------------------------------------------------------
 *	Sexy Bootstrap fileselect
 *
 *	Improve look of upload button/html
 *	@http://labs.abeautifulsite.net/demos/bootstrap-file-inputs/
-----------------------------------------------------------------------*/
	$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
		var input = $(this).parents('.input-group').find(':text'),
		log = numFiles > 1 ? numFiles + ' files selected' : label;

		if( input.length ) {
			input.val(log);
		} else {
			if( log ) alert(log);
		}
	});

	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	});



/*------------------------------------------------------------------------------------------------------------
 *	resonsiveView()
 *
 *	Handles when a browser is resized
 *	# Called on 1) page load 2) page resize
-----------------------------------------------------------------------*/
	function responsiveView() {
		if($('.small-centered').length == 0){

			var wSize = $(window).width();
			if (wSize <= 768) {

				$('body').addClass('sidebar-close');
				$('#sidebar').hide();
				$('.page-content').addClass('small-left-margin');
				$('.user-info-container').addClass('mobile-style');
				$('.sidebar-subnav').css('width', 'auto');
			}

			if (wSize > 768) {

				$('body').removeClass('sidebar-close');
				$('#sidebar').show();
				$('.page-content').removeClass('small-left-margin');
				$('.user-info-container').removeClass('mobile-style');
				$('.sidebar-subnav').width($('.sidebar-subnav').parent().width());
			}
		}

		//----------------
		//	EQUAL HEIGHT DIVs
		//----------------
		$('.equal-height-container a').eqHeights({child:'.equal-height-child'});

		$('.eheight-tab').eqHeights({child:'.eheight-tab-element'});
	}


/*------------------------------------------------------------------------------------------------------------
 *	On window/document change
-----------------------------------------------------------------------*/
	//handle resize
	$(window).on('load', responsiveView);
	$(window).on('resize', responsiveView);

//End of Document Ready
});





/*------------------------------------------------------------------------------------------------------------
 *
 *	Functions
 *
-----------------------------------------------------------------------*/
	//----------------
	//	Ajax - Retreive All user information based on their HRMS/FirstName/Surname
	//----------------
	function userSearch(){

		var data = $("#SearchNominateBookingForm").serialize();

		$('#SearchNominateBookingForm')		.removeClass('has-error');
		$(".search-option-fields")			.removeClass('has-error');
		$("#responseError")					.slideUp();
		$('#multiple-results')				.hide();
		$('#multiple-results tr').not(':first').remove();

		$.ajax({

			type: 		"post",
			url: 		"/users/search/",
			data: 		data,
			dataType: 	"json",
			success: function(response, status) {

				searchSuccess(response, status);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

				searchError(XMLHttpRequest, textStatus, errorThrown);
			}
		});

		return false;
	}
	function searchSuccess(response, status) {

		// if response was a success
		if (response.success) {

			//empty inputs of form on right
			$('#BookingBookingInfoForm').find('input[type=text], input[type=hidden], textarea').val('');

			//if only 1 i.e. searching by unique value hrms
			if(response.searchType == 'hrms'){

				$('#UserUsername')		.val(response.data.User.username);
				$('#ProfileId')			.val(response.data.Profile.id);
				$('#ProfileFirstName')	.val(response.data.Profile.first_name);
				$('#ProfileSurname')	.val(response.data.Profile.surname);
				$('#RegionName')		.val(response.data.Region.name);
				$('#StoreName')			.val(response.data.Store.name);
				$('#BookingArd')		.val(response.data.Profile.ard);

			//else if not searching by a unique value
			//if multiple users are returned
			}else if(response.totalUsers > 1){

				//show #multiple-results table
				var multipleUsersTable = "";
				$('#multiple-results').show();

				//set counter
				var i = 0;

				//build row for each user returned
				$.each(response.data, function(i, entry) {
					multipleUsersTable +=
							'<tr id="user-' + i + '">' +
								'<td class="userid hidden">'+	response.data[i].Profile.id 	+'</td>' +
								'<td class="ard hidden">'+		response.data[i].Profile.ard+'</td>' +
								'<td class="hrms">'+			response.data[i].User.username+'</td>' +
								'<td class="name">'+
									'<span class="fname">'+		response.data[i].Profile.first_name+
									'</span> '+
									'<span class="sname">'+		response.data[i].Profile.surname+
								'</span></td>' +
								'<td class="location">'+
									'<span class="region">'+	response.data[i].Region.name+
									'</span><br/>'+
									'<span class="store">'+		response.data[i].Store.name+
								'</span></td>' +
							'</tr>';
					i++;
				});

				//append table rows to table in dom
				$('#results tbody').html(multipleUsersTable);


			} else{

				//else if searching by firstname/suranme (not unique)
				//but total users returned is 1 (i.e. only 1 user returned)
				//get 0-index array (because arrays are 0 indexed)

				$('#UserUsername')		.val(response.data[0].User.username);
				$('#ProfileId')			.val(response.data[0].Profile.id);
				$('#ProfileFirstName')	.val(response.data[0].Profile.first_name);
				$('#ProfileSurname')	.val(response.data[0].Profile.surname);
				$('#RegionName')		.val(response.data[0].Region.name);
				$('#StoreName')			.val(response.data[0].Store.name);
				$('#BookingArd')		.val(response.data[0].Profile.ard);

			}


		}else{

			//else if not success i.e. FAILUREEEEEEEEEE
			$('#BookingBookingInfoForm').find('input[type=text], textarea').val('');

			var errors = [];

			//check if response returned is object/array
			if (typeof(response.data) == ("object" || "array")) {

				//loop through each array element
				//build a list of array elements
				$.each(response.data, function(key, value) {

					var text = isNaN(key) ? key + ": " + value : value;
					errors.push("<span>"+ text +"</span>");

				});

			} else {

				//else if not array/object i.e. string
				errors.push("<span>"+ response.data +"</span>");
			}

			//send items to dom
			errors 							= errors.join("\n");
			$("#responseError")				.html(errors).slideDown();
			$('#SearchNominateBookingForm')	.addClass('has-error');
		}
	}

	//if problem with AJAX call
	function searchError(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus);
		console.log(errorThrown);
		console.log(XMLHttpRequest);
		//show error message
		$("#responseError").html("<li>An unexpected error has occurred.</li>").slideDown();
	}