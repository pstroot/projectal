<!-- BEGIN NAVIGATION -->

<div id="image_preloader" style="display:none;">
<img src="<?php echo DIR_WS_TEMPLATE?>images/subnav_bkg_bottom.png" />
<img src="<?php echo DIR_WS_TEMPLATE?>images/subnav_bkg_color.png" />
<img src="<?php echo DIR_WS_TEMPLATE?>images/subnav_bkg_middle.png" />
<img src="<?php echo DIR_WS_TEMPLATE?>images/subnav_bkg_top.png" />
<img src="<?php echo DIR_WS_TEMPLATE?>images/subnav_pointer.gif" />
</div>

<nav id="topNav">
  <ul>
	<li id="nav-shop">
		<div class="subnav">
        	<div class="subnav_before"></div>
            <ul>
                <?
				  $query = "SELECT DISTINCT d.categories_name, c.categories_id
										FROM zen_categories c 
										LEFT JOIN zen_categories_description d ON c.categories_id = d.categories_id
										WHERE c.parent_id = 65
										ORDER BY c.sort_order";
				  $results = $db->Execute($query);
				  while (!$results->EOF) {	
					  $categories_id = $results->fields['categories_id'];
					  $categories_name = $results->fields['categories_name'];
					  print "\t";
					  print '<li id="shop_'.$categories_id.'"><a href="index.php?main_page=index&amp;cPath=' . $cityID . '_' . $categories_id . '">' . $categories_name . '</a></li>';
					  print "\n";
					  $results->MoveNext();       
				  }
				?>

            </ul>
            <div class="subnav_after"></div>
        </div>
        <a href="index.php?main_page=store">Shop</a>            
	</li>
    
	<li id="nav-participate">               
		<div class="subnav">
        	<div class="subnav_before"></div>
            <ul>
            	<li id="nav-membership"><a href="index.php?main_page=membership">Membership</a></li>
                <li id="nav-howitworks"><a href="index.php?main_page=how_it_works">How It Works</a></li>
            </ul>  
            <div class="subnav_after"></div>
        </div>
        <a href="index.php?main_page=participate">Participate</a> 
	</li>
    
	<li id="nav-about"><a href="index.php?main_page=about_us">About Us</a></li>
    
	<li id="nav-blog"><a href="index.php?main_page=wordpress">Blog</a></li>
    
	<li id="nav-info">
		<div class="subnav">
			<div class="subnav_before"></div>
            <ul>
            	<li id="nav-customerservice"><a href="index.php?main_page=customer_service">Customer Service</a></li>
                <li id="nav-contact"><a href="index.php?main_page=contact_us">Contact&nbsp;Us</a></li>
            </ul>  
            <div class="subnav_after"></div>
        </div>
        <a href="index.php?main_page=customer_service">info</a>
	</li>
       <li id="freeShippingMessage">Free Shipping for 2 or more shirts</li>
  </ul>
</nav>
<!-- END NAVIGATION -->    
     