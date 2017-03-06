<?php
	
	try {
		// connect to database
		include 'dbConnect.php';

		 // Checklist data posted from web page
		$checklistData = $_POST['checklistData'];
		 // ChecklistData is recieved as a comma seperated string, create an array from the data
		$checklistArray = explode(',', $checklistData);

		 // Create a new Checklist, assign an equipment to it after the equipment has been created.
		$queryInsertChecklist = mysqli_query($db, "INSERT INTO Checklist(ChecklistID) VALUES(NULL)");

		 // Retrieve the highest ChecklistID value, for this will be assigned to our new equipment
		$queryGetHighID = mysqli_query($db, "SELECT ChecklistID FROM Checklist ORDER BY ChecklistID DESC LIMIT 1");
		$checklistNewID;

		while($data = mysqli_fetch_assoc($queryGetHighID)) {
			$checklistNewID = $data['ChecklistID'];
		}

		 // Now that the checklist has been created, we can assign a checklist to our new equipment,
		 // the first element in $checklistData is the equipment type
		$queryInsertEquipment = mysqli_query($db, "INSERT INTO Equipment(EquipmentType, ChecklistID) VALUES('$checklistArray[0]', '$checklistNewID')");

		 // Now we need to get the new equipmentID, and assign it to our checklist
		$queryGetEquipmentID = mysqli_query($db, "SELECT EquipmentID FROM Equipment ORDER BY EquipmentID DESC LIMIT 1");
		$equipmentNewID;

		while($data = mysqli_fetch_assoc($queryGetEquipmentID)) {
			$equipmentNewID = $data['EquipmentID'];
		}

		 // Assign our Checklist
		$queryAssignChecklist = mysqli_query($db, "UPDATE Checklist SET EquipmentID = '$equipmentNewID' WHERE ChecklistID = '$checklistNewID'");
		$size = count($checklistArray);

		 // Now insert our checklist items
		 // start from 1 since our first element is the equipment type
		for($i = 1; $i < $size; $i++) {
			mysqli_query($db,"INSERT INTO ChecklistItem(ChecklistItem, IsChecked, ChecklistID) VALUES('$checklistArray[$i]', 0, '$checklistNewID')");
		}

		echo "Checklist succesfully created!";
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>