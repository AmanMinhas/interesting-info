
$(document).ready(function() {

	validation_errors = []; // Global error var.
	
	const_EMPTY_TITLE 				= "Title cannot be empty.";
	const_TITLE_NOT_ENOUGH_CHARS 	= "Title does not have enough characters. String too short.";
	const_NOT_ENOUGH_TAGS 			= "Minimum of 3 tags expected.";

	$("#article-tags").tagit({
		// availableTags: sampleTags,
        // This will make Tag-it submit a single form value, as a comma-delimited field.
        singleField: true,
        singleFieldNode: $('#all-tags')
   	});

	// Show Validation Error In This Div
	$(".show-validation-error").hide();

	// On success show this div
	$(".show-validation-success").hide();
	
	// On Submit
	$("#create-article").click(function(ev) {
		ev.preventDefault();
		console.log($("#inputUpload").val());
		if(validateArticle()) {
			// createArticle();
		} else {
			alert("new article not created.");
			// showvalidationErrors();
		}
	});

});

function createArticle() {
	var title 		= $("#inputTitle").val();
	var description	= $("#inputDescription").val();
	var tags 		= $("#all-tags").val(); ;

	tags = tags.split(','); // Similar to php Explode() function
	
	$.ajax({
		type 		: "POST",
		url			: "/Article/new",
		data		: {"title" : title , "description" : description , "tags" : tags },
		success 	: function(msg) {console.log(msg)}
	}).done(function(data){
		// console.log(data);
		// $(".new-article-form").slideUp();
		// $(".show-validation-error").slideUp();
		showSuccessMsg();
		// alert("new article created.");
	}).error(function(data){
		
	});
}

function validateArticle() {
	validation_errors = [];

	// Validate Title
	var title = $("#inputTitle").val();
	if(typeof(title)=="undefined" || title==null || title == "") {
		addValidationError(const_EMPTY_TITLE);
		return false;
	}
	if(title.length < 5) {
		addValidationError(const_TITLE_NOT_ENOUGH_CHARS);
		return false;
	}

	// Validate Tags
	var count_tags = $("#article-tags li").length - 1; // -1 because returns 1 more than the original count.
	if(count_tags < 3) {
		addValidationError(const_NOT_ENOUGH_TAGS);
		return false;
	}

	return true;

}

function addValidationError(msg) {
	validation_errors.push(msg);
}

function showvalidationErrors() {
	$(".show-validation-error").slideDown();
	$(".show-validation-error").empty();
	$(".show-validation-error").html("<ul>");
	$.each(validation_errors,function(k,v){
		var error = "<li>"+v+"</li>";
		$(".show-validation-error").append(error);
	});
	$(".show-validation-error").append("</ul>");
}

function showSuccessMsg() {
	var msg = "<h4><strong>Success!</strong> Article Created! </h4>";
	$(".show-validation-success").html(msg);
	$(".show-validation-success").slideDown();
}