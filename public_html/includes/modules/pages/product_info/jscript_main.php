<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: jscript_main.php 5444 2006-12-29 06:45:56Z drbyte $
//
?>

<!-- meta info for facebook "LIKE" button -->
<meta property="og:site_name" content="ProjectAl.com"/>
<meta property="og:image" content="http://www.projectal.com/<?php echo ($facebook_image); ?>" />
<meta property="og:title" content="ProjectAl: <?php echo $products_name; ?>" />
<meta property="og:description" content="Original t-shirt design for <?php echo $business_name ; ?> and other local businesses created by ProjectAl artists. Only available through ProjectAl.com" />

<script type='text/javascript' src='<?php echo DIR_WS_TEMPLATE; ?>/includes/jqzoom_ev-2.3/js/jquery.jqzoom-core.js'></script>  
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATE; ?>/includes/jqzoom_ev-2.3/css/jquery.jqzoom.css"> 


<script type="text/javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
function popupWindowPrice(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=400,screenX=150,screenY=150,top=150,left=150')
}


bannerData = <?php echo json_encode($topBannerData); ?>;
var i = -1;

$(document).ready(function() {
	$('h2#tagline').remove();
	$('.headerBar .logo').remove();
	nextBanner();
	
	function nextBanner(){
		if($('#rBanner_'+i).length > 0){
			$('#rBanner_'+i).delay(1000).fadeOut(1000);
		}
		
		i++;
		if(i >= bannerData.length) i=0
		if($('#rBanner_'+i).length == 0){
			$('.headerBar').prepend("<div id='rBanner_"+i+"' style='position:absolute;'>"+bannerData[i]["content"]+"</div>");
		}
		
		$('#rBanner_'+i).fadeIn(1000);
		
		if(bannerData.length > 1){
			setTimeout(nextBanner,bannerData[i]["delay"]*1000);
		}
	
	}
	
	
	
	 var options = {  
           zoomType: 'standard', 
            zoomWidth: 458,  
            zoomHeight: 350,  
            xOffset:33,  
            yOffset:56,  
            lens:true,   
            position:'right',  
            preloadImages: true,  
			alwaysOn:false,
			showEffect:'fadein',
			hideEffect:'fadeout'
    };  
    $('.zoomableProductImage').jqzoom(options);  
	
});
//--></script>

<script>
$(document).ready(function() {
	activateNav('nav-shop');
	<?
	if(isset($_GET["cPath"])){
		$activeCategories = explode("_",@$_REQUEST["cPath"]);
		foreach($activeCategories as $catID){
			print "activateNav('shop_".$catID."');\n"; 
		}
	}
	
	// if there is a product ID passed in from the querystring, find out what category it's in, and activate the nav via javascript
	if(isset($_GET["products_id"])){
		$theQuery = $db->Execute("SELECT master_categories_id FROM zen_products WHERE products_id = " . $theProductID);
		while (!$theQuery->EOF) {							
			print "activateNav('shop_".$theQuery->fields['master_categories_id']."');\n"; 
			$theQuery->MoveNext();
		}	
	}  
	?>
});
</script>




