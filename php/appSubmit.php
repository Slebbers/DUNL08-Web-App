<?php
	try {
		// connect to database
		include 'dbConnect.php';
		
		$recievedJSON = $_GET['json'];

		$array = json_decode($recievedJSON, true);
		$size = count($array['ChecklistItems']);

		for($i = 0; $i < $size; $i++) {
			$query = "UPDATE ChecklistItem SET IsChecked = " . $array['ChecklistItems'][$i]['IsChecked'] . " WHERE ChecklistID = " 
				. $array['ChecklistID'] . " AND ChecklistItem = '" . $array['ChecklistItems'][$i]['ChecklistItem'] . "'";

			mysqli_query($db, $query);
		}

		//last inspection, nextinspection,status
		mysqli_query($db, "UPDATE Equipment SET LastInspection = '" . $array['LastInspection'] . "' WHERE EquipmentID = " . $array['EquipmentID']);
		mysqli_query($db, "UPDATE Equipment SET NextInspection = '" . $array['NextInspection'] . "' WHERE EquipmentID = " . $array['EquipmentID']);
		mysqli_query($db, "UPDATE Equipment SET Status = '" . $array['Status'] . "' WHERE EquipmentID = " . $array['EquipmentID']);
		echo "complete";
	} catch(Exception $e) {
	 	echo "Error: " . $e;
	}
?>