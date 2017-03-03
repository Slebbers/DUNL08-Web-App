<?php
	try {
		// connect to database
		$db = mysqli_connect("127.0.0.1", "root", "", "checklist");

		$query = "SELECT Checklist.ChecklistID, Checklist.EquipmentID, ChecklistItem.ChecklistItemID, ChecklistItem.ChecklistItem, ChecklistItem.IsChecked, Equipment.EquipmentID, Equipment.EquipmentType,	Equipment.LastInspection, Equipment.NextInspection, Equipment.Status FROM checklist INNER JOIN ChecklistItem ON Checklist.ChecklistID = ChecklistItem.ChecklistID INNER JOIN Equipment ON Checklist.ChecklistID = Equipment.ChecklistID";
		$checklist = mysqli_query($db, $query);
		$rows = array();

		while($data = mysqli_fetch_assoc($checklist)) {
			$rows[] = $data;
		}

		echo json_encode($rows);
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>