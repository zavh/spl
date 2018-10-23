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
}

function getClient(el){
    var index = el.selectedIndex;
    var options = el.options;
    var id = options[index].value;
    ajaxFunction("viewclient", id, "cp-supplimentary");
}