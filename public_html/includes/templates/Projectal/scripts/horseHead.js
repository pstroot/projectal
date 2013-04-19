var HORSEHEAD_WIDTH;
var HORSEHEAD_HEIGHT;
var theCanvas;
var theContext;
//var rightDown = false;
//var leftDown = false;
var mImage; // moveableImage Object as defined below

if (typeof pathToRoot === 'undefined') {
	pathToRoot = ''
}




var horseHeadDelayInterval
var horseAttemptCounter = 0
// Use JQuery to wait for document load
$(document).ready(function(){
	horseHeadDelayInterval = setTimeout(horseHead_init, 500); // IE requires a delay for some reason

});


// Main Function To Start
function horseHead_init(){
	clearInterval(horseHeadDelayInterval)
	try{
		theCanvas = $('#easteregg_horsehead')[0];
		HORSEHEAD_WIDTH = $("#easteregg_horsehead").width();
		HORSEHEAD_HEIGHT = $("#easteregg_horsehead").height();
		theContext = theCanvas.getContext('2d');
		mImage = new HorseHeadImage("images/horsehead.png",0,0);
	}catch(err){
		txt="There was an error on this page.\n\n";
		txt+="Error description: " + err.description + "\n\n";
		txt+="Click OK to continue.\n\n";
		// alert(txt);
		horseAttemptCounter++
		if(horseAttemptCounter<10){
			horseHeadDelayInterval = setTimeout(horseHead_init, 500); // IE requires a delay for some reason
		}
	}
	
}

// MoveableImage Class	
function HorseHeadImage(src,x,y){
	this.animationInterval;
	this.finalX;
	this.finalY;
	
	this.x = x;
	this.y =  HORSEHEAD_HEIGHT;
	this.imageLoaded = false
	this.theImage = new Image();
	this.theImage.src = "includes/templates/Projectal/images/horsehead.png";
	this.theImage.onload = function() {
		this.imageLoaded = true
		mImage.draw()
	}
	
	this.draw = function(){
		theContext.drawImage(this.theImage, this.x, this.y);
	}
	
	this.getX = function(){
		return x;
	}
	
	this.getY = function(){
		return this.y;
	}
	
	this.move = function(){	
		var Xdiff = mImage.finalX - mImage.x
		var Ydiff = mImage.finalY - mImage.y
		
		mImage.x += Xdiff/6
		mImage.y += Ydiff/6

		mImage.clear();
		mImage.draw();
		
		if((Xdiff < 1 && Xdiff > -1) && (Ydiff < 1 && Ydiff > -1)) {
			mImage.doAnimateEnd();
		}
		
	}
	this.moveTo = function(x,y)
	{	
		this.dx = x
		this.dy = y
		this.x = this.dx;
		this.y = this.dy;
	
	}
	
	this.animateTo = function(x,y){
		this.finalX = x;
		this.finalY = y;
		
		this.animationInterval = setInterval(this.move, 40);
	}
	
	
	this.doAnimateEnd = function(){
		clearInterval(this.animationInterval);
	}
	
	
	this.clear = function(){
		theContext.clearRect ( 0 , 0 , HORSEHEAD_WIDTH , HORSEHEAD_HEIGHT );
	}
	this.draw()
	
	
}

function animateHorseheadIn(){
	mImage.doAnimateEnd();
	mImage.animateTo(0,0)
}
function animateHorseheadOut(){
	mImage.doAnimateEnd();
	mImage.animateTo(0,HORSEHEAD_HEIGHT)
}


