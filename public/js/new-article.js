
$(document).ready(function() {

	validation_errors = []; // Global error var.
	
	const_EMPTY_TITLE 				= "Title cannot be empty.";
	const_TITLE_NOT_ENOUGH_CHARS 	= "Title does not have enough characters. String too short.";
	const_NOT_ENOUGH_TAGS 			= "Minimum of 3 tags expected.";

	$("#article-tags").tagit({
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
		if(validateArticle()) {
			createArticle();
		} else {
			alert("new article not created.");
			showvalidationErrors();
		}
	});

	$("#new-article-from").submit(function(ev){
		// ev.preventDefault();
		alert("here");
		$(this).ajaxSubmit({
			target			: '#output',
			beforeSubmit	: beforeSubmit,
			success 		: afterSuccess,
			uploadProgress 	: onProgress,
			resetForm 		: true
		});
		return false;
	});

});

function beforeSubmit(){
	//check whether client browser fully supports all File API
	alert("in beforeSubmit");
	if (window.File && window.FileReader && window.FileList && window.Blob) {
		var fsize = $('#FileInput')[0].files[0].size; //get file size
		var ftype = $('#FileInput')[0].files[0].type; // get file type
        //allow file types 
      	switch(ftype) {
            case 'image/png': 
            case 'image/gif': 
            case 'image/jpeg': 
            case 'image/pjpeg':
            case 'text/plain':
            case 'text/html':
            case 'application/x-zip-compressed':
            case 'application/pdf':
            case 'application/msword':
            case 'application/vnd.ms-excel':
            case 'video/mp4':
            break;
            default:
				$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
         		return false
		}
    
		//Allowed file size is less than 5 MB (1048576 = 1 mb)
		if(fsize>5242880) {
			alert("<b>"+fsize +"</b> Too big file! <br />File is too big, it should be less than 5 MB.");
			return false;
		}
	} 
	else {
		//Error for older unsupported browsers that doesn't support HTML5 File API
		alert("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}
}

function OnProgress(event, position, total, percentComplete) {
	//Progress bar
	$('#progressbox').show();
	$('#progressbar').width(percentComplete + '%') //update progressbar percent complete
	$('#statustxt').html(percentComplete + '%'); //update status text
	if(percentComplete>50) {
		$('#statustxt').css('color','#000'); //change status text to white after 50%
	}
}

//function after succesful file upload (when server response)
function afterSuccess()
{
	$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar
}


function createArticle() {
	var title 		= $("#inputTitle").val();
	var description	= $("#inputDescription").val();
	var tags 		= $("#all-tags").val();
	var primary_img = "/img/default/default.jpg";	// Not using it at the moment.

	tags = tags.split(','); // Similar to php Explode() function
	$.ajax({
		type 		: "POST",
		url			: "/Article/new",
		data		: {"title" : title , "description" : description , "tags" : tags }
	}).done(function(data){
		showSuccessMsg();
		hideNewArticleForm();
	}).fail(function(data){
		alert("Server Error! Could not create article");
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

function hidevalidationErrors() {
	$(".show-validation-error").hide();
}

function showSuccessMsg() {
	var msg = "<h4><strong>Success!</strong> Article Created! </h4>";
	$(".show-validation-success").html(msg);
	$(".show-validation-success").slideDown();
}

function hideSuccessMsg() {
	$(".show-validation-success").hide();
}

function hideNewArticleForm() {
	$(".new-article-form").hide();
}