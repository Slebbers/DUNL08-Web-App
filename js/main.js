$(document).ready(function() {

    var checklistNum = 3;
    // Code to load each html page.
    $("#createChecklist").click(function(event) {
        // stops # being added to url
        event.preventDefault();
        checklistNum = 3;
        $(".container").empty();


        $(".container")
            .load("html/create.html", function() {
			   // Remove listeners previously assigned, fixing issues related to clicking the navigation bar multiple times.
                $("#btnAddItem").off("click");
                $("#btnRemoveItem").off("click");
                $("#btnSaveChecklist").off("click");

                $("#btnAddItem").click(function() {
                    checklistNum++;
                    // add new row to the table
                    $("#tblChecklist tr:last").after("<tr> <td>" + checklistNum + "</td> <td><input type='text' class='form-control checklistItem'></td></tr>");
                });

                $("#btnRemoveItem").click(function() {
                    if (checklistNum > 1) {
                        checklistNum--;
                        $("#tblChecklist tr:last").remove();
                    }
                });

                $("#btnSaveChecklist").click(function() {
                    var checklistData = [];
                    var count = 0;

                    // some validation that items have been entered
                    $(".checklistItem").each(function() {
                        if ($(this).val().length != 0) {
                            checklistData.push($(this).val());
                        }
                        count++;
                    });

                    console.log(checklistData);
                    // save to database!
                    if (count == checklistData.length && checklistData.length != 0) {
                        $.post("php/saveChecklist.php", "checklistData=" + checklistData, function(response) {
                            alert(response);
                        });
                    } else {
                        alert("Please enter in all appropriate fields.");
                    }
                });

            });
    });

    $("#addEquipment").click(function(event) {
        event.preventDefault();
        $(".container").empty();
        $(".container")
            .load("html/newEquipment.html")
            .off().on("click", "#btnAddEquipment", function() {
                var equipName = $("#equipmentName").val();
                var checklistType = $("#checklistTypes option:selected").text();

                if (equipName.length != 0) {
                    $.post("php/saveEquipment.php", {
                        name: equipName,
                        type: checklistType
                    }, function(response) {
                        alert(response);
                    });
                } else {
                    alert("Please enter an equipment name.")
                }
            });

        var checklistTypes;

        var checklistTypesRequest = $.get("php/getChecklistTypes.php", function(data) {
            checklistTypes = data;
        });

        $.when(checklistTypesRequest).done(function() {
            $.each(JSON.parse(checklistTypes), function(index, item) {
                $('#checklistTypes').append("<option>" + item.ChecklistType + "</option>");
            });
        });
    });

    $("#viewEquipment").click(function(event) {
        event.preventDefault();

        $(".container").empty();
        $(".container").load("html/view.html");

        var checklists;
        var checklistItems;
        var isChecked;
        var itemArray;
        var isCheckedArray;

        // get stuff from database
        var checklistRequest = $.get("php/getChecklist.php", function(data) {
            checklists = data;
        });

        var checklistItemRequest = $.get("php/getChecklistItem.php", function(data) {
            checklistItems = data;
        });

        var isCheckedRequest = $.get("php/getIsChecked.php", function(data) {
            isChecked = data;
        });


        $.when(checklistRequest, checklistItemRequest, isCheckedRequest).done(function() {
            console.log("requests completed!");

            $.each(JSON.parse(checklists), function(index, item) {
                if (item.LastInspection == null) {
                    item.LastInspection = "Not Set";
                }

                if (item.NextInspection == null) {
                    item.NextInspection = "Not Set";
                }

                if (item.Status == null) {
                    item.Status = "Not Set"
                }


                $("#tblView tr:last")
                    .after("<tr> <td class='col-md-1'>" + item.EquipmentName + "</td>" +
                        "<td class='col-md-1'>" + item.LastInspection + "</td>" +
                        "<td class='col-md-1'>" + item.NextInspection + "</td>" +
                        "<td class='col-md-2'><select class='form-control selectbox' id='item" + item.ChecklistID + "'></select></td>" +
                        "<td class='col-md-1'>" + item.Status + "</td></tr>");
                // "<td class='col-md-1'> <button type='button' class='btn btn-warning btn-sm'><span class='glyphicon glyphicon-edit'></span></button>" +
                // "<button type='button' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span></button></td></tr>");
            });

            itemArray = $.parseJSON(checklistItems);
            isCheckedArray = $.parseJSON(isChecked);
            console.log(isCheckedArray);
            console.log(itemArray);

            // Fill up items and display a X or ✓ if they've been checked or not by the app
            for (var i = 0; i < itemArray.length; i++) {
                if ("item" + itemArray[i].ChecklistID == $("#item" + itemArray[i].ChecklistID).attr("id")) {
                    for (var j = 0; j < isCheckedArray.length; j++) {
                        if (itemArray[i].ChecklistItemID == isCheckedArray[j].ChecklistItemID) {
                            if (isCheckedArray[j].IsChecked == 0) {
                                $("#item" + itemArray[i].ChecklistID).append("<option>" + itemArray[i].ChecklistItem + " X </option>");
                            } else {
                                $("#item" + itemArray[i].ChecklistID).append("<option>" + itemArray[i].ChecklistItem + " ✓  </option>");
                            }
                        }
                    }
                }
            }
        });
    });

    $("#viewChecklists").click(function(event) {
        event.preventDefault();

        $(".container").empty();
        $(".container").load("html/viewChecklists.html")

        var checklists;

        var checklistRequest = $.get("php/getChecklistData.php", function(data) {
            checklists = data;
        });

        $.when(checklistRequest).done(function() {
            var lastInserted;
            $.each(JSON.parse(checklists), function(index, item) {
                if (lastInserted != item.ChecklistType) {
                    $("#tblViewChecklists tr:last")
                        .after("<tr> <td class='col-md-1'>" + item.ChecklistType + "</td>" +
                            "<td class='col-md-2'><select class='form-control selectbox' id='item" + item.ChecklistID + "'></select></td></tr>");
                    lastInserted = item.ChecklistType;
                } else {
                    lastInserted = item.ChecklistType;
                }

            });

            var itemArray = $.parseJSON(checklists);

            for (var i = 0; i < itemArray.length; i++) {
                if ("item" + itemArray[i].ChecklistID == $("#item" + itemArray[i].ChecklistID).attr("id")) {
                    $("#item" + itemArray[i].ChecklistID).append("<option>" + itemArray[i].ChecklistItem + " </option>");
                }
            }
        });
    });
});