var WIDTH;
var HEIGHT;
var theCanvas;
var theContext;
//var rightDown = false;
//var leftDown = false;
var imageArray = new Array();
var friction = .985;
var dragging = false;
var xVelocity = 0;
var yVelocity = 0;
var oldX;
var oldY;
var animationInterval;

// Main Function To Start
function start()
{
	theCanvas = $('#easteregg_horsehead')[0];
    theContext = theCanvas.getContext('2d');

	/*
    // Mouse based interface
    $(theCanvas).bind('mousedown', dragStart);
    $(theCanvas).bind('mousemove', doDrag);
    $(theCanvas).bind('mouseup', dragEnd);
    $('body').bind('mouseup', dragEnd);
    
    // Touch screen based interface
    $(theCanvas).bind('touchstart', dragStart);
    $(theCanvas).bind('touchmove', doDrag);
    $(theCanvas).bind('touchend', dragEnd);
	*/

	WIDTH = $("#easteregg_horsehead").width();
	HEIGHT = $("#easteregg_horsehead").height();
	imageArray[0] = new MoveableImage("images/horsehead.png",0,0);
	doThrow();
}

/*
// Get Key Input
function onKeyDown(evt) 
{
	if(evt.keyCode == 39) rightDown = true;
	else if(evt.keyCode == 37) leftDown = true;
	dx = dx*-1;
}

function onKeyUp(evt) 
{
	if (evt.keyCode == 39) rightDown = false;
	else if (evt.keyCode == 37) leftDown = false;
	dx = dx*-1;
}
*/

// MoveableImage Class	
function MoveableImage(src,x,y)
{
	this.x = x;
	this.y = HEIGHT-10;
	this.imageLoaded = false
	this.theImage = new Image();
	this.theImage.src = "images/horsehead.png";
	this.theImage.onload = function() {
		this.imageLoaded = true
		this.draw
		doAnimateIn()
	}
	
	this.draw = function()
	{
		theContext.drawImage(this.theImage, this.x, this.y);
	}
	
	this.getX = function()
	{
		return x;
	}
	
	this.getY = function()
	{
		return this.y;
	}
	
	this.move = function()
	{	
		this.xVelocity = this.xVelocity * friction
		this.yVelocity = this.yVelocity * friction
		this.x += this.xVelocity ;
		this.y += this.yVelocity ;
	
		if(this.x > WIDTH || this.x < 0)
		{
			this.xVelocity = this.xVelocity*-1;
		}
		
		if(this.y > HEIGHT || this.y < 0)
		{
			this.yVelocity = this.yVelocity*-1;
		}
		
	}
	this.moveTo = function(x,y)
	{	
		this.dx = x
		this.dy = y
		this.x = this.dx;
		this.y = this.dy;
	
	}
	this.draw()
}

function doAnimateIn()
{
	animationInterval = setInterval(doMove, 10);
}
// Draw Function
function doMove()
{	
	
	var i;
	for (i=0; i<imageArray.length; i++)
	{
		var newX = imageArray[i].x
		var newY = imageArray[i].y - 5
		if(newY < 0){
			doAnimateEnd()
		} else {
			clear()
			imageArray[i].moveTo(newX,newY);
			imageArray[i].draw();
		}
	}

}

function doAnimateEnd() {
	clearInterval(animationInterval);
}


function clear() 
{
	theContext.clearRect ( 0 , 0 , WIDTH , HEIGHT );
}


// Use JQuery to wait for document load
$(document).ready(function()
{
	start();
});


