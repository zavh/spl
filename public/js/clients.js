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
					if(instruction == "addClient"){
						var addResponse = JSON.parse(ajaxRequest.responseText);
						if(addResponse.result == 'success')
							window.location.href ="/clients";
					}
					else{
						var ajaxDisplay = document.getElementById(divid);
						ajaxDisplay.innerHTML = ajaxRequest.responseText;
					}
				}
	    } 

		if(instruction == "viewclient"){
			ajaxRequest.open("GET", "/clients/"+ execute_id, true);
			ajaxRequest.send();
		}

		if(instruction == "showCreateClient"){
			ajaxRequest.open("GET", "/clients/create", true);
			ajaxRequest.send();
		}

		if(instruction == "addClient"){
			console.log(execute_id);
			ajaxRequest.open("POST", "/clients/store", true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send(execute_id);
		}
}

function deleteClient(client, clientid){
	var confirmation = confirm("Please confirm deletion of Client : '"+client+"'");
	if(confirmation){
		//preventDefault();
		var formid = 'client-delete-form-'+clientid;
		var formel = document.getElementById(formid);
		formel.submit();
	}
}

function addClient(e){
	e.preventDefault();
	
	var token = document.getElementsByName("_token")[0].value;
	var name = document.getElementsByName("name")[0].value;
	var contact = document.getElementsByName("contact")[0].value;
	var organization = document.getElementsByName("organization")[0].value;
	var address = document.getElementsByName("address")[0].value;

	var postqstring = "_token="+token+"&name="+name+"&contact="+contact+"&organization="+organization+"&address="+address;

	ajaxFunction('addClient', postqstring, 'client-container');
	//console.log(postqstring);
}