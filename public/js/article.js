$(document).ready(function() {
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

	$("#add-comment-form").hide();
	$("#add-comment-button").click(function(){
		$("#add-comment-form").toggle();
	});
	
	add_comments(article_id);

	load_comments(article_id);
});

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
	});
}

function populate_comments_div(comment){
	var sub_div 	= get_comments_sub_div(comment);
	$("#view-comments-div").append(sub_div);
}

function get_comments_sub_div(comment){
	// var user	= getUserDataById(comment.user_published);

	var sub_div = "<div class = \"comment-sub-div\" >";
	sub_div 	+= "<p>"+comment.comment+"</p>";
	sub_div 	+= "</div>";

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
