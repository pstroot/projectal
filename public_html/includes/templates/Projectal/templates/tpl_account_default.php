<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account.<br />
 * Displays previous orders and options to change various Customer Account settings
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_default.php 4086 2006-08-07 02:06:18Z ajeh $
 */
?>

<div class="centerColumn" id="accountDefault">

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
    
    <h1 id="accountDefaultHeading"><?php echo HEADING_TITLE; ?></h1>
    <?php if ($messageStack->size('account') > 0) echo $messageStack->output('account'); ?>
    
    <?php
        if (zen_count_customer_orders() > 0) {
      ?>
    <p class="forward"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . OVERVIEW_SHOW_ALL_ORDERS . '</a>'; ?></p>
   
   <br class="clearBoth" />
    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="productTable">
    <caption><h2><?php echo OVERVIEW_PREVIOUS_ORDERS; ?></h2></caption>
        <tr class="tableHeading">
        <th scope="col" class=""><?php echo TABLE_HEADING_DATE; ?></th>
        <th scope="col" class=""><?php echo TABLE_HEADING_ORDER_NUMBER; ?></th>
        <th scope="col" class="productDetail"><?php echo TABLE_HEADING_SHIPPED_TO; ?></th>
        <th scope="col" class="productDetail"><?php echo TABLE_HEADING_STATUS; ?></th>
        <th scope="col" class="productTotal <?php echo $product['rowClass']; ?>"><?php echo TABLE_HEADING_TOTAL; ?></th>
        <th scope="col" class="productButtons"><?php echo TABLE_HEADING_VIEW; ?></th>
      </tr>
    <?php
      $i = 0;
      foreach($ordersArray as $orders) {
          $rowClass = (($i / 2) == floor($i / 2)) ? "rowEven" : "rowOdd";
          $i++;
    ?>
      <tr>
        <td  class=" <?php echo $rowClass; ?>" width="70px"><?php echo zen_date_short($orders['date_purchased']); ?></td>
        <td  class=" <?php echo $rowClass; ?>" width="30px"><?php echo TEXT_NUMBER_SYMBOL . $orders['orders_id']; ?></td>
        <td  class="productDetail <?php echo $rowClass; ?>"><div id="title"><?php echo zen_output_string_protected($orders['order_name']) . '</div>' . $orders['order_country']; ?></address></td>
        <td  class="productDetail <?php echo $rowClass; ?>" width="70px"><?php echo $orders['orders_status_name']; ?></td>
        <td  class="productTotal <?php echo $rowClass; ?>" width="70px" align="right"><?php echo $orders['order_total']; ?></td>
        <td  class="productButtons <?php echo $rowClass; ?>" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '"> ' . zen_image_button(BUTTON_IMAGE_VIEW_SMALL, BUTTON_VIEW_SMALL_ALT) . '</a>'; ?></td>
      </tr>
    
    <?php
      }
    ?>
    </table>
    <?php
      }
    ?>
    
    
    <br class="clearBoth" />
    
    
    <div id="accountLinksWrapper" class="back">
    <h2><?php echo MY_ACCOUNT_TITLE; ?></h2>
    <ul id="myAccountGen" class="list">
    <li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></li>
    <li><?php echo ' <a href="' . zen_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></li>
    <?
    if(!isset($_SESSION["COWOA"]) || $_SESSION["COWOA"] != 1){
    echo '<li><a href="' . zen_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a></li>';
    } ?>
    </ul>
    
    <?php if(!isset($_SESSION["COWOA"]) || $_SESSION["COWOA"] != 1){ ?>
        <?php if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK !='false' or CUSTOMERS_PRODUCTS_NOTIFICATION_STATUS !='0') { ?>
            <h2><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h2>
            <ul id="myAccountNotify" class="list">
            
            <?php if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK=='true') { ?>
            	<li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></li>
            <?php } //endif newsletter unsubscribe ?>
            
            <?php if (CUSTOMERS_PRODUCTS_NOTIFICATION_STATUS == '1') { ?>
            	<li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_PRODUCTS . '</a>'; ?></li>
            <?php } //endif product notification ?>
            
            </ul>
    
        <?php } // endif don't show unsubscribe or notification ?>
    <?php } // end if !isset($_SESSION["COWOA"] ?>
    
    
<br /><br /><br /><br />

    </div>
    

    <?php
    // only show when there is a GV balance
      if ($customer_has_gv_balance ) {
    ?>
    <div id="sendSpendWrapper">
    <?php require($template->get_template_dir('tpl_modules_send_or_spend.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_send_or_spend.php'); ?>
    </div>
    <?php
      }
    ?>

</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->