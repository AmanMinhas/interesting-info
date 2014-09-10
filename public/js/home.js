$(document).ready(function(){
	console.log("here");
	create_thumbnail_click_event();

	search_by_tags();

	$("#article-tags").tagit({
		singleField: true,
        singleFieldNode: $('#all-tags')
   	});
});

function create_thumbnail_click_event() {
	$(".article_thumbnail").click(function(){
		var article_id 	= $(this).attr("id").replace('article_thumbnail_','');
		window.location = "/Article?id="+article_id;
	});
}

function search_by_tags(){
	$("#search-by-tags").click(function(){
		// ev.preventDefault();
		$(".searched-articles-list").empty();
		var tags 		= $("#all-tags").val();

		tags = tags.split(',');
		console.log(tags); 
	});
}