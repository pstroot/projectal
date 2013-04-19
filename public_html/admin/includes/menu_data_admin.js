//http://www.milonic.com/beginner.php

fixMozillaZIndex=true; //Fixes Z-Index problem  with Mozilla browsers but causes odd scrolling problem, toggle to see if it helps
_menuCloseDelay=500;
_menuOpenDelay=400;
_subOffsetTop=0;
_subOffsetLeft=0;




with(menuStyle=new mm_style()){
	styleid=1;
	horizontalMenuDelay = false;
	
	borderwidth=0;
	bordercolor="#cc0000";
	borderstyle="solid";
	
	fontfamily="Verdana, Arial, Helvetica, sans-serif";
	fontsize="11px";
	fontstyle="normal";
	fontweight="normal";
	
	headerbgcolor="#0000FF";
	headercolor="#000000";
	offbgcolor=""; // background color
	offcolor="#091930"; // text color
	onbgcolor=""; // rollover background color
	oncolor="#998248"; // rollover text color
	outfilter="Fade(duration=0.3)";
	overfilter="Fade(duration=0.2);Alpha(opacity=90);";
	overimage="whitedots.gif";
	padding="0px 7px 0px 7px";
	pagebgcolor=""; // color of the active tab
	pagecolor="#998248"; // color of the active tab text
	separatorcolor="#091930";
	separatorsize=1;
	imagepadding=0;
	subimagepadding=2;
}

with(submenuStyle=new mm_style()){
	bordercolor="#FFFFFF";
	borderstyle="solid";
	borderwidth=0;
	rawcss="border-top:2px solid #ffffff;padding:5px;width:auto;";
	fontfamily="Verdana, Arial, Helvetica, sans-serif";
	fontsize="11px";
	fontstyle="normal";
	
	headerbgcolor="#c7b995";
	headercolor="#FFFFFF";
	
	offbgcolor="#FFFFFF"; // color of background
	offcolor="#998248"; // color of background text
	
	onbgcolor="";
	oncolor="#091930";
	
	outfilter="Fade(duration=0.3)";
	overfilter="Inset(duration=0.2);Alpha(opacity=94);";
	padding="0px 4px 0px 4px";
	pagebgcolor="";
	pagecolor="#998248";
	
	separatorcolor="#f1e4c2";
	separatorsize=1;
	subimage="images/arrow.gif";
	onsubimage="images/arrow_o.gif";
	subimagepadding=0;
}



