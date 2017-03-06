$(document).ready(function() {

	var checklistNum = 3;
	// Code to load each html page.
	$("#createChecklist").click(function(event) {
		// stops # being added to url
		event.preventDefault();
		checklistNum = 3;
		$(".container").empty();

		$(".container")
			.load("html/create.html")
			// when create.html is loaded ... 
			.on("click", "#btnAddItem", function() {
				checklistNum++;
				// add new row to the table
				$("#tblChecklist tr:last").after("<tr> <td>" + checklistNum + "</td> <td><input type='text' class='form-control checklistItem'></td></tr>");
			})
			.on("click", "#btnRemoveItem", function() {
				if(checklistNum > 1) {
					checklistNum--;
					$("#tblChecklist tr:last").remove();
				}
			})
			.on("click", "#btnSaveChecklist", function() {
				var checklistData = []; 

				$(".checklistItem").each(function() {
					checklistData.push($(this).val());
				});

				// save to database!
				$.post("php/saveChecklist.php", "checklistData=" + checklistData, function(response) {
    				alert(response);
				});
			});
	});

	$("#viewChecklist").click(function(event) {
		event.preventDefault();

		$(".container").empty();
		$(".container").load("html/view.html");

		var checklists;
		var checklistItems;
		var itemArray;

		// get stuff from database
		var checklistRequest = $.get("php/getChecklist.php", function(data) {	
			checklists = data;
		});

		var checklistItemRequest = $.get("php/getChecklistItem.php", function(data) {
			checklistItems = data;
		});


		$.when(checklistRequest, checklistItemRequest).done(function() {
			console.log("requests completed!");

			$.each(JSON.parse(checklists), function(index, item) {
				if(item.LastInspection == null) {
					item.LastInspection = "Not Set";
				}

				if(item.NextInspection == null) {
					item.NextInspection = "Not Set";
				}

				if(item.Status == null) {
					item.Status = "Not Set"
				}

				$("#tblView tr:last")
					.after("<tr> <td class='col-md-1'>" + item.EquipmentType + "</td>" +
					   "<td class='col-md-1'>" + item.LastInspection + "</td>" +
					   "<td class='col-md-1'>" + item.NextInspection + "</td>" +
					   "<td class='col-md-2'><select class='form-control selectbox' id='item" + item.ChecklistID + "'></select></td>" +
					   "<td class='col-md-1'>" + item.Status + "</td>" +
					   "<td class='col-md-1'> <button type='button' class='btn btn-warning btn-sm'><span class='glyphicon glyphicon-edit'></span></button>" +
	   				   "<button type='button' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span></button></td></tr>");
			});

			itemArray = $.parseJSON(checklistItems);

			for(var i = 0; i < itemArray.length; i++) {
				if("item" + itemArray[i].ChecklistID == $("#item" + itemArray[i].ChecklistID).attr("id")) 
			 		$("#item" + itemArray[i].ChecklistID).append("<option>" + itemArray[i].ChecklistItem + "</option>");
			}
		});
	});
});	