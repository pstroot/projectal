
    jQuery(function ($) {
		$('#wordpressDefault select option').each(function(){
			var originalVal = $(this).val();
			var newVal = alter_URL_for_WOZ(originalVal);
			$(this).val(newVal);				
		});
		$('#wordpressDefault #searchform').append('<input type="hidden" name="main_page" value="wordpress" />');
		
		$('#wordpressDefault a').each(function(){
			
			var originalLink = $(this).attr("href");
			var newLink = alter_URL_for_WOZ(originalLink);	
			$(this).attr("href",newLink);				
		});
		
		
		function alter_URL_for_WOZ(url){
			var url = url.replace('?author=', 'index.php?main_page=wordpress&author=');
			var url = url.replace('?cat=', 'index.php?main_page=wordpress&cat=');
			var url = url.replace('?p=', 'index.php?main_page=wordpress&p=');
			var url = url.replace('?m=', 'index.php?main_page=wordpress&m=');
			var url = url.replace('?d=', 'index.php?main_page=wordpress&d=');
			var url = url.replace('?y=', 'index.php?main_page=wordpress&y=');
			return url;
		}
    });