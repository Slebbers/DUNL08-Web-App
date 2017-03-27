<?php
	
	try {
		// connect to database
		include 'dbConnect.php';

		 // Checklist data posted from web page
		$checklistData = $_POST['checklistData'];
		 // ChecklistData is recieved as a comma seperated string, create an array from the data
		$checklistArray = explode(',', $checklistData);

		
		 // Create a new Checklist, assign an equipment to it after the equipment has been created.
		$checklist = mysqli_query($db, "INSERT INTO Checklist(ChecklistType) VALUES('$checklistArray[0]')");
		$newID = mysqli_insert_id($db);
		
		echo($newID);
		$size = count($checklistArray);

		 // Now insert our checklist items
		 // start from 1 since our first element is the equipment type
		for($i = 1; $i < $size; $i++) {
			mysqli_query($db,"INSERT INTO ChecklistItem(ChecklistItem, ChecklistID) VALUES('$checklistArray[$i]', '$newID')");
		}

		echo "Checklist successfully created!";
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>