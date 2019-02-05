/**
 * @author Kishor Mali
 */


jQuery(document).ready(function(){
	
	jQuery(document).on("click", ".deleteUser", function(){
		var userId = $(this).data("userid"),
			hitURL = baseURL + "deleteUser",
			currentRow = $(this);
		
		var confirmation = confirm("Are you sure to delete this user ?");
		
		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { userId : userId } 
			}).done(function(data){
				console.log(data);
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("User successfully deleted"); }
				else if(data.status = false) { alert("User deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});


	jQuery(document).on("click", ".deleteCategory", function(){
		var categoryId = $(this).data("categoryid"),
			hitURL = baseURL + "deleteCategory",
			currentRow = $(this);
		
		var confirmation = confirm("Are you sure to delete this Category ?");
		
		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { categoryId : categoryId } 
			}).done(function(data){
				
				if(data.status == true) { alert("Category successfully deleted");currentRow.parents('tr').remove(); }
				else if(data.status == false) { alert("Category deletion failed"); }
				else if(data.status == 'exist') { alert("Story of Given Category is present"); }
				else { alert("Access denied..!"); }
			});
		}
	});
	jQuery(document).on("click", ".deleteTitle", function(){
		var categoryId = $(this).data("categoryid"),
			hitURL = baseURL + "title/deleteCategory",
			currentRow = $(this);
		
		var confirmation = confirm("Are you sure to delete this Title ?");
		
		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { categoryId : categoryId } 
			}).done(function(data){
				
				if(data.status == true) { alert("Title successfully deleted");currentRow.parents('tr').remove(); }
				else if(data.status == false) { alert("Title deletion failed"); }
				else if(data.status == 'exist') { alert("Story of Given Title is present"); }
				else { alert("Access denied..!"); }
			});
		}
	});
	
	jQuery(document).on("click", ".deleteKind", function(){
		var kindId = $(this).data("kindid"),
			hitURL = baseURL + "kind/deleteKind",
			currentRow = $(this);
		
		var confirmation = confirm("Are you sure to delete this Kinds ?");
		
		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { kindId : kindId } 
			}).done(function(data){
				
				if(data.status == true) { alert("Kinds deleted successfully");currentRow.parents('tr').remove(); }
				else if(data.status == false) { alert("Kinds deletion failed"); }
				//else if(data.status == 'exist') { alert("Story of Given Kinds is present"); }
				else { alert("Access denied..!"); }
			});
		}
	});
	
	jQuery(document).on("click", ".searchList", function(){
		
	});
	
});
