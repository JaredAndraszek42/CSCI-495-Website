
/** 
 * reveals the text input field whenever the 
 * check box is toggled
 * */ 
function reveal(fieldId) {
	let thisField = document.getElementById(fieldId);
	
	if (thisField.style.display != "block"){
		thisField.style.display = "block"
	} else {
		thisField.style.display = "none"
	}

}