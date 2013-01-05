$(document).ready(function(){

	$("#fold-year").live("click", function(event){
		var id = $(this).attr("rel");
		var symbol = $(this).children('.symbol');
		var icon = (symbol.html() == "▼"? '►':'▼');
//		var icon = (html == "&#9660;"? '&#x25B6;':'&#9660;');
		symbol.html(icon);
	
		$("#" + id).toggle('slow', function() {
		    // Animation complete.
		  });
		event.preventDefault();
	});
	
	$("#fold-month").live("click", function(event){
		var id = $(this).attr("rel");
		$("#" + id).toggle('slow', function() {
		    // Animation complete.
		  });
		event.preventDefault();
	});

	$('#menu li').has('ul').hover(function(){
		$(this).addClass('current').children('ul').show();
		//$(this).next().show();
	}, function(){
		//$(this).next().hide();
		$(this).removeClass('current').children('ul').hide();
	});

});
