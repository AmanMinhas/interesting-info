
$(document).ready(function(){
	console.log("here");

	//Carousel in the home page shoule be spread across the entire page and not just in the center.
	if($("#signup_carousel").parent().is(".container")) {
		$("#signup_carousel").unwrap();
	}

	$("#signup-header-login-form-div").hide();
	
	$("#signup-header-login").click(function() {
		console.log("signup-header-login click");
		$("#signup-header-login-form-div").slideToggle();
		// $("#signup-header-login-form-div").slideToggle();
	});	

	// click(function() {
	// 	console.log("signup-header-login click");
	// 	$(#signup-header-login).find(".container").slideToggle();
	// 	// $("#signup-header-login-form-div container").slideToggle();
	// });	

	// $("#signup-header-login-hide").click(function() {
	// 	$("#signup-header-login-form-div").slideUp();
	// });

});