function ajaxFunction(instruction, execute_id, divid){
	var ajaxRequest;  // The variable that makes Ajax possible!
		try{
				// Opera 8.0+, Firefox, Safari
				ajaxRequest = new XMLHttpRequest();
		} catch (e){
				// Internet Explorer Browsers
				try{
						ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
						try{
								ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e){
								// Something went wrong
								alert("Your browser broke!");
								return false;
						}
				}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4 && ajaxRequest.status == 200){
				    var ajaxDisplay = document.getElementById(divid);
				    ajaxDisplay.innerHTML = ajaxRequest.responseText;
				}
	    } 

		if(instruction == "viewuser"){
			ajaxRequest.open("GET", "/users/"+ execute_id, true);
			ajaxRequest.send();
		}
		if(instruction == "viewusertasks"){
			ajaxRequest.open("GET", "/user/tasks", true);
			ajaxRequest.send();
		}		
}

function deleteUser(user, userid){
	var confirmation = confirm("Please confirm deletion of User : '"+user+"'");
	if(confirmation){
		//preventDefault();
		var formid = 'user-delete-form-'+userid;
		var formel = document.getElementById(formid);
		formel.submit();
	}
}