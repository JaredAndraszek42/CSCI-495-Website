let pageError = document.getElementById('pageError');

let inputPage = document.getElementById("inputPage")
let resultsPage = document.getElementById("resultsPage")
let backButton = document.getElementById("backButton")
let nextButton = document.getElementById("nextButton")

backButton.style.display = "none"

function reveal(fieldId) {
	let thisField = document.getElementById(fieldId);

	let index = inputNames.indexOf(fieldId);
	
	if (thisField.style.display != "block"){
		thisField.style.display = "block";
	} else {
		thisField.style.display = "none";

		// clear input text on deselection
		thisField.value = "";
		thisField.innerText = "";
		hideErrorLabels(index);
	}
}

function nextPage() {
	inputPage.style.display = "none"
	resultsPage.style.display = "flex"

	backButton.style.display = "flex"
	
	nextButton.style.display = "none"
	
	// createDownloadButton()
	showDownloadButton()
}

function back(){
	inputPage.style.display = "flex"
	resultsPage.style.display = "none"

	nextButton.style.display = "flex"
	backButton.style.display = "none"
	
	// deleteDownloadButton()
	hideDownloadButton()
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


function showDownloadButton() {
	let x = document.getElementById("downloadButton")

	x.style.display = 'block';
	
}

function hideDownloadButton() {
	let x = document.getElementById("downloadButton")
	
	x.style.display = 'none';
	
}