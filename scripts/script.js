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

function hideErrorLabels(index) {
	const x = errorLabels[index];
	const y = textInputs[index];
	x.style.display = "none";
	y.style.borderColor = "#8ab1b4";
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
			message += formalName + " must be a number";
			break;
		case "special":
			message += formalName + " should not have a special character";
			break;
		case "dna":
			message += formalName + " should be in this format: c.181T";
			break;
		case "protien":
			message += formalName + " should be in this format: p.Cys64Gly";
			break;
		case "none":
		default:

			message += "Validation error"

			console.log("Unable to handle error.\n" + 
				"   Error Type:    " + errorType + "\n" +
				"   Input Field:   " + inputName + "\n"
			)
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

function specialCheck(string) {
	const specialCharRegex = /[~`!@#$%^&*()\-+=|\\}\]{\["':;?/><,]/;
	if (!specialCharRegex.test(string)) {
		return true;
	}
	else {
		return false;
	}
}

function numberCheck(string) {
	if (string.trim().length === 0)
		return false;
	return !isNaN(string);
}

function dnaCheck(string) {
	let begin = string[0] + string[1];

	if (begin != "c.") {
		return false
	} else{ return true}
}

function protienCheck(string) {
	let begin = string[0] + string[1];

	if (begin != "p.") {
		return false
	} else{ return true}
}


function displayUserInputs(userInputs) {
	for (let i = 0; i < userInputs.length; i++) {
		const x = userInputs[i][1];
		const y = document.getElementById(displays[i]);

		y.innerText = x;
		
	}
}


function validateAllInput() {

	// Reset all labels
	for (let i = 0; i < errorLabels.length; i++) {
		hideErrorLabels(i);
	}

	let errorsFound = false;

	let userInputs = getUserInput();

	if (checkBoxCheck() == false) {
		handleError("none", "none")
		errorsFound = true;
	} 
	
	else {

		for (let i = 0; i < userInputs.length; i++) {
			const x = userInputs[i][0];
			const y = userInputs[i][1];
	
			// if this check box TRUE
			if (x) {
	
				// Failed Empty Check: Value was empty
				if (emptyCheck(y) == false) {
		
					handleError(inputNames[i], "empty")
					errorsFound = true;
				}
		
				// Failed Numeric Year: Year was not a number
				else if (i == 5 && numberCheck(y) == false) {
					handleError(inputNames[i], "nan");
					errorsFound = true;
				}

				// Failed DNA Change: DNA change was not in the format c.181T
				else if (i == 1 && dnaCheck(y) == false) {
					handleError(inputNames[i], "dna");
					errorsFound = true;
				}

				// Failed Protien Change: Protien change was not in the format p.Cys64Gly
				else if (i == 2 && protienCheck(y) == false) {
					handleError(inputNames[i], "protien");
					errorsFound = true;
				}
		
				// Failed Special Character: Value contained special charaters
				else if (specialCheck(y) == false) {
					handleError(inputNames[i], "special");
					errorsFound = true;
				}
			}
			
		}
	}

	// Reveal label if error
	if (errorsFound) {
		pageError.style.display = "block";
		
	} 
	
	// otherwise continue
	else {
		pageError.style.display = "none";
		nextPage()
		displayUserInputs(userInputs)
	}
	
}


function loading() {
	// const loadingOverlay = document.getElementById('loading-overlay');
    // loadingOverlay.style.display = 'flex';
}