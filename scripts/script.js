document.getElementById("backButton").style.display = "none"

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

let pageError = document.getElementById('pageError');

let inputNames = ["gene", "dna", "protein", "classific", "lab", "year"];

let formalNames = ["Gene Name", "DNA Change", "Protien Change", "Classification", "Lab", "Year"]

let displays = ["gendis", "dnadis", "prodis", "cladis", "labdis", "yeadis"]

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

function displayInputError(inputName, message){
	let index = inputNames.indexOf(inputName)
	let errorLabel = errorLabels[index];
	let inputBox = textInputs[index];

	// Reveal label
	errorLabel.style.display = "block";

	// Update message
	errorLabel.innerText = message;

	// Set to red
	inputBox.style.borderColor = "#984464";
}

function hideAllErrorLabels() {
	for (let i = 0; i < errorLabels.length; i++) {
		const x = errorLabels[i];
		const y = textInputs[i];
		x.style.display = "none";
		y.style.borderColor = "#8ab1b4";
	}
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

	let formalName = formalNames[inputNames.indexOf(inputName)]

	let message = "";

	switch (errorType) {
		case "empty":
			message += formalName + " should not be empty";
			message += "\n";
			break;
		case "nan":
			message += formalName + " has to be a number";
			break;
		case "specialChar":
			message += formalName + " should not have a special character";
			break;
		case "none":

		default:
			message += "Unable to handle error.\n";
			message += "   Error Type:    " + errorType + "\n";
			message += "   Input Field:   " + inputName + "\n";
			break;
	}

	if (inputName != "none") {
		displayInputError(inputName, message)

	}

}

function checkBoxCheck() {
	let count = 0
	for (let i = 0; i < checkBoxes.length; i++) {
		const box = checkBoxes[i];
		
		let x = box.checked;

		if (x) {count++}
	}

	if(count == 0) {
		return false
	}
	else {
		return true
	}
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

function displayUserInputs(userInputs) {
	for (let i = 0; i < userInputs.length; i++) {
		const x = userInputs[i][1];
		const y = document.getElementById(displays[i]);

		y.innerText = x;
		
	}
}

function validateAllInput() {

	hideAllErrorLabels();

	let errorsFound = false;

	let userInputs = getUserInput();

	for (let i = 0; i < userInputs.length; i++) {
		const x = userInputs[i][0];
		const y = userInputs[i][1];
		
		if (x && emptyCheck(y) == false) {

			handleError(inputNames[i], "empty")
			errorsFound = true;
		}
	}

	if (checkBoxCheck() == false) {
		handleError("none", "none")
		errorsFound = true;
	}

	if (errorsFound) {
		pageError.style.display = "block";
		
	} else {
		pageError.style.display = "none";
		nextPage()
		displayUserInputs(userInputs)

	}
	
}
let inputPage = document.getElementById("inputPage")
let resultsPage = document.getElementById("resultsPage")
let backButton = document.getElementById("backButton")
let nextButton = document.getElementById("nextButton")

function nextPage() {
	inputPage.style.display = "none"
	resultsPage.style.display = "flex"

	backButton.style.display = "flex"
	
	nextButton.style.display = "none"
	
	createDownloadButton()
}

function back(){
	inputPage.style.display = "flex"
	resultsPage.style.display = "none"

	nextButton.style.display = "flex"
	backButton.style.display = "none"
	
	deleteDownloadButton()
}



function createDownloadButton() {
	let container = document.getElementById("button-container")
	
	let button = document.createElement("div");
	button.className = "button download";
	button.id = "downloadButton";
	button.innerText = "Download"

	container.appendChild(button)
}

function deleteDownloadButton() {
	document.getElementById("downloadButton").remove()
}


