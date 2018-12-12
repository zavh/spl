var contacts = [];
var preload = false;
var preloaded_contacts = [];
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
						var view = ccResponse.data.view;
						ajaxDisplay.innerHTML = view;
						for(i=0;i<contacts.length;i++){
							contacts[i]['selected'] = 0;
						}
						if(preload) renderContact();
						return;
					}
					if(instruction == "createProject"){
						cpResponse = JSON.parse(ajaxRequest.responseText);
						if(cpResponse.response.status == 'failed'){
							errorBagProcessing(cpResponse.response.messages);
						}
						else {
							var pid = cpResponse.response.project_id;
							location.href = "/projects/"+pid;
						}
						return;
					}
					if(instruction == "createTask"){
						ctResponse = JSON.parse(ajaxRequest.responseText);
						if(ctResponse.result.status == 'failed'){
							errorBagProcessing(ctResponse.result.messages);
						}
						else if(ctResponse.result.status == 'success'){
							var pid = ctResponse.result.project_id;
							ajaxFunction('showTasks', pid , 'taskdiv');
							var new_alloc = ctResponse.result.new_alloc;
							renderAlloc(new_alloc);
							renderTaskCount(pid);
						}
						return;
					}
					if(instruction == "editTask"){
						etResponse = JSON.parse(ajaxRequest.responseText);
						if(etResponse.result.status == 'failed'){
							errorBagProcessing(etResponse.result.messages);
						}
						else {
							var pid = etResponse.result.project_id;
							ajaxFunction('showTasks', pid , 'taskdiv');
							var new_alloc = etResponse.result.new_alloc;
							renderAlloc(new_alloc);
							renderProjectTimeline(pid);
						}
						return;
					}
				    ajaxDisplay.innerHTML = ajaxRequest.responseText;
				}
				else if(ajaxRequest.readyState == 4 && ajaxRequest.status == 419){
					var ajaxDisplay = document.getElementById("app");
					ajaxDisplay.innerHTML = ajaxRequest.responseText;
					//window.location.href = '/login';
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
		if(instruction == "renderTimeline"){
			ajaxRequest.open("GET", "/project/timeline/"+execute_id, true);
			ajaxRequest.send();
		}
		if(instruction == "newClientValidation"){
			ajaxRequest.open("POST", "/clients/validateonly/", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
		if(instruction == "createProject"){
			ajaxRequest.open("POST", "/projects", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
		if(instruction == "createTask"){
			ajaxRequest.open("POST", "/tasks", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
		if(instruction == "editTask"){
			var task_id = document.getElementById("task_id").value;
			ajaxRequest.open("POST", "/tasks/"+task_id, true);
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
		submitDeleteForm('tasks', taskid);
	}
}
function deleteEnquiry(enqid){
	var confirmation = confirm("Please confirm deletion of report");
	if(confirmation){
		submitDeleteForm('enquiries', enqid);
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
	clearErrorFormatting(form.id);
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
		ajaxFunction('createProject', JSON.stringify(formdat) , '');
	}		
}

function createTask(e, form){
	e.preventDefault();
	clearErrorFormatting(form.id);
	var formdat = getQString(form.id, 'ctinput');

	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	ajaxFunction('createTask', JSON.stringify(formdat) , 'taskdiv');
}

function editTask(e, form){
	e.preventDefault();
	clearErrorFormatting(form.id);
	var formdat = getQString(form.id, 'etinput');
	
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['_method'] = "PUT";
	console.log(JSON.stringify(formdat));
	ajaxFunction('editTask', JSON.stringify(formdat) , 'taskdiv');
}

function newClientValidation(e, form){
	e.preventDefault();
	clearErrorFormatting(form.id);
	var formdat = getQString(form.id, 'form-control');

	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	console.log(formdat);
	//ajaxFunction('editTask', JSON.stringify(formdat) , 'taskdiv');
}

function renderAlloc(alloc){
	var t = document.getElementById('al-at-title');
	var v = document.getElementById('al-at-value');

	t.innerHTML = alloc;
	v.style.width = alloc+'%';

	if(alloc > 80){
		v.classList.remove('bg-danger');
		v.classList.add('bg-success');
	}
	else {
		v.classList.remove('bg-success');
		v.classList.add('bg-danger');
	}
}

function renderContact(){
	var i, j, x = document.getElementsByName('tempcontact');
	for(i=0;i<contacts.length;i++){
		for(j=0;j<preloaded_contacts.length;j++){
			if(contacts[i]['id'] == preloaded_contacts[j]){
				contacts[i]['selected'] = 1;
				if(x[i].dataset['id']==preloaded_contacts[j])
					x[i].checked = true;
			}
		}
	}
}

function renderTaskCount(project_id){
	var x = document.getElementById('taskcount');
	var current_task_count = parseInt(x.innerText) + 1;
	x.innerHTML = current_task_count;
	renderProjectTimeline(project_id);
}

function renderProjectTimeline(project_id){
	ajaxFunction('renderTimeline',project_id,'projecttimeline');
}