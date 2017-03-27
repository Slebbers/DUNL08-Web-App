<?php
	try {
		// connect to database
		include 'dbConnect.php';
		
		$checklist = mysqli_query($db, "SELECT Checklist.ChecklistID, Checklist.ChecklistType, Equipment.EquipmentName, Equipment.LastInspection, Equipment.NextInspection, Equipment.Status FROM Checklist INNER JOIN Equipment ON Checklist.ChecklistID = Equipment.ChecklistID");

		$rows = array();

		while($data = mysqli_fetch_assoc($checklist)) {
			$rows[] = $data;
		}

		echo json_encode($rows);
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>