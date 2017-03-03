<?php
	try {
		// connect to database
		$db = mysqli_connect("127.0.0.1", "root", "", "checklist");
		$checklist = mysqli_query($db, "SELECT checklist.ChecklistID, checklist.EquipmentID, equipment.EquipmentType, equipment.LastInspection, equipment.NextInspection, equipment.Status FROM checklist INNER JOIN equipment ON checklist.ChecklistID = equipment.ChecklistID");
		$rows = array();

		while($data = mysqli_fetch_assoc($checklist)) {
			$rows[] = $data;
		}

		echo json_encode($rows);
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>