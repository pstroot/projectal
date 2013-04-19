jQuery(document).ready(function($) {

	html_block = $("#html_block").css("height","250").css("width","630").htmlbox({
	    toolbars:[
		     ["cut","copy","paste","separator_dots","bold","italic","underline","strike","sub","sup","separator_dots","separator_dots",
			 "left","center","right","justify","separator_dots","ol","ul","indent","outdent","separator_dots","link","unlink","image", "code"],
			 ["formats","fontsize","fontfamily","separator","fontcolor"
			]
		],
		icons:"silk",
		skin:"default"
	});	

});