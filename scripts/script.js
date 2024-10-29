
/** 
 * reveals the text input field whenever the 
 * check box is toggled
 * */ 
function reveal(fieldId) {
	let thisField = document.getElementById(fieldId);
	
	if (thisField.style.display != "block"){
		thisField.style.display = "block";
	} else {
		thisField.style.display = "none";

		// clear input text on deselection
		thisField.value = "";
		thisField.innerText = "";
	}
}

let inputNames = ["gene", "dna", "protein", "classific", "lab", "year"];

let textInputs = [];
let inputLabels = [];
let errorLabels = [];
let checkBoxes = [];

/**
 * Initializes arrays containing html elements for each field
 */
function populateInputDOM() {

	for (let i = 0; i < inputNames.length; i++) {
		// example: gene
		const x = inputNames[i];
		
		// textInput : gene
		textInputs.push(document.getElementById(x));
		
		// inputLabel : labelgene
		inputLabels.push(document.getElementById("label" + x));
		
		// errorLabels : errgene
		errorLabels.push(document.getElementById("err" + x));
		
		// checkBoxes : checkgene
		checkBoxes.push(document.getElementById("check" + x));

	}
}

populateInputDOM();

function getUserInput() {
	let userInputs = [];

	for (let i = 0; i < checkBoxes.length; i++) {
		const box = checkBoxes[i];
		
		let x = box.checked;
		let y = textInputs[i].value;

		userInputs.push([x,y]);
	}

	return userInputs;
}

/**
 * Receives error type and displays it to the corresponding input field
 * 
 * Error Types: empty, nan (not a number), specialChar,  
 * 
 * @param {string} inputName 
 * @param {string} errorType 
 */
function handleError(inputName, errorType) {

	let message = "";

	switch (errorType) {
		case "empty":
			message += "Empty value at " + inputName;
			message += "\n";
			break;
		case "nan":
			message += inputName + " is not a number";
			break;
		case "specialChar":
			message += inputName +" has special characters";
			break;
		default:
			message += "Unable to handle error.\n";
			message += "   Error Type:    " + errorType + "\n";
			message += "   Input Field:   " + inputName + "\n";
			break;
	}
	console.log(" [ ErrorHandler ] " + message);
}


function emptyCheck(string) {
	if (string.trim() == "" || string.length == 0) {
		return false;	
	} 
	else { return true; }
}

function specialCheck(string, char) {
	if (string.includes(char)) { return false;	} 
	else { return true; }
}

function validateInput() {

	let errorsFound = false;

	let userInputs = getUserInput();

	// console.table(userInputs);

	for (let i = 0; i < userInputs.length; i++) {
		const x = userInputs[i][0];
		const y = userInputs[i][1];
		
		if (x && emptyCheck(y) == false) {

			handleError(inputNames[i], "empty")
			errorsFound = true;
		}
	}

	if (errorsFound) {
		console.log(" [ Validator ] Input failed validation");
	}else {
		console.log(" [ Validator ] Input passed validation");

	}
	
}