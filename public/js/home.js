$(document).ready(function(){
	create_thumbnail_click_event();
});

function create_thumbnail_click_event() {
	$(".article_thumbnail").click(function(){
		var article_id 	= $(this).attr("id").replace('article_thumbnail_','');
		window.location = "/Article?id="+article_id;
	});
}