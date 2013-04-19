<script>
activateNav('nav-shop');
</script>

<div class="centerColumn" id="store">

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	$breadcrumb->_trail= array_values($breadcrumb->_trail);
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->


    
<div class="centerColumnContent">
<div class="centerColumnPadding">

    <?php if (DEFINE_STORE_STATUS >= '1' and DEFINE_STORE_STATUS <= '2') { ?>
        <div id="storeContent" class="content">
        <?php require($define_page); ?>
        </div>
    <?php } ?>
    
    
    
    <div id="shop_guys_or_ladies_buttons">
        <a href='index.php?main_page=index&amp;cPath=65_66' id='shopGuysButton'   class='last'  onmouseover="mouseOverGuys()"   onmouseout="mouseOutGuys()" ></a>
        <img src="<?php echo DIR_WS_TEMPLATE; ?>images/or_white.gif" id="or">
        <a href='index.php?main_page=index&amp;cPath=65_67' id='shopLadiesButton' class='first' onmouseover="mouseOverLadies()" onmouseout="mouseOutLadies()"></a>
    </div>
        
    <div id="shop_guys_or_ladies_shirts">
        <a href='index.php?main_page=index&amp;cPath=65_66' id='shopGuysShirt' onmouseover="mouseOverGuys()" onmouseout="mouseOutGuys()">Shop Guys</a>
        <a href='index.php?main_page=index&amp;cPath=65_67' id='shopLadiesShirt' onmouseover="mouseOverLadies()" onmouseout="mouseOutLadies()">Shop Ladies</a>
    </div>
    
    <div style="clear:both;"></div>



</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->