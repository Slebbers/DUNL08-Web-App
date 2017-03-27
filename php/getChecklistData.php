<?php
	try {
		// connect to database
		include 'dbConnect.php';
		
		$checklistItems = mysqli_query($db, "SELECT Checklist.ChecklistID, Checklist.ChecklistType, ChecklistItem.ChecklistItem FROM Checklist, ChecklistItem WHERE Checklist.ChecklistID = ChecklistItem.ChecklistID");
		$rows = array();

		while($data = mysqli_fetch_assoc($checklistItems)) {
			$rows[] = $data;
		}

		echo json_encode($rows);
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>