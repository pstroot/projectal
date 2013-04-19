<script type="text/javascript">
jQuery(function($) {
	var tweets = {
		el: '.twitterTicker .twitter-stream',
		items: new Array(),
		count: 0,
		total: -1,
		delay: 6000,
		animate: true
	};
	$(tweets.el+' p').each(function(i) {
		tweets.items[i] = $(this).html();
		tweets.total++;
	}).hide();
	runTweeter();
	function runTweeter() {
		if(tweets.animate == true) {
			if($(tweets.el+' p').length > 0) {
				$(tweets.el).children('p').fadeOut(500, function() {
					$(this).parent(0).empty().append('<p style="display: none;">'+tweets.items[tweets.count]+'</p>').children('p').fadeIn(500);
				});
			} else {
				$(tweets.el).empty().append('<p style="display: none;">'+tweets.items[tweets.count]+'</p>').children('p').fadeIn(750);
			}
		} else {
			$(tweets.el).empty().append('<p>'+tweets.items[tweets.count]+'</p>');
		}
		setTimeout(function() {
			if(tweets.count == tweets.total) {
				tweets.count = 0;
			} else {
				tweets.count++;
			}
			runTweeter();
		}, tweets.delay);
	}
});
</script>
<nav id="cartInfo">
<div class="twitterTicker">

<?php
$doc = new DOMDocument();

# load the RSS -- replace 'username' with your own twitter username
if($doc->load('http://twitter.com/statuses/user_timeline/projectal.rss')) {
	echo "<div class='twitter-stream'>";

	# number of <li> elements to display.  20 is the maximum
	$max_tweets = 10;    

	$i = 1;
	foreach ($doc->getElementsByTagName('item') as $node) {
		# fetch the title from the RSS feed.
		# Note: 'pubDate' and 'link' are also useful (I use them in the sidebar of this blog)
		$tweet = $node->getElementsByTagName('title')->item(0)->nodeValue;

		# the title of each tweet starts with "username: " which I want to remove
		$tweet = substr($tweet, stripos($tweet, ':') + 1);   
		
		$tweet = htmlentities($tweet);

		# OPTIONAL: turn URLs into links
		$tweet = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $tweet);

		 # OPTIONAL: turn @replies into links
		$tweet = preg_replace("/@([0-9a-zA-Z]+)/", "<a href=\"http://twitter.com/$1\">@$1</a>",$tweet);

	

		echo "<p>". $tweet  . "</p>";
		if($i++ >= $max_tweets) break;
	}
	echo "</div>";
} 
?>
</div>



<!-- 
<ul> 
--> 

    <!-- ///////////////// SELECT A BUSINESS PULLDOWN ///////////////// -->
		<!--
        <li id="searchForm">
			<form name="searchForm" method="GET" action="search.php">
            <select name="business" onChange="businessSearch(this)">
                <option value="">Search by Business</option>
                <option value="location">By Location</option>
                <option value="theme">By Minnesota Theme</option>
            </select>
            </form>
		</li>
        -->
        
        
       <?php if ($_SESSION["cart"]->count_contents() > 0) {  ?>
	   <!-- ///////////////// NBR OF ITEMS AND CHECKOUT ///////////////// -->
       <!--
       <li id="checkoutLink">
			<ul>
                <li id="items" onMouseOver="showCheckout()">
                    <div class="label">
                        <a href="shoppingcart.php"><img src="<?php echo DIR_WS_TEMPLATE; ?>images/cart_icon.gif" alt="View Cart" /></a>
                        Items: 
                    </div>
                    <div class="value"><span id="total_cart_items"><?=$_SESSION["cart"]->count_contents(); ?></span></div> 
                </li>
                    
                <li id="checkout" onMouseOut="hideCheckout()" style="display:none;">
                    <div class="label"><a href="index.php?main_page=shopping_cart"><img src="<?php echo DIR_WS_TEMPLATE?>images/checkout_rollover.gif" alt="Checkout" width="106" height="25" /></a></div>
                </li>
			</ul>
		</li>
       -->     
            
		<!-- ///////////////// SUBTOTAL ///////////////// -->
        <!--
		<li id="subtotal">
			<div class="label">Subtotal: </div>
			<div class="value"><span id="show_cart_total"><?= $currencies->display_price($_SESSION["cart"]->total); ?></span></div>
		</li>
        -->
		<? } ?>

</ul>
<!-- 
</nav> 
-->