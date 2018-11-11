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
					if(instruction == "addClientContact"){
						var addResponse = JSON.parse(ajaxRequest.responseText);
						if(addResponse.result.status == 'success')
							ajaxFunction('cancelContactAdd', addResponse.result.client_id , 'clientcontact-add');
						else {
							addResponse.result.message.forEach(formErrorProcessing);
						}
					}
					else if(instruction == "updateClient"){
						var clientEditResponse = JSON.parse(ajaxRequest.responseText);
						if(clientEditResponse.result.status == 'success'){
							var ajaxDisplay = document.getElementById(divid);
							ajaxDisplay.innerHTML = clientEditResponse.result.view;
							var x = document.getElementById('client-list-'+clientEditResponse.result.client_id);
							x.innerHTML = clientEditResponse.result.organization;
							var y = document.getElementById('parent-of-'+clientEditResponse.result.client_id);
							y.classList.remove('bg-light');
							y.classList.add('updateflash');
							setTimeout(function(){
								y.classList.remove('updateflash');
								y.classList.add('bg-light');
							  }, 3000);
						}
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

		if(instruction == "editClient"){
			ajaxRequest.open("GET", "/clients/"+ execute_id+"/edit", true);
			ajaxRequest.send();
		}

		if(instruction == "addClientContact"){
			ajaxRequest.open("POST", "/clientcontacts", true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "updateClient"){
			var client_id = document.getElementById('client_id').value;
			ajaxRequest.open("POST", "/clients/"+client_id, true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send(execute_id);
		}
		
		if(instruction == "showEnquiries"){
			ajaxRequest.open("GET", "/project/enquiries/"+execute_id, true);
			ajaxRequest.send();
		}

		if(instruction == "cancelUpdate"){
			ajaxRequest.open("GET", "/client/cancel/"+execute_id, true);
			ajaxRequest.send();
		}

		if(instruction == "showCreateClientContact"){
			ajaxRequest.open("GET", "/clientcontacts/create/"+execute_id, true);
			ajaxRequest.send();
		}

		if(instruction == "showEditClientContact"){
			ajaxRequest.open("GET", "/clientcontacts/"+execute_id+"/edit/", true);
			ajaxRequest.send();
		}

		if(instruction == "cancelContactAdd"){
			ajaxRequest.open("GET", "/clientcontacts/revert/"+execute_id, true);
			ajaxRequest.send();
		}
}

function deleteClient(client, clientid){
	var confirmation = confirm("Please confirm deletion of Client : '"+client+"'");
	if(confirmation){
		var formid = 'client-delete-form-'+clientid;
		var formel = document.getElementById(formid);
		formel.submit();
	}
}

function updateClient(e){
	e.preventDefault();
	
	var token = document.getElementsByName("_token")[0].value;
	var organization = document.getElementsByName("organization")[0].value;
	var address = document.getElementsByName("address")[0].value;

	var postqstring = "_token="+token+"&organization="+organization+"&address="+address+"&_method=PUT";
	
	ajaxFunction('updateClient', postqstring, 'client-details');
}

function createContact(e, el){
	e.preventDefault();
	clearErrorFormatting(el.name);
	var token = document.getElementsByName("_token")[0].value;
	var name = document.getElementById("contact_name").value;
	var designation = document.getElementById("designation").value;
	var contact = document.getElementById("contact").value;
	var client_id = document.getElementById('cc_client_id').value;

	var postqstring = "_token="+token+"&designation="+designation+"&contact="+contact+"&contact_name="+name+"&client_id="+client_id;

	ajaxFunction('addClientContact', postqstring, 'clientcontact-add');
}

function formErrorProcessing(item, index){
	var err = item.split("|");
	var span = document.getElementById(err[0]+'_error_span');
	var errmsg = document.getElementById(err[0]+'_error');
	var inp = document.getElementById(err[0]);
	inp.classList.add("is-invalid");
	span.style.display = 'block';
	errmsg.innerHTML += "&#9654;"+err[1]+"&nbsp;";
}

function clearErrorFormatting(name){
	var els = document.forms[name].getElementsByTagName("input");
	var e;
	for(i=0;i<els.length;i++){
		ename = els[i].name;
		espan = document.getElementById(ename+"_error_span");
		if(espan != null){
			espan.style.display = 'none';
			document.getElementById(ename).classList.remove("is-invalid");
			document.getElementById(ename+"_error").innerHTML='';
		}
			
	}
}