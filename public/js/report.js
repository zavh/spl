var report_data = {};
var client_data = [];
var client_index;
var stage2changed = false;
var stage2save = true;
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
                /* Client List initiated when the report page opens up.
                    cleint_data is populated only with clients, no contacts 
                    added to client_data global variable.
                */
                if(instruction == 'viewClientList'){
                    var ccr = JSON.parse(ajaxRequest.responseText); // ccr = ClientsControllerResponse
                    client_data = JSON.parse(ccr.response.clients);
                    ajaxDisplay.innerHTML = ccr.response.view;
                    return;
                }
                if(instruction == 'viewClientContacts'){
                    var cccr = JSON.parse(ajaxRequest.responseText); // ccclr = ClientContactControllerResponse
                    var x = JSON.parse(cccr.data.contacts);
                    
                    for(i=0;i<x.length;i++){
                        x[i].dbflag = 1;
                        x[i].selected = 0;
                    }
                    for(i=0;i<client_data.length;i++){
                        if(client_data[i].id == execute_id){
                            client_data[i].contacts = x;
                            client_data[i].contactview = cccr.data.view;
                            ajaxDisplay.innerHTML = client_data[i].contactview;
                            break;
                        }
                    }
                    return;
                }
                if(instruction == "newClientValidation"){
                    var vResponse = JSON.parse(ajaxRequest.responseText);
                    if(vResponse.result.status == 'success'){
                        document.getElementById('client-chooser').innerHTML = vResponse.result.view;
                        
                        client_data = JSON.parse(vResponse.result.clients);
                        var i = client_data.length;
                        client_index = i-1;
                        client_data[client_index].contacts = JSON.parse(vResponse.result.contacts);
                        client_data[client_index].contacts[0].selected = 1;
                        client_data[client_index].contacts[0].dbflag = 0;
                        client_data[client_index].contactview = vResponse.result.contactview;
                        renderContact();
                        renderReport();
                        var x = document.getElementsByName('tempcontact');
                        x[0].checked = true;
                    }
                    else {
                        vResponse.result.message.forEach(formErrorProcessing);
                    }
                    return;
                }
                if(instruction == "saveStageOne"){
                    var sResponse = JSON.parse(ajaxRequest.responseText);
                    if(sResponse.result.status == 'success'){                        
                        document.getElementById('stage_2').innerHTML = sResponse.result.view;
                        return;
                    }
                }
                if(instruction == "clientDetails"){
                    var cResponse = JSON.parse(ajaxRequest.responseText);
                    var data = JSON.parse(cResponse.result.report_data);
                    client_index = data.client_index;
                    client_data[client_index] = data.client_data;
                    //renderContact();
                    renderReport();
                    console.log(data);
                    return;
                }
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
            }
	    } 

		if(instruction == "viewClientList"){
			ajaxRequest.open("GET", "/clients/listing/", true);
            ajaxRequest.send();
        }

		if(instruction == "viewClientContacts"){
			ajaxRequest.open("GET", "/clientcontacts/listing/"+execute_id, true);
			ajaxRequest.send();
        }

		if(instruction == "newClientValidation"){
			ajaxRequest.open("POST", "/clients/validateonly/", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }

        if(instruction == "saveStageOne"){
			ajaxRequest.open("POST", "/reports", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
		if(instruction == "clientDetails"){
			ajaxRequest.open("GET", "/report/clientdetails/"+execute_id, true);
			ajaxRequest.send();
        }        
}

function showClientContact(el){
    var x = el.options;
    var y = el.selectedIndex;
    var index = x[y].value;
    var client_id = index;
    
    if(client_data[y-1].contacts == null){
        ajaxFunction('viewClientContacts', client_id, 'client-contacts');
    }
    if(client_index != null){ //Remove previous selection states
        var i;
        for(i=0;i<client_data[client_index].contacts.length;i++){
            client_data[client_index].contacts[i].selected = 0;
        }
        var parent_node = document.getElementById("contact-details");
        removeContact(parent_node);
        document.getElementById("contact-row").style.display = 'none';
    }
    client_index = y-1;
    renderContact();
    renderReport();
}

function selectClientContact(el){
    var contact_index = el.dataset.index;
    if(el.checked)
        client_data[client_index].contacts[contact_index].selected = 1;
    else
        client_data[client_index].contacts[contact_index].selected = 0;
    renderReport()
}

function renderContact(){
    if(client_data[client_index].contactview == null)
        document.getElementById('client-contacts').innerHTML = 'Generating Contact List';
    else document.getElementById('client-contacts').innerHTML = client_data[client_index].contactview;
        document.getElementById('client-contacts').style.display = '';
}

function renderReport(){
    //Displaying Customer Name and Address
    document.getElementById('client-name').innerHTML = client_data[client_index].organization;
    document.getElementById('client-address').innerHTML = client_data[client_index].address;
    document.getElementById('details-row').style.display = '';

    //Displaying Customer Background
    
    document.getElementById('background-details').innerHTML = client_data[client_index].background;
    document.getElementById('background-row').style.display = '';

    if(client_data[client_index].contacts != null){
        var i;
        var num_contacts = client_data[client_index].contacts.length;
        if(num_contacts>0){
            var parent_node = document.getElementById("contact-details");
            removeContact(parent_node);
    
            var select_flag = 0, nodecontent, obj;
            for(i=0;i<num_contacts;i++){
                if(client_data[client_index].contacts[i].selected == 1){
                    select_flag++;
                    obj = client_data[client_index].contacts[i];
                    nodecontent = 'Name : ' + obj.name;
                    if(select_flag>1)
                        addContactElements(parent_node, nodecontent, {class:'border-bottom border-success mt-2'});
                    else 
                    addContactElements(parent_node, nodecontent, {class:'border-bottom border-success'});
                    nodecontent = 'Designation : '+obj.designation;
                    addContactElements(parent_node, nodecontent,{class:'border-bottom border-success'});
                    nodecontent = 'Phone : ' + obj.contact;
                    addContactElements(parent_node, nodecontent,{class:'none'});
                }
            }
            console.log(select_flag);
            if(select_flag>0){
                document.getElementById("contact-row").style.display = '';
                document.getElementById("step1-complete").style.display = '';
            }
            else{ 
                document.getElementById("contact-row").style.display = 'none';
                document.getElementById("step1-complete").style.display = 'none';
            }
        }
    }
    else {
        document.getElementById("step1-complete").style.display = 'none';
    }
}

function removeContact(cel){ //container element = cel
    while (cel.firstChild) {
        cel.removeChild(cel.firstChild);
    }
}

function addContactElements(parent_node, nodecontent,attObj){
    var el = document.createElement("div");
    el.setAttribute("class", attObj.class);
    var node = document.createTextNode(nodecontent);
    el.appendChild(node);
    parent_node.appendChild(el);
}

function newClientValidation(e, form){
    e.preventDefault();
    var postqstring = getQueryString(form.id);
    //console.log(postqstring);
    clearErrorFormatting(form.id); // Clear any previous error
	ajaxFunction('newClientValidation', postqstring, 'client-creator');
}

function saveStage(stage){
    if(stage == 1){
        var token = document.getElementsByName("_token");
        report_data['client_data'] =client_data[client_index];
        report_data['stage'] = stage;
        report_data['client_index'] = client_index;
        report_data['_token'] = token[0].value;
        var qstring = JSON.stringify(report_data);
        ajaxFunction('saveStageOne', qstring, 'stage_2');
        document.getElementById('stage_1').style.display = 'none';
        console.log(qstring);
    }
}

function stage2State(){
    stage2changed = true;
    stage2save = false;
}

function backValidation(){
    if(stage2changed && !stage2save){
        var saveStage = confirm("Your Input will be lost. Would you like to Save?");
        if(saveStage){
        }
        else{
            backToStageOne();
        }
    }
    else 
        backToStageOne();
}

function backToStageOne(){
    stage2changed = false;
    stage2save = true;
    document.getElementById("stage_2").innerHTML='';
    document.getElementById('stage_1').style.display = '';
}