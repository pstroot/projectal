<script>
activateNav('nav-participate');
activateNav('nav-howitworks');
</script>
 
<div class="centerColumn" id="howItWorks">

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	$breadcrumb->_trail= array_values($breadcrumb->_trail); 
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">

    <h1><?php echo HEADING_TITLE; ?></h1>
    
    <div class="greyBubbleArrow" id="first"></div>
    <div class="greyBubble" id="first" >
        <?php echo HEADING_TAGLINE; ?>
    </div>
        
        
    <?php if (DEFINE_HOW_IT_WORKS_STATUS >= '1' and DEFINE_HOW_IT_WORKS_STATUS <= '2') { ?>
        <div id="howItWorksContent" class="content">
        <?php require($define_page); ?>
        </div>
    <?php } ?>

</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->