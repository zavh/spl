var contacts = [];
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
					if(instruction == "newClientValidation"){
						var vResponse = JSON.parse(ajaxRequest.responseText);
						if(vResponse.result.status == 'failed'){
							vResponse.result.message.forEach(formErrorProcessing);
						}
						return;
					}
					if(instruction == "viewclient"){
						ccResponse = JSON.parse(ajaxRequest.responseText);
						contacts = JSON.parse(ccResponse.data.contacts);
						for(i=0;i<contacts.length;i++){
							contacts[i]['selected'] = 0;
						}
						var view = ccResponse.data.view; 
						ajaxDisplay.innerHTML = view;
						return;
					}
					if(instruction == "createClient"){
						cpResponse = JSON.parse(ajaxRequest.responseText);
						console.log(cpResponse);
						return;
					}
				    ajaxDisplay.innerHTML = ajaxRequest.responseText;
				}
	    } 

		if(instruction == "viewclient"){
			document.getElementById("cp-supplimentary").style.display='';
			ajaxRequest.open("GET", "/clientcontacts/listing/"+execute_id, true);
			ajaxRequest.send();
        }
		if(instruction == "showCreateClient"){
			document.getElementById("cp-supplimentary").style.display='';
			document.getElementById("client_id").selectedIndex = 0;	
			ajaxRequest.open("GET", "/project/createclient", true);
			ajaxRequest.send();
		}
		if(instruction == "showAddTask"){
			ajaxRequest.open("GET", "/tasks/create/"+ execute_id, true);
			ajaxRequest.send();
		}
		if(instruction == "showTasks"){
			ajaxRequest.open("GET", "/tasks/project/"+ execute_id, true);
			ajaxRequest.send();
		}
		if(instruction == "editTasks"){
			ajaxRequest.open("GET", "/tasks/"+execute_id+"/edit", true);
			ajaxRequest.send();
		}

		if(instruction == "showEnquiries"){
			ajaxRequest.open("GET", "/enquiries/project/"+ execute_id, true);
			ajaxRequest.send();
		}
		if(instruction == "editEnquiries"){
			ajaxRequest.open("GET", "/enquiries/"+execute_id+"/edit", true);
			ajaxRequest.send();
		}
		if(instruction == "newClientValidation"){
			ajaxRequest.open("POST", "/clients/validateonly/", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
		if(instruction == "createClient"){
			ajaxRequest.open("POST", "/projects", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
}

function getClient(el){
    var index = el.selectedIndex;
    var options = el.options;
    var id = options[index].value;
    ajaxFunction("viewclient", id, "cp-supplimentary");
}

function showEnqInputs(el){
	var pumps = ["surface", "submerse"];
    var index = el.selectedIndex;
    var option = el.options;
	var ptype = option[index].value;
	var i, j;

    var rows = document.getElementsByClassName(ptype+"-row");
    for(i=0;i<rows.length;i++){
        rows[i].style.display = '';
    }
    var els = document.getElementsByClassName(ptype+"-row-el");
    for(i=0;i<els.length;i++){
        els[i].disabled = false;
	}
	for(i=0;i<pumps.length;i++){
		if(pumps[i] != ptype){
			rows = document.getElementsByClassName(pumps[i]+"-row");
			for(j=0;j<rows.length;j++){
				rows[j].style.display = 'none';
			}
			els = document.getElementsByClassName(pumps[i]+"-row-el");
			for(j=0;j<els.length;j++){
				els[j].disabled = true;
			}
		}
	}
}
function deleteTask(task, taskid){
	var confirmation = confirm("Please confirm deletion of Task : '"+task+"'");
	if(confirmation){
		//preventDefault();
		var formid = 'task-delete-form-'+taskid;
		var formel = document.getElementById(formid);
		formel.submit();
	}
}

function deleteEnquiry(enq, enqid){
	var confirmation = confirm("Please confirm deletion of Enquiry : '"+enq+"'");
	if(confirmation){
		//preventDefault();
		var formid = 'enquiry-delete-form-'+enqid;
		var formel = document.getElementById(formid);
		formel.submit();
	}
}

function newClientValidation(e, form){
    e.preventDefault();
    var postqstring = getQueryString(form.id);
    clearErrorFormatting(form.id); // Clear any previous error
	ajaxFunction('newClientValidation', postqstring, 'client-creator');
}

function showClientContact(el){
    var x = el.options;
    var y = el.selectedIndex;
    var index = x[y].value;
    var client_id = index;

    ajaxFunction('viewClientContacts', client_id, 'client-contacts');
}

function selectClientContact(el){
	if(el.checked){
		contacts[el.dataset.index]['selected'] = 1;
	}
	else {
		contacts[el.dataset.index]['selected'] = 0;
	}
	console.log(contacts);
}

function createProject(e, form){
	e.preventDefault();
	var i, c = [], count=0, formdat;
	for(i=0; i<contacts.length;i++){
		if(contacts[i].selected == 1)
		c[count++] = contacts[i];
	}
	if(c.length == 0){
		alert('No contact selected');
		return;
	}
	else {
		formdat = getQString(form.id, 'cpinput');
		formdat['contacts'] = c;
		formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');;
		ajaxFunction('createClient', JSON.stringify(formdat) , '');
	}
		
}