<script>
activateNav('nav-info');
</script>

<div class="centerColumn" id="infoDefault">

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


<div class="centerColumn" id="info">

    <h1><?php echo HEADING_TITLE; ?></h1>
    
    <?php if (DEFINE_INFO_STATUS >= '1' and DEFINE_INFO_STATUS <= '2') { ?>
        <div id="infoContent" class="content">
        <?php require($define_page); ?>
        </div>
    <?php } ?>

</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->