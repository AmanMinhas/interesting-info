
$(document).ready(function(){
	console.log("here");
	$("signup-header-login-form-div").hide();
	
	$("signup-header-login").click(function() {
		$("signup-header-login-form-div").slideDown();
	});

	$("signup-header-login-hide").click(function() {
		$("signup-header-login-form-div").slideUp();
	});

});