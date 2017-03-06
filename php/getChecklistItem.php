<?php
	try {
		// connect to database
		include 'dbConnect.php';
		
		$checklistItems = mysqli_query($db, "SELECT * FROM ChecklistItem");
		$rows = array();

		while($data = mysqli_fetch_assoc($checklistItems)) {
			$rows[] = $data;
		}

		echo json_encode($rows);
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>