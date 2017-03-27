<?php
	try {
		// connect to database
		include 'dbConnect.php';

		$checklistType = $_POST['type'];
		// first we need to get the checklistID
		$checklistIDQuery = mysqli_query($db, "SELECT ChecklistID FROM Checklist WHERE ChecklistType = '$checklistType'");
		$checklistIDAssoc = mysqli_fetch_assoc($checklistIDQuery);
		$checklistID = mysqli_real_escape_string($db, $checklistIDAssoc['ChecklistID']);

		// now we need to get all checklist items associated with said checklist
		$checklistItemQuery = mysqli_query($db, "SELECT ChecklistItemID FROM ChecklistItem WHERE ChecklistID = '$checklistID'");
		$checklistItemRows = array();

		// add our ids to checklistItemRows
		while($data = mysqli_fetch_assoc($checklistItemQuery)) {
			$checklistItemRows[] = $data['ChecklistItemID'];
		}

		// Insert our new equipment
		$equipmentName = mysqli_escape_string($db, $_POST['name']);
		$equipmentInsertQuery = mysqli_query($db, "INSERT INTO Equipment(EquipmentName, ChecklistID) VALUES('$equipmentName', '$checklistID')");

		// get the equipmentID
		$selectEquipment = mysqli_query($db, "SELECT EquipmentID FROM Equipment WHERE EquipmentName = '$equipmentName' AND ChecklistID = '$checklistID'");
		$equipment = mysqli_fetch_assoc($selectEquipment); // returned row from above query
		$equipmentID = mysqli_real_escape_string($db, $equipment['EquipmentID']); // actual string ID

		$size = count($checklistItemRows);
		// insert into EquipmentItem
		for($i = 0; $i < $size; $i++) {
			$checklistItemID = mysqli_escape_string($db, $checklistItemRows[$i]);
			mysqli_query($db,"INSERT INTO EquipmentItem(EquipmentID,ChecklistItemID,IsChecked) VALUES('$equipmentID', '$checklistItemID', 0)");
		}

		echo "Success!";
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>