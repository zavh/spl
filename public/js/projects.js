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

		if(instruction == "viewclient"){
			document.getElementById("cp-supplimentary").style.display='';
			ajaxRequest.open("GET", "/clients/"+ execute_id, true);
			ajaxRequest.send();
        }
		if(instruction == "showCreateClient"){
            document.getElementById("cp-supplimentary").style.display='';		
			ajaxRequest.open("GET", "/clients/create", true);
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
			console.log('exeucute_id', execute_id);
			ajaxRequest.open("GET", "/tasks/"+execute_id+"/edit", true);
			ajaxRequest.send();
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