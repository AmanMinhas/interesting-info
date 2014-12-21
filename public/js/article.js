$(document).ready(function() {
	console.log("in article.js ");
	
	var comma_separated_tags 	= "";
	var tags 					= [];
	var article_id				= $("#hidden-article-id").val();

	$("#readOnlyTags").css("border","none");
	$("#readOnlyTags").css("cursor","pointer");

	comma_separated_tags 	= $("#hidden-tags").val();
	tags 					= comma_separated_tags.split(",");

	$.each(tags,function(i,v){
		$("#readOnlyTags").append("<li>"+tags[i]+"</li>");
	});

	$('#readOnlyTags').tagit({
		readOnly: true
	});

	$("#add-comment-form-0").hide();
	$("#add-comment-button").click(function(){
		console.log("comment button clicked");
		$("#add-comment-form-0").toggle();
	});
	//Hide Add Comment on Cancel Click
	handle_cancel_comment_button();

	add_comments(article_id);

	load_comments(article_id);

});

function handle_cancel_comment_button(){

	$(".cancel-comment-button").click(function(ev){
		ev.preventDefault();
		var this_id = $(this).attr('id').replace(/cancel-comment-button-/,""); 
		$("#add-comment-form-"+this_id).hide();
		console.log("hiding #add-comment-form-"+this_id);
	});
}

function add_comments(article_id) {
	$("#post-comment-button").click(function(ev){
		ev.preventDefault();

		var comment 	= $("#comment-textarea").val();

		$("#add-comment-form").trigger("reset");
		$("#add-comment-form").hide();

		$.ajax({
			"type"	: "POST",
			"url"	: "/Article/ajax-add-comment",
			"data"	: {"comment" : comment, "article_id" : article_id}
		}).done(function(new_comment_id){
			var cmnt 	 	= get_comment_by_id(new_comment_id);
			var sub_div 	= get_comments_sub_div(cmnt);
			$("#view-comments-div").prepend(sub_div);
		});
	});
}

function load_comments(article_id) {
	
	$.getJSON("/Article/get-article-comments?article_id="+article_id, function(data){
		var comments = [];
		$.each(data,function(i,v){
			populate_comments_div(v);				
		});
		$(".reply-to-comment").click(function(){
			reply_to_comment_click($(this));
		})
	});
}

function populate_comments_div(comment){
	var sub_div 	= get_comments_sub_div(comment);
	$("#view-comments-div").append(sub_div);
}

function get_comments_sub_div(comment){
	// var user	= getUserDataById(comment.user_published);

	var sub_div  = 	"<div id = \"comment-sub-div+"+comment.id+"\" class = \"comment-sub-div\" >";
	sub_div 	+= 		"<table class = \"comment-sub-div-table\">";
	sub_div 	+= 			"<tr>"; 
	sub_div 	+= 				"<td rowspan = \"2\" colspan = \"2\" >"; 
	sub_div 	+= 					"<img class = \"user-profile-thumbnail\" src=\"/img/default/default.jpg\" >";
	sub_div 	+= 				"</td>";
	sub_div 	+= 				"<td>"+comment.user_published+"</td>";
	sub_div 	+= 			"</tr>";
	sub_div 	+= 			"<tr>";
	sub_div 	+= 				"<td>"+comment.comment+"</td>";
	sub_div 	+= 			"</tr>";
	sub_div 	+= 		"</table>";
	sub_div 	+= 		"<i class = \"reply-to-comment pull-right\" id = \"reply-to-comment-"+comment.id+"\">Reply</i>"
	sub_div 	+= 	"</div>";

	return sub_div;
}

function get_comment_by_id(comment_id) {
	var ret_comment = "";

	$.ajax({
		"dataType" 	: "json",
		"type"		: "POST",
		"url"		: "/Article/get-article-comment-by-id",
		"data"		: {"comment_id" : comment_id},
		"async" 	: false
	}).done(function(comment){
		ret_comment = comment; 
	});

	return ret_comment;
}

function reply_to_comment_click(element) {
	element.hide();
	create_reply_to_comment_form(element);
	handle_cancel_comment_button();
}

function create_reply_to_comment_form(element) {
	var parent_div 	= element.parent();
	
	var parent_comment_id 	= element.attr('id').replace(/reply-to-comment-/,"");
								   
	parent_div.append("<form id =\"add-comment-form-"+parent_comment_id+"\" >");
	parent_div.append(	"<textarea></textarea>");
	parent_div.append(	"<button id = \"post-comment-button-"+parent_comment_id+"\" class=\"post-comment-button btn btn-primary\">Post</button>");
	parent_div.append(	"<button id = \"cancel-comment-button-"+parent_comment_id+"\" class=\"cancel-comment-button btn btn-default\">Cancel</button>");
	parent_div.append("</form>");

}
