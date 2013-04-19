<script>
activateNav('nav-about');
</script>


<div class="centerColumn" id="aboutUs">

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	$breadcrumb->_trail= array_values($breadcrumb->_trail);
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">

    <h1><?php echo HEADING_TITLE; ?></h1>
    
     
        
    <?php if (DEFINE_ABOUT_US_STATUS >= '1' and DEFINE_ABOUT_US_STATUS <= '2') { ?>
        <div class="greyBubbleArrow" id="first"></div>
        <div class="greyBubble" id="first" >
         <?php require($define_page); ?>
         </div>
    <?php } ?>

	<a name="faq"></a>
	<div class="greyBubble" id="second">
    	<h3><?= HEADING_TAGLINE; ?></h3>
    </div>
    <div class="greyBubbleArrow" id="second"></div>
    

    <ol>
    <? 	
	while (!$faqs->EOF) { 
		?>
		<li>
			<div class="question"><?php echo  stripslashes($faqs->fields['question']); ?></div>
			<div class="answer"><?php   echo  stripslashes($faqs->fields['answer']); ?></div>
		</li>
		<? 
		$faqs->MoveNext();
	} 
	?>
    </ol>




</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->