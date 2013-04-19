
<?php 
include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CATEGORIES_TABS));
?>

<div class="centerColumn" id="productInfo">


<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	//unset($breadcrumb->_trail[1]); // remove the first level category, which is the city
	$breadcrumb->_trail= array_values($breadcrumb->_trail); // reassigns the array indicies so there isn't a hole.
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->
    



<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">
    
	<?php if ($messageStack->size('contact') > 0) echo $messageStack->output('contact'); ?>

    <div class="leftcolumn" >  
    
        
        <!--bof Main Product Image -->
        <?php
        if (zen_not_null($products_image)) {
            /* display the main product image */
            require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php');
        }
        ?>
    
                
        <div class="itemsBelowProductImage"> 
            
            <div class="crossSellList">
                <!-- bof: Cross-Sell information -->
                <?php
                // THIS BLOCK ADDED BY PAUL STROOT on Feb 22, 2011 -->
                // THIS CODE WOULD BE ADDED INTO YOUR TPL_PRODUCT_INFO_DISPLAY.PHP WHEREVER YOU WANT TO DISPLAY THE CROSS_SELL BOX:
                  require($template->get_template_dir('tpl_modules_xsell_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_xsell_products.php');
                ?>
                <!-- eof: Cross-Sell information -->
            </div>
            
            <div id="previous_next">        	
                <!--bof Prev/Next top position -->
                <?php 
                if (PRODUCT_INFO_PREVIOUS_NEXT == 1 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { 
                    require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); 
                } 
                ?>
                <!--eof Prev/Next top position-->
            </div>
        
            <div class="socialNetworkingLinks">
            
           		<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=391146680946945";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
                <span class="fb-like" data-href="<?php echo $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']; ?> " data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial"></span>
                <div id="fb-root"></div>
                
                
                <!--<a href="#" style="margin:0px 10px;"><img src="<?php echo DIR_WS_TEMPLATE; ?>images/icon_tweet.png" alt="Tweet"></a>-->
                <!--<a href="#" style="margin:0px 10px;"><img src="<?php echo DIR_WS_TEMPLATE; ?>images/icon_like.png" alt="Like"></a>-->
            </div>
        </div>
    
        
        
        <!--bof Additional Product Images -->
        <?php
        /**
         * display the products additional images
         */
          require($template->get_template_dir('/tpl_modules_additional_images.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_additional_images.php'); ?>
        <!--eof Additional Product Images -->
        
        
        
        
        <!--eof Main Product Image-->
    
    
    </div> <!-- END <div class="leftcolumn"> -->
    
    
    <!-- BEGIN <div class="rightcolumn"> -->
    <div class="rightcolumn">
        
        <div class="centerColumn" id="productGeneral">    
            <?php if (CATEGORIES_TABS_STATUS == '1' && sizeof($links_list) >= 1) { ?>
            <div id="navCatTabsWrapper">
                <div id="navCatTabs">
                    <ul>
                    <li>Links List</li>
                    <?php for ($i=0, $n=sizeof($links_list); $i<$n; $i++) { ?>
                    <li><?php echo $links_list[$i];?></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
    
        
        <div id="categoryInfo">
            
        
        
        
        
        <!--bof Form start-->
        <?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product', $request_type), 'post', 'enctype="multipart/form-data"') . "\n"; ?>
        <!--eof Form start-->
        
        
        <div id="productHeader">
            <!--bof Product Name-->
            <h1 id="productName" class="productGeneral">
            <?php 
            if(trim($business_name) == ""){
                echo $products_name; 
            } else {
                echo $business_name; 
            }
            ?>
            </h1>
            <!--eof Product Name-->
            
            <!--bof Product Price block -->
            <h2 id="productPrices" class="productGeneral">
            <?php
            // base price
              if ($show_onetime_charges_description == 'true') {
                $one_time = '<span >' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
              } else {
                $one_time = '';
              }
              echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and $flag_show_product_info_starting_at == 1) ? TEXT_BASE_PRICE : '') . zen_get_products_display_price((int)$_GET['products_id']);
            ?>
            
            <?php 
            if ($flag_show_product_info_model == 1 && $products_model != ""){
               echo '<span id="productNameDescription"> - ' . $products_model . "</span>\n";
            }
            ?>
            </h2>
            <!--eof Product Price block -->
            
            <?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>
        </div>
        
        
        
        <!-- /////////////////////////////////////////////////////////////////////////// -->
        <!-- /////////// ATTRIBUTES: i.e. SIZE SELECTION and COLOR SELECTION /////////// -->
        <!-- /////////////////////////////////////////////////////////////////////////// -->
       <SCRIPT>
            <?
            // Create javascript setting the different images for each of the colors available
            $tshirt_image_query = $db->Execute("SELECT options_values_id, attributes_image FROM zen_products_attributes WHERE options_id = 1 AND products_id=" . $theProductID);
            while (!$tshirt_image_query->EOF) {				
            
                $id = $tshirt_image_query->fields['options_values_id'];			
                $products_image = $tshirt_image_query->fields['attributes_image'];
    
                $imgSrc = calculateOptimizedImage($products_image,"MEDIUM");
                if(is_file($imgSrc)){
                    print "\tvar shirt_".$id ." = \"" . $imgSrc ."\";\n";
                } else {
                    print "\tvar shirt_".$id ." = \"".DIR_WS_IMAGES . PRODUCTS_IMAGE_NO_IMAGE."\";\n";
                }
                $tshirt_image_query->MoveNext();
            }	
            ?>
        </SCRIPT>
        <div id="attributes">
            <?php 		
                include(DIR_WS_TEMPLATE . "common/size_chart.php");
            ?>
            <!--bof Attributes Module -->
            <?php
              if ($pr_attr->fields['total'] > 0) {
            ?>
            <?php
                /* display the product atributes */
                require($template->get_template_dir('/tpl_modules_attributes.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_attributes.php'); ?>
            <?php
              }
            ?>
            <!--eof Attributes Module -->
        </div>
    
        
        
        
        <div id="productInfo">
        
             <!--bof Product description -->
            <?php if ($products_description != '') { ?>
            <div id="productDescription" class="productGeneral biggerText"><?php echo stripslashes($products_description); ?></div>
            <br class="clearBoth" />
            <?php } ?>
            <!--eof Product description -->
        
            <!--bof free ship icon  -->
            <?php if(zen_get_product_is_always_free_shipping($products_id_current) && $flag_show_product_info_free_shipping) { ?>
            <div id="freeShippingIcon"><?php echo TEXT_PRODUCT_FREE_SHIPPING_ICON; ?></div>
            <?php } ?>
            <!--eof free ship icon  -->
            
            
        
            
            
        <!--bof Add to Cart Box -->
        <?php
        if (CUSTOMERS_APPROVAL == 3 and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '') {
          // do nothing
        } else {
            $display_qty = (($flag_show_product_info_in_cart_qty == 1 and $_SESSION['cart']->in_cart($_GET['products_id'])) ? '<p>' . PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '</p>' : '');
                    if ($products_qty_box_status == 0 or $products_quantity_order_max== 1) {
                      // hide the quantity box and default to 1
                      $the_button = '<input type="hidden" name="cart_quantity" value="1" />';
                    } else {
                      // show the quantity box
                      
                      // these two lines below edited by Paul Stroot to hide the add to cart number input field
                       $the_button = '<input type="hidden" name="cart_quantity" value="' . zen_get_buy_now_qty($_GET['products_id']) . '" />';
                       $the_button .= zen_get_products_quantity_min_units_display((int)$_GET['products_id']);
        
                       //$the_button = PRODUCTS_ORDER_QTY_TEXT . '<input type="text" name="cart_quantity" value="' . zen_get_buy_now_qty($_GET['products_id']) . '" maxlength="6" size="4" /><br />';
                       //$the_button .= zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . '<br />';
        
                    }
            $the_button .= zen_draw_hidden_field('products_id', (int)$_GET['products_id']);
            $the_button .= projectal_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
            $display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);
            if ($display_qty != '' or $display_button != '') { ?>
            <div id="cartAdd">
            <?php
              echo $display_button;
              ?>
            </div>
            <?php 
          } // display qty and button 
        } // CUSTOMERS_APPROVAL == 3 
        ?>
        <!--eof Add to Cart Box-->
        
        
        
        
        <!--bof Product details list  -->
        <?php  echo $display_qty; ?>
        <?php if ( (($flag_show_product_info_model == 1 and $products_model != '') or ($flag_show_product_info_weight == 1 and $products_weight !=0) or ($flag_show_product_info_quantity == 1) or ($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name))) ) { ?>
            <ul id="productDetailsList" class="floatingBox back">
              <?php // echo (($flag_show_product_info_model == 1 and $products_model !='') ? '<li>' . TEXT_PRODUCT_MODEL . $products_model . '</li>' : '') . "\n"; ?>
              <?php echo (($flag_show_product_info_weight == 1 and $products_weight !=0) ? '<li>' . TEXT_PRODUCT_WEIGHT .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT . '</li>'  : '') . "\n"; ?>
              <?php echo (($flag_show_product_info_quantity == 1) ? '<li>' . $products_quantity . TEXT_PRODUCT_QUANTITY . '</li>'  : '') . "\n"; ?>
              <?php echo (($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) ? '<li>' . TEXT_PRODUCT_MANUFACTURER . $manufacturers_name . '</li>' : '') . "\n"; ?>
            </ul>
            <br class="clearBoth" />
            <?php
        }
        ?>
        <!--eof Product details list -->
        
            <div id="addToCart-after"></div>
        
        
        </div>
                
        <? if(is_file( $business_image) || trim($business_name) != "" || trim($business_website) != "" || trim($business_description != "")){ ?>
        <div id="businessInfo" >
            <? if(is_file( $business_image)){ ?>
                <img src="<?= $business_image;?>" alt="<?= addslashes($business_name); ?>">
            <? } ?>
                        
            <?= stripslashes($business_description) ;?>
           
            <? if(trim($business_website) != ""){ ?>
                <div id="readMoreLink">Visit Website at <a href="<?=$business_website?>" target="_blank"><?=$business_website?></a>.</div>
            <? } ?>
        </div>
        <? } ?>
            
            
        <!--bof Form close-->
        </form>
        <!--bof Form close-->
        
        
            
        <div class="subscribeForm">
        Notify me when Projectal has new tees. <BR />
        <!--<a href="#">Click here</a> to learn more about product alerts.<BR />-->
        <form name="subscribeForm" action="" method="POST">
            <input type="hidden" name="action" value="addToNewsletter">
            <input type="text" name="email" value="">
            <input type="hidden" name="redirect" value="<?= urlencode(curPageURL()) ;?>">
            <input type="submit" name="submit" id="submit" value="Subscribe">
        </form>
        </div>
           
            
            
            
        <!--bof Quantity Discounts table -->
        <?php
          if ($products_discount_type != 0) { ?>
        <?php
        /**
         * display the products quantity discount
         */
         require($template->get_template_dir('/tpl_modules_products_quantity_discounts.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_products_quantity_discounts.php'); ?>
        <?php
          }
        ?>
        <!--eof Quantity Discounts table -->
        
    
        <!--bof Prev/Next bottom position -->
        <?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
        <?php
        /**
         * display the product previous/next helper
         */
         require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
        <?php } ?>
        <!--eof Prev/Next bottom position -->
        
        <!--bof Tell a Friend button -->
        <?php
          if ($flag_show_product_info_tell_a_friend == 1) { ?>
        <div id="productTellFriendLink" class="buttonRow forward"><?php echo ($flag_show_product_info_tell_a_friend == 1 ? '<a href="' . zen_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $_GET['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_TELLAFRIEND, BUTTON_TELLAFRIEND_ALT) . '</a>' : ''); ?></div>
        <?php
          }
        ?>
        <!--eof Tell a Friend button -->
        
     

        <!--bof Reviews button and count-->
        <?php
          if ($flag_show_product_info_reviews == 1) {
            // if more than 0 reviews, then show reviews button; otherwise, show the "write review" button
            if ($reviews->fields['count'] > 0 ) { ?>
        <div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>'; ?></div>
        <br class="clearBoth" />
        <p class="reviewCount"><?php echo ($flag_show_product_info_reviews_count == 1 ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : ''); ?></p>
        <?php } else { ?>
        <div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?></div>
        <br class="clearBoth" />
        <?php
          }
        }
        ?>
        <!--eof Reviews button and count -->
        
        
        <!--bof Product date added/available-->
        <?php
          if ($products_date_available > date('Y-m-d H:i:s')) {
            if ($flag_show_product_info_date_available == 1) {
        ?>
          <p id="productDateAvailable" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
        <?php
            }
          } else {
            if ($flag_show_product_info_date_added == 1) {
        ?>
              <p id="productDateAdded" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
        <?php
            } // $flag_show_product_info_date_added
          }
        ?>
        <!--eof Product date added/available -->
        
        <!--bof Product URL -->
        <?php
          if (zen_not_null($products_url)) {
            if ($flag_show_product_info_url == 1) {
        ?>
            <p id="productInfoLink" class="productGeneral centeredContent"><?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?></p>
        <?php
            } // $flag_show_product_info_url
          }
        ?>
        <!--eof Product URL -->
        
        <!--bof also purchased products module-->
        <?php // require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
        <!--eof also purchased products module-->
        
        
        </div>
    
    </div> <!-- END <div class="rightcolumn"> -->



</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->
