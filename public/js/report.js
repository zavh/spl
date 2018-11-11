var report_data = [];
var client_data;
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
                if(instruction == 'viewClientList'){
                    var ccr = JSON.parse(ajaxRequest.responseText); // ccr = ClientsControllerResponse
                    client_data = JSON.parse(ccr.response.clients);
                    ajaxDisplay.innerHTML = ccr.response.view;
                    return;
                }
                if(instruction == 'viewClientContacts'){
                    var cccr = JSON.parse(ajaxRequest.responseText); // ccclr = ClientContactControllerResponse
                    var x = JSON.parse(cccr.data.contacts);
                    for(i=0;i<x;i++){
                        x[i].dbflag = 1;
                    }
                    for(i=0;i<client_data.length;i++){
                        if(client_data[i].id == x[0].client_id){
                            client_data[i].contacts = x;
                            client_data[i].contactview = cccr.data.view;
                            ajaxDisplay.innerHTML = client_data[i].contactview;
                            break;
                        }
                    }
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
}

function showClientContact(el){
    var x = el.options;
    var y = el.selectedIndex;
    var client_id = client_data[y-1].id;
    var org = client_data[y-1].organization;
    var address = client_data[y-1].address;
    if(client_data[y-1].contacts == null){
        ajaxFunction('viewClientContacts', client_id, 'client-contacts');
    }

    report_data['organization'] = org;
    report_data['address'] = address;
    renderReport(y-1);
}

function selectClientContat(el){
    alert(el.dataset.name);
}

function renderReport(index){
    if(report_data['organization'] != null){
        document.getElementById('client-name').innerHTML = report_data['organization'];
        document.getElementById('client-address').innerHTML = report_data['address'];
        document.getElementById('client-details').style.display = '';
        if(client_data[index].contactview == null)
        document.getElementById('client-contacts').innerHTML = 'Generating Contact List';
        else document.getElementById('client-contacts').innerHTML = client_data[index].contactview;
        document.getElementById('client-contacts').style.display = '';
    }
}

function newClientValidation(e, el){
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