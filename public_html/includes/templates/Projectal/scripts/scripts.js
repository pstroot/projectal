var activeCategoryID = '';

function setClassName(objId, className) {
	document.getElementById(objId).className = className;
}


function setValue(name,value){
	if(name == "isLoggedIn" ){	
		if(value == "true"){
			document.getElementById('welcomeLinks').style.display = "block"
		} else {
			document.getElementById('joinOrLogin').style.display = "block"
		}
	} else if(name == "total"){
		setCartTotal(value)		
	} else if(name == "totalItems"){
		setTotalItems(value)
	} else if(name == "securityToken"){
		document.forms["login-form"].securityToken.value = value
	} else if(name == "customer_first_name"){
		document.getElementById("customer_first_name").innerHTML = value
	}
		
}



var activeSizeID = ""
function changeSize(size){
	if(activeSizeID != ""){
		setClassName("size_" + activeSizeID, "")
	}
	activeSizeID = size
	setClassName("size_" + activeSizeID, "active")
	
	document.getElementById("attrib-2-"+activeSizeID).checked = true
}


function showError(divID){
	document.getElementById(divID).className = "error";
}	
	
var activeCategory
function highlightCategory(id){
	var theClassName
	if(activeCategory != undefined){
		theClassName = document.getElementById(activeCategory).className
		var newClassName = theClassName.replace(" active","")
		setClassName(activeCategory, newClassName)
	}
	activeCategory = id
	
	try{
		theClassName = document.getElementById(id).className
		setClassName(activeCategory, theClassName + " active")
	} catch(e) {
		//alert("Error");	
	}
	
}
	
function doZoom(label,img){
	// GB_showCenter(caption, url, /* optional */ height, width, callback_fn)
    GB_showCenter(label, '../../'+img,500,500)
}



function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}

function updateAttribute(selectItem){
	var attribute_option_id = selectItem.name.replace(/^id\[|\]$/ig, "");
	if(attribute_option_id == 1){ // this is the ID for "color" option
		var attribute_value = selectItem.value
		updateShirtBackground(attribute_value)
	}
}

function updateShirtBackground(attribute_id){
	if(attribute_id != ""){
		document.getElementById('loadingAnimation').style.display = ""
		
		var shirtImagePath = eval("shirt_"+attribute_id)
		var thisDiv = document.getElementById('tshirtImage')
		//thisDiv.src = shirtImagePath
		thisDiv.className = "HalfOpacity"
		var img = new Image();
		img.src = shirtImagePath;
		img.onload = function() {
			thisDiv.className = ""
			thisDiv.src = shirtImagePath
			document.getElementById('loadingAnimation').style.display = "none"
		}
	}

}

var shirtBackgroundSet = false

// This is called from "includes/modules/attributes.php"
function setDefaultAttribute(optionID,selectedValue){
	//setClassName("size_" + selectedValue, "active")
	if(optionID == 2){ // 2 is the ID for the Size options
		changeSize(selectedValue)
	}
	
	if(optionID == 1){ // 1 is the ID for the Color options
		//shirtBackgroundSet = true
		//updateShirtBackground(selectedValue)
	}
}


// Display the "SizingChart" link
// This is called from "includes/modules/attributes.php"
function showSizingChart(){ 
	document.getElementById('sizingChartLink').style.display = "block";
}

// this is called on "<Body onLoad()>"
function pageLoadedFinalScripts(){
	// if the product does NOT have a default color set, then set it to white.
	if(shirtBackgroundSet == false){
		//updateShirtBackground(activeCategoryID,30) // 30 is the ID of the WHITE color option
	}
}



// for the shop main page, when the user mouses over the guys shirt or the girls shirt
function mouseOverGuys(){
	document.getElementById('shopGuysShirt').className = "active"
	document.getElementById('shopGuysButton').className = "active last"
}
function mouseOutGuys(){
	document.getElementById('shopGuysShirt').className = ""
	document.getElementById('shopGuysButton').className = "last"
}
function mouseOverLadies(){
	document.getElementById('shopLadiesShirt').className = "active"
	document.getElementById('shopLadiesButton').className = "active first"
}
function mouseOutLadies(){
	document.getElementById('shopLadiesShirt').className = ""
	document.getElementById('shopLadiesButton').className = "first"
}




var activePaymentDetails = '';
function changePaymentType(radioBtn,id){
	if(activePaymentDetails != ''){
		activePaymentDetails.style.display = "none"	
	}
	try{
		activePaymentDetails = document.getElementById("details_"+radioBtn.value)
		activePaymentDetails.style.display = "block"	
	}catch(e){
		activePaymentDetails = ""
	}
}
function getSelectedRadio(form,radio){
	var radioGroup = eval("document."+form+"."+radio)
	for (ii=radioGroup.length-1; ii > -1; ii--) {
		if (radioGroup[ii].checked) {
			changePaymentType(radioGroup[ii],radioGroup[ii].value)
		}
	}
		
}



function rollOverContactLink(){
	animateHorseheadIn()
}

function rollOffContactLink(){
	animateHorseheadOut()
}




           
                
/* MAIN NAVIGATION */		
		
function activateNav(navID){
	if(navID != "" && document.getElementById(navID) != null){
		document.getElementById(navID).className = "active"
	}
}



function toggleLogin(){
	var thisDiv = document.getElementById("loginForm-short")
	var currentStyle = thisDiv.style.display
	if(currentStyle == "" || currentStyle == "none"){
		thisDiv.style.display = "block"
	}else{
		thisDiv.style.display = "none"
	}
}


/* SHOPPING CART DATA IN HEADER */
function showCheckout(){
	document.getElementById("items").style.display = "none";
	document.getElementById("checkout").style.display = "block";
}
function hideCheckout(){
	document.getElementById("items").style.display = "block";
	document.getElementById("checkout").style.display = "none";
}

function businessSearch(pulldown){
	var index = pulldown.selectedIndex
	var selectedValue = pulldown.options[index].value
	alert("Do a search for: " + selectedValue)
}



