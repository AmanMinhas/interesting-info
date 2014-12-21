
$(document).ready(function(){
	console.log("here");
	
	// create_thumbnail_click_event();
	search_by_tags();
	get_last_searched_tags();

	$("#article-tags").tagit({
		singleField: true,
        singleFieldNode: $('#article-tags')
        // singleFieldNode: $('#all-tags')
   	});
});

function create_thumbnail_click_event() {
	$(".article_thumbnail").click(function(){
		var article_id 	= $(this).attr("id").replace('article_thumbnail_','');
		window.location = "/Article?id="+article_id;
	});
}

function get_last_searched_tags() {
	$.ajax({
		"dataType" 	: "json",
		"url"		: "/User/get-last-searched-tags"
	}).done(function(data){
		$.each(data,function(i,tag){
			$("#article-tags").tagit("createTag",tag); 	// Add previously searched tags into the tags area
		});
		//Now simulate a click to get articles from previously searched tags.
		$("#search-by-tags").trigger("click");
	}).fail(function(data){
		console.log("fail - got last searched tags");
		console.log(data);
	})
}

function search_by_tags(){
	$("#search-by-tags").click(function(){
		// ev.preventDefault();
		$(".searched-articles-list-row").empty();
		var tags 		= $("#article-tags").val();

		console.log(tags); 
		// tags = tags.split(',');
		// console.log(tags); 

		$.ajax({
			"dataType"	: 'json',
			"type"		: "POST",
			"url"		: "/Article/search-articles-by-tags",
			"data"		: {"tags":tags}
		})
		.done(function(data){
			console.log("Success");
			console.log(data);
			var articles = data;

			if(articles.length === 0) {
				var no_articles_msg	 = 	"<div class =\"alert alert-info\">";
				no_articles_msg 	+= 		"<strong>Sorry!</strong> We do not have any article that matches your searched criteria.<hr>";
				no_articles_msg 	+= 		"Click <a href=\"/Article/new\" class = \"btn btn-default btn-info \">Here</a> to share something interesting";
				no_articles_msg 	+= 	"</div>";

				$(".searched-articles-list-row").append(no_articles_msg);
			} else {
				$.each(articles,function(i,article){
					createArticleThumbnail(article);
				});
			}
			create_thumbnail_click_event();

		})
		.fail(function(data){
			console.log("Failed");
			console.log(data);
		});
	});
}

function createArticleThumbnail(article){
	console.log("atricle ");
	console.log(article);
	var th 	 = "";
	
	th 	+=	"<div class = 'col-lg-4'>";
	th 	+=		"<div class = 'thumbnail article_thumbnail' id = article_thumbnail_"+article.id+">";
	th 	+=			"<img src = '"+article.primary_img+"' />";
	th 	+=			"<div class = 'caption'>";
	th 	+=				"<h3>"+article.title+"</h3>";
	th 	+=				"<p>"+article.article_text+"</p>";
	th 	+=			"</div>";
	th 	+=		"</div>";
	th 	+=	"</div>";
	// th 	+=				"<img src = '/img/facebook-icon.png' class = 'img-circle' height = '20' widht = '20' alt = 'Share on Facebook' >";

	// console.log(th);
	$(".searched-articles-list-row").append(th);
}