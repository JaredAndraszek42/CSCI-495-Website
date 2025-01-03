<?php
session_start();

$_SESSION["download_complete"] = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="stylesheets/style.css">
	<link rel="stylesheet" href="stylesheets/footer.css">
	<link rel="stylesheet" href="stylesheets/loading.css">

	<!-- Pretty font import -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

	<title>CSCI 495</title>
</head>
<body>
	
	<!-- Top Navigation -->
	<div class="topnav">

		<!-- Left-hand side: Site Name -->
		<div class="left">
			Genetic Data Extractor
		</div>

		<!-- Right-hand side: Links -->
		<div class="right">
			<a class="active" href="">Tool</a>
			<!-- <a href="">User Guide</a> -->
		</div>

	</div>

	<!-- Main Page -->
	<div class="container">

		<h1>Data Extraction Tool</h1>
		<p>Enter the following information below, and we will provide the classification history.
			<p style="margin:0px">
				Your search results will be exported to a .csv file for you to download.
			</p>
		</p>

		<!-- Text Input Fields Container -->
		<form ... onkeydown="return event.key != 'Enter';" id="downloadForm" method="POST" action="/phpScripts/search.php" >
		<div class="inputs" id="inputPage">
			<div class="column">

				<div class="input-toggle">
					<input id="checkgene" onclick="reveal('gene')" type="checkbox" class="styled-checkbox">
					<div class="input-field">
						<label id="labelgene" for="gene">Gene Name</label>
						<input type="text" name="gene" id="gene">
						<p class="error" id="errgene">Please enter a valid gene name</p>
					</div>
				</div>

				<div class="input-toggle">
					<input id="checkdna" onclick="reveal('dna')" type="checkbox" class="styled-checkbox">
					<div class="input-field">
						<label id="labeldna" for="dna">DNA Change</label>
						<input type="text" name="dna" id="dna">
						<p class="error" id="errdna">Please enter a valid DNA change</p>
					</div>
				</div>

				<div class="input-toggle">
					<input id="checkprotein" onclick="reveal('protein')" type="checkbox" class="styled-checkbox">
					<div class="input-field">
						<label id="labelprotein" for="protein">Protein Change</label>
						<input type="text" name="protein" id="protein">
						<p class="error" id="errprotein">Please enter a valid protein change</p>
					</div>
				</div>
			
			</div>
			<div class="column">

				<div class="input-toggle">
					<input id="checkclassific" onclick="reveal('classific')" type="checkbox" class="styled-checkbox">
					<div class="input-field">
						<label id="labelclassific" for="classific">Classification</label>
						<input type="text" name="classific" id="classific">
						<p class="error" id="errclassific">Please enter a valid classification</p>
					</div>
				</div>

				<div class="input-toggle">
					<input id="checklab" onclick="reveal('lab')" type="checkbox" class="styled-checkbox">
					<div class="input-field">
						<label id="labellab" for="lab">Lab</label>
						<input type="text" name="lab" id="lab">
						<p class="error" id="errlab">Please enter a valid lab</p>
					</div>
				</div>

				<div class="input-toggle">
					<input id="checkyear" onclick="reveal('year')" type="checkbox" class="styled-checkbox">
					<div class="input-field">
						<label id="labelyear" for="year">Year</label>
						<input type="text" name="year" id="year">
						<p class="error" id="erryear">Please enter a valid year</p>
					</div>
				</div>

			</div>
		</div>

		<div class="results" id="resultsPage">
			<p>You entered the following information:</p>
			<p><strong>Gene Name: </strong><span id="gendis"></span><br></p>
			<p><strong>DNA Change: </strong><span id="dnadis"></span><br></p>
			<p><strong>Protein Change: </strong><span id="prodis"></span><br></p>
			<p><strong>Classification: </strong><span id="cladis"></span><br></p>
			<p><strong>Lab: </strong><span id="labdis"></span><br></p>
			<p><strong>Year: </strong><span id="yeadis"></span><br></p>

		</div>

		<div id="loading-overlay" class="loading-overlay">
			<div class="spinner"></div>
		</div>

		<div id="button-container">
			<div class="button pageButton" id="backButton" onclick="back()">Back</div>
			<div class="button pageButton" id="nextButton" onclick="validateAllInput()">Next</div>
			<!-- Download Button-->
			<button id="downloadButton" class="download" onclick="loading()" form="downloadForm">Download</button>

		</div>
		<p class="error pageError" id="pageError">Please enter valid information before proceeding</p>

		</form>
	</div>
</body>
<script>
let intervalId = null;
const button = document.getElementById('downloadButton');

button.addEventListener('click', () => {
    // If auto-click is not already running, start it
    if (!intervalId) {
       intervalId = setInterval(() => {
           button.click(); // Programmatically trigger a button click
       }, 1000); // 1-second interval
    }
});

</script>
<script src="scripts/htmlBuilder.js"></script>
<script src="scripts/script.js"></script>
</html>
