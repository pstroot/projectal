// JavaScript Document
var selectedShippingRow = "";
function selectShipping(radio){
	if(selectedShippingRow != ""){
		document.getElementById(selectedShippingRow).className = "moduleRow";
	}
	if(radio.checked){
		rowID = ("ROW_" + radio.value)
		document.getElementById(rowID).className = "activeModuleRow";
		selectedShippingRow = rowID;
	}
}