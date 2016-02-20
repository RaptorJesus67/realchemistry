$(document).ready(function(){
	
	var base_uri = "http://realchemistry.loc/";
	
	
	// UPDATE USER EDIT PROFILE EVERYTIME AN INPUT IS CHANGED
	$(".userEditProfileForm input, .userEditProfileForm select").bind("change paste keyup", function() {
		
		var bDay = $("#uBdayMonth").val() + $("#uBdayDay").val() + $("#uBdayYear").val();
		
		var formValues = {
							"fName": 	$("#fName").val(),
							"lName": 	$("#lName").val(),
							"city":		$("#uCity").val(),
							"state":	$("#uStateSelect option:selected").text(),
							"country":	$("#uCountrySelect option:selected").text(),
							"birthday":	bDay
						};
		
		$.ajax({
		
			url: base_uri + "user",
			type: 'POST',
			data: {update: formValues},
			success: function(value){
			
				
				
			}
			
		});
		
	});
	
	
	
	
	////////////////////////////////////
	//	USERNAME BINDING
	//
	//
	$("#username").bind("change paste keyup", function() {
		
		
		$.ajax({
			
			url: base_uri + "register",
			method: 'POST',
			data: {username: $("#username").val()},
			success: function(value) {
				
				// Remove each bind
				$("#usernameMessage > p").text("");
				$("#usernameMessage > p").attr("class", "");
				
				console.log(value);
				
				// If it returns value
				if (value != null || value != "null") {
					
					var t = jQuery.parseJSON(value);
					$("#usernameMessage > p").text(t.text);
					$("#usernameMessage > p").addClass(t.class);
					
				}
				
			}
			
		});
		
	});
	
	
	
	
	
	////////////////////////////////////
	//	EMAIL BINDING
	//
	//
	$("#email").bind("change paste keyup", function() {
		
		
		$.ajax({
			
			url: base_uri + "register",
			method: 'POST',
			data: {email: $("#email").val()},
			success: function(value) {
				
				// Remove each bind
				$("#emailMessage > p").text("");
				$("#emailMessage > p").attr("class", "");
				
				console.log(value);
				
				// If it returns value
				if (value != null || value != "null") {
					
					var t = jQuery.parseJSON(value);
					$("#emailMessage > p").text(t.text);
					$("#emailMessage > p").addClass(t.class);
					
				}
				
			}
			
		});
		
	});
	
	
	
	
	
	///////////////////////////////////
	// REGISTRATION FORM SUBMITTED
	//
	//
	//
	$("#registerForm").on("submit", function(e) {
		
		e.preventDefault();
		
		var formValues = {
							"username":		$("#username").val(),
							"email":		$("#email").val(),
							"password":		$("#password").val(),
							"confirm":		$("#confirm").val()
						};
						
						
		$.ajax({
			
			url: $("#registerForm").attr("action"),
			method: 'POST',
			data: {regData: formValues},
			success: function(data) {
			
				console.log(data);
				
			}
			
		});	
		
	});
	
});




$(document).on("submit", ".addBandForm", function(e) {
	
	e.preventDefault();
	
	var formData = {
						"bandName":		$("#bandName").val(),
						"bandEmail":	$("#bandEmail").val(),
						"bandCity":		$("#bandCity").val(),
						"bandState":	$("#bandState").val(),
						"bandCountry":	$("#bandCountry").val(),
						"bandWebsite":	$("#bandWebsite").val(),
						"bandFormed":	$("#bandFormed").val(),
					}
					
	$.ajax({
			
		url: $(".addBandForm").attr("action"),
		method: 'POST',
		data: {bandData: formData},
		success: function(data) {
		
			console.log(data);
			
		}
		
	});	
	
});


