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
				if(instruction == "userWithSalary"){
					var crResponse = JSON.parse(ajaxRequest.responseText);
					if(crResponse.result.status == 'failed'){
						errorBagProcessing(crResponse.result.messages);
						console.log(crResponse);
					}
					else {
						console.log(crResponse);
						location.href = "/users/";
					}
					return;
				}
				if(instruction == "usereditWithSalary"){
					var crResponse = JSON.parse(ajaxRequest.responseText);
					if(crResponse.result.status == 'failed'){
						errorBagProcessing(crResponse.result.messages);
						console.log(crResponse);
					}
					else {
						if(crResponse.result.type == 'admin')
						{
							console.log(crResponse);
							location.href = "/users/";
						}
						else{
							location.href = "/home/";
						}
						
					}
					return;
				}
				var ajaxDisplay = document.getElementById(divid);
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			}
			else if(ajaxRequest.readyState == 4 && ajaxRequest.status == 419){
				var ajaxDisplay = document.getElementById("app");
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
		if(instruction == "viewuserreports"){
			ajaxRequest.open("GET", "/user/reports", true);
			ajaxRequest.send();
		}
		if(instruction == "userWithSalary"){
			ajaxRequest.open("POST", "/users", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
		if(instruction == "usereditWithSalary"){
			var salary_id = document.getElementById("sal_id").value;
			ajaxRequest.open("POST", "/users/"+salary_id, true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
			console.log(execute_id);
		}
}

function deleteUser(user, userid){
	var confirmation = confirm("Please confirm deletion of User : '"+user+"'");
	if(confirmation){
		var uDeleteForm = document.createElement("form");
		uDeleteForm.target = "";
		uDeleteForm.method = "POST";
		uDeleteForm.action = "/users/"+userid;

		var methodInput = document.createElement("input");
		methodInput.type = "hidden";
		methodInput.name = "_method";
		methodInput.value = "delete";
		uDeleteForm.appendChild(methodInput);

		var csrfInput = document.createElement("input");
		csrfInput.type = "hidden";
		csrfInput.name = "_token";
		csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');;
		uDeleteForm.appendChild(csrfInput);
		document.body.appendChild(uDeleteForm);
		uDeleteForm.submit();	
	}
}

function deleteReport(reportid){
	var confirmation = confirm("Please confirm deletion of report");
	if(confirmation){
		var rDeleteForm = document.createElement("form");
		rDeleteForm.target = "";
		rDeleteForm.method = "POST";
		rDeleteForm.action = "/reports/"+reportid;

		var methodInput = document.createElement("input");
		methodInput.type = "hidden";
		methodInput.name = "_method";
		methodInput.value = "delete";
		rDeleteForm.appendChild(methodInput);

		var csrfInput = document.createElement("input");
		csrfInput.type = "hidden";
		csrfInput.name = "_token";
		csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		rDeleteForm.appendChild(csrfInput);
		document.body.appendChild(rDeleteForm);
		rDeleteForm.submit();
	}
}
function deactivateUser(user, userid){
	var confirmation = confirm("Please confirm deactivation/reactivation of User : '"+user+"'");
	
	if(confirmation){
		var rDeactivate = document.createElement("form");
		rDeactivate.target = "";
		rDeactivate.method = "POST";
		rDeactivate.action = "/user/deactivate/"+userid;

		var csrfInput = document.createElement("input");
		csrfInput.type = "hidden";
		csrfInput.name = "_token";
		csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		rDeactivate.appendChild(csrfInput);
		document.body.appendChild(rDeactivate);

		rDeactivate.submit();
		
	}
}
function updateTask(taskid)
{
	var checkedstatus = document.getElementById('done-'+taskid);
	var submitdate = document.getElementById('done-date-'+taskid).value;

	if (checkedstatus.checked)
	{
		if(submitdate=='')
		{
			alert('No date submitted');
			checkedstatus.checked = false;
		}
		else 
		{
			var today = new Date();
			var formsubmission = document.getElementById('done-form-'+taskid);
			var assigneddate = document.getElementById('task_date_assigned').value;

			submit = new Date(submitdate);
			assigned = new Date(assigneddate);

			if(submit<assigned)
			{
				alert('cannot submit before assigned date');
				checkedstatus.checked = false;
			} 
			else if (submit>today)
			{
				alert('cannot submit after current date');
				checkedstatus.checked = false;
			}
			else
			{
				checkedstatus.checked = true;
				formsubmission.submit();
			}
		}					
	}
	else 
	{//not checked
		if(submitdate=='')
		{
			alert('No date submitted');
			checkedstatus.checked = false;
			
		}
		else 
		{
			var today = new Date();
			// console.log(today);
			var formsubmission = document.getElementById('done-form-'+taskid);
			var assigneddate = document.getElementById('task_date_assigned').value;

			submit = new Date(submitdate);
			assigned = new Date(assigneddate);			

			if(submit<assigned)
			{
				alert('cannot submit before assigned date');
				checkedstatus.checked = true;
			} 
			else if (submit>today)
			{
				alert('cannot submit after current date');
				checkedstatus.checked = true;
				
			}
			else
			{
				checkedstatus.checked = false;
				formsubmission.submit();
			}
		}	
	}
}

function withSalary(e, form){
	e.preventDefault();

	var formdat = {};
	formdat['salary'] = getQString(form.id, 'salary');
	formdat['useraccount'] = getQString(form.id, 'useraccount');
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	clearErrorFormatting(form.id);
	ajaxFunction('userWithSalary', JSON.stringify(formdat) , '');
	console.log(formdat);
}
function editwithSalary(e, form){
	e.preventDefault();

	var formdat = {};
	formdat['_method'] = "PUT";
	formdat['salary'] = getQString(form.id, 'salary');
	formdat['useraccount'] = getQString(form.id, 'useraccount');
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

	clearErrorFormatting(form.id);
	ajaxFunction('usereditWithSalary', JSON.stringify(formdat) , '');
	console.log(formdat);
}
