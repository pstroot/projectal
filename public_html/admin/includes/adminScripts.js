function setClassName(objId, className) {
	document.getElementById(objId).className = className;
}



function showError(divID){
	document.getElementById(divID).className = "error";
}

function changeActiveState(checkbox){
	var activeState
	if(checkbox.checked){
		activeState=1
	} else {
		activeState=0
	}
	window.location='?setActiveState='+activeState+'&id='+checkbox.value+'&ref='+document.URL
}

// THIS IS IN "includes/main_navigation.php"
function activateNav(navID){
	if(navID != "" && document.getElementById(navID) != null){
		document.getElementById(navID).className = "active"
	}
}



<!-- for image uploading -->
function checkImageRadio(r,divName){
	if (r.value == "change"){
		document.getElementById(divName).style.display = 'inline'
	} else {
		document.getElementById(divName).style.display = 'none'
	}
}

function toggleExpert(divName,checkbox){
	if(checkbox.checked){
		document.getElementById(divName + "_expert").style.display = 'block'
		document.getElementById(divName + "_notexpert").style.display = 'none'
	}else{
		document.getElementById(divName + "_expert").style.display = 'none'
		document.getElementById(divName + "_notexpert").style.display = 'block'
	}
}






/* Lytebox Functions */

	function startLytebox(href, title, rev) {
		var el   = document.getElementById('lytebox_misc');
		el.href  = href;
		el.title = title;
		el.rel	 = "lyteframe";
		el.rev = rev
		//alert (href)
		if(typeof myLytebox=="undefined") initLytebox();	
		myLytebox.start(el,false,true);
	}
	
	function updateData(divID,value){
		document.getElementById(divID).innerHTML = value	
	}
	function appendData(divID,value){
		document.getElementById(divID).innerHTML = document.getElementById(divID).innerHTML + value	
	}
	function delayLyteboxClose(time){
		setTimeout("lbIframeClose()",1250);
	}
	function lbIframeClose() {
		parent.document.getElementById("lbMain").style.display = "none";
		parent.document.getElementById("lbOverlay").style.display = "none";
	}
	
	
	
	
	/* creating and deleting table rows */
	function createRow(tableID){
		var tblBody = document.getElementById(tableID).tBodies[0];
		var newRow = tblBody.insertRow(-1);
		return newRow
	}
	function insertCell(row,value){
		 var newCell = row.insertCell(0);
		 newCell.innerHTML = value;
		 return newCell;
	}
	function deleteTableRow(rowID){
	 	var table = document.getElementById("theDataTable");
		
		var rowCount = table.rows.length;
 		for(var i=0; i<rowCount; i++) {
        	var row = table.rows[i];
            if(row == document.getElementById(rowID)) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
            }
 		}
	}