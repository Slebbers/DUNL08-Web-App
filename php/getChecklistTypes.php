<?php
	try {
		// connect to database
		include 'dbConnect.php';
		
		$checklist = mysqli_query($db, "SELECT ChecklistType FROM Checklist");
		$rows = array();

		while($data = mysqli_fetch_assoc($checklist)) {
			$rows[] = $data;
		}

		echo json_encode($rows);
	} catch(Exception $e) {
		echo "Error: " . $e;
	}
?>