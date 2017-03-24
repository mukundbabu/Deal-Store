$(document).ready(function () {

	/* on submitting signup form-validate it*/
	$("#signup_form").submit( function() {
		var p1 = document.getElementById("signup_pass").value;
		var p2 = document.getElementById("signup_repass").value;

		if (p1.length < 8) {
			$("#pass_warning").show();
			document.getElementById("signup_pass").value = "";
			$("#signup_pass").css("border-color","red");
			return false;
		}

		if (p1.localeCompare(p2)!=0) {
			$("#repass_warning").show();
			document.getElementById("signup_repass").value = "";
			$("#signup_repass").css("border-color","red");
			return false;
		}

		return true;
	});

	$("#del_deal_form").submit( function() {
		if (confirm('Are you sure you want to delete this deal?'))
		{
			return true;
		}

		return false;
	});

	/*On entering password, validate it*/
	$("#signup_pass").focusout( function() {
		var p1 = document.getElementById("signup_pass").value;

		if (p1.length < 8) {
			$("#pass_warning").show();
			$("#signup_pass").css("border-color","red");
			return false;
		} else {
			$("#pass_warning").hide();
			$("#signup_pass").css("border-color","initial");
			return true;
		}
	});
	
	/*On entering password, validate it*/
	$("#signup_oldpass").focusout( function() {
		
		var p1 = document.getElementById("signup_oldpass").value;

		if (p1.length < 8) {
			$("#oldpass_warning").show();
			$("#signup_oldpass").css("border-color","red");
			return false;
		} else {
			$("#oldpass_warning").hide();
			$("#signup_oldpass").css("border-color","initial");
			return true;
		}
	});

	/*On entering password(again), validate it*/
	$("#signup_repass").focusout( function() {
		var p1 = document.getElementById("signup_pass").value;
		var p2 = document.getElementById("signup_repass").value;

		if (p1.localeCompare(p2)!=0) {
			$("#repass_warning").show();
			$("#signup_repass").css("border-color","red");
			return false;
		}
		else {
			$("#repass_warning").hide();
			$("#signup_repass").css("border-color","initial");
			return true;
		}
	});

	/*validate sign in form, on submitting*/
	$("#signin_form").submit( function() {

		var p1 = document.getElementById("signin_pass").value;

		if (p1.length < 8)
		{
			$("#pass_warning").show();
			document.getElementById("signin_pass").value = "";
			$("#signin_pass").css("border-color","red");
			return false;
		}

		return true;

	});

	/*On entering password, validate it*/
	$("#signin_pass").focusout( function() {

		var p1 = document.getElementById("signin_pass").value;

		if (p1.length < 8)
		{
			$("#pass_warning").show();
			$("#signin_pass").css("border-color","red");
			return false;
		} else {
			$("#pass_warning").hide();
			$("#signin_pass").css("border-color","initial");
			return true;
		}

	});

	/*On submitting the deal add form,submit it*/
	/* Start_date >=today and start_date < end_date are the validations*/
	$("#dealadd_form").submit( function() {
		var d1 = document.getElementById("start_date").value;
		var d2 = document.getElementById("end_date").value;

		var today = new Date();
		today = today.toISOString().substring(0, 10);

		if (d1 < today)
		{
			alert("Sorry, you can't post old deals");
			document.getElementById("start_date").value="mm/dd/yyyy";
			$("#start_date").css("border-color","red");

			return false;
		}

		if (d1 > d2)
		{
			alert("Start Date should be before end date");
			document.getElementById("start_date").value="mm/dd/yyyy";
			document.getElementById("end_date").value="mm/dd/yyyy";
			$("#start_date").css("border-color","red");
			$("#end_date").css("border-color","red");

			return false;
		}

		//var deal_name = document.getElementById("deal_name").value;
		alert(" deal has been added!");
		return true;
	});

	$("#start_date").focusout( function() {
		var d1 = document.getElementById("start_date").value;
		var today = new Date();
		today = today.toISOString().substring(0, 10);

		if(d1 < today)
		{
			$("#start_date").css("border-color","red");
			return false;
		} else {
			$("#start_date").css("border-color","initial");
			return true;
		}

	});

	$("#end_date").focusout( function() {
		var d1 = document.getElementById("start_date").value;
		var d2 = document.getElementById("end_date").value;

		if (d1 > d2)
		{
			$("#start_date").css("border-color","red");
			$("#end_date").css("border-color","red");
			return false;
		} else {
			$("#start_date").css("border-color","initial");
			$("#end_date").css("border-color","initial");
			return true;
		}
	});

	/* When signout is clicked is submitted */
	$("#signout_b").click( function() {
		$.removeCookie('f_name', { path: '/' });
		return true;
	});

	 //MB - function which uses the data from above function to display the items as dropdowns
	$("#placesearchtextbox").autocomplete({
		source: function(request, response) {
			$.ajax({
				url: baseURL+'/placesAutocomplete?keyword='+$("#placesearchtextbox").val(),
				dataType: "json",
				success: function(data){
				response(data);
				}
			});
		},
		minLength: 2
	});

	//MB - function to add a deal to wishlist on click of the button
	$(".listButton").click(function(){
		var dealid = $("#deal_id").val();
		$.post(
			baseURL+'/addWishlist',
			{'dealID': dealid},
			function(data){
				if(data.status == "success")
				{
					$("#addResult").text("Deal added to wishlist");
					$("#addResult").show();
					$("#addResult").slideUp(5000);
					$count = parseInt($.cookie("wishlist")) + 1;
					$.cookie("wishlist", $count);
					$("#wishlist_count").html($count+ " items in your wishlist");
				}
				if(data.status == "exists")
				{
					$("#addResult").text("Deal already exists in wishlist");
					$("#addResult").show();
					$("#addResult").slideUp(5000);
				}
			},
			"json"
		);
	});
	
	$('[name="follow_user"]').click(function(){
		console.log("hello");
		var value = $(this).val();

		var result = value.split("-");
		console.log(result[0]);
		$.post(
      baseURL+'/user/follow/',
      { 'userid': result[1], 'operation': result[0] },
      function(data) {
				console.log("hello1");
				var element1 = "#following"+result[1];
				var element2 = "#follow_img"+result[1];
				if(result[0] == "follow")
				{
					$(element1).css("visibility", "visible");
					$(element2).attr("src", baseURL+"/public/img/button_unfollow.png");
					$(element2).attr("value", "unfollow-"+result[1]);
				}
				else {
					$(element1).css("visibility", "hidden");
					$(element2).attr("src", baseURL+"/public/img/button_follow.png");
					$(element2).attr("value", "follow-"+result[1]);
				}
      },
      'json'
    );
	});

	$('#usersearchbox').autocomplete({
		source: function(request, response) {
			$.ajax({
				url: baseURL+'/usersAutocomplete?keyword='+$('#usersearchbox').val(),
				dataType: "json",
				success: function(data){
					response($.map(data, function (a){
						return {
							value: a.name,
							id: a.id
						};
					}));
					console.log("success");
				}
			});
		},
		minLength: 2,
		select: function(event, ui){
			$('#user_search_id').val(ui.item.id);
			$('#usersearchbox').val(ui.item.value);

			console.log($('#user_search_id').val());
			return false;
		}
	});
	
});

/* Checks sessions to see if any user is logged in and finds his/her email id to display as 'Hi user_name' */
function checkCookie() {

	if ($.cookie('f_name')) {
		
		//$("#post_login").css("display","inline");

		$("#user_name").text("Hi  " + $.cookie("f_name"));
	}
}
