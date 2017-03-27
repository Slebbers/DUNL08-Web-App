<?php
	try {
		// connect to database
		include 'dbConnect.php';
		// Access straight from input, as OkHttp sends as JSON, which we cannot access through
		// $_POST
		$recievedData = json_decode(file_get_contents("php://input"), true);
		$size = count($recievedData);
		
		for($i = 0; $i < $size; $i++) {

			$queryLastInspection = "UPDATE Equipment SET LastInspection = " . $recievedData[$i]['LastInspection'] . " WHERE EquipmentID = " . $recievedData[$i]['EquipmentID'];
			mysqli_query($db, $queryLastInspection);
			$queryNextInspection = "UPDATE Equipment SET LastInspection = " . $recievedData[$i]['NextInspection'] . " WHERE EquipmentID = " . $recievedData[$i]['EquipmentID'];
			mysqli_query($db, $queryNextInspection);
			$queryStatus = "UPDATE Equipment SET Status = " . $recievedData[$i]['Status'] . " WHERE EquipmentID = " . $recievedData[$i]['EquipmentID'];
			mysqli_query($db, $queryStatus);
			
			$itemSize = count($recievedData[$i]['ChecklistItems']);
			
			for($j = 0; $j < $itemSize; $j++) {
				$query = "UPDATE EquipmentItem SET IsChecked = " . $recievedData[$i]['ChecklistItems'][$j]['IsChecked'] . " WHERE ChecklistItemID = " . $recievedData[$i]['ChecklistItems'][$j]['ChecklistItemID'];
				mysqli_query($db, $query);
			}
		}
		
		echo "complete";
	} catch(Exception $e) {
	 	echo "Error: " . $e;
	}
?>