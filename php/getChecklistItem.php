<?php
	try {
		// connect to database
		$db = mysqli_connect("127.0.0.1", "root", "", "checklist");
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