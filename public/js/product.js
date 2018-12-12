var category = [];
function addProduct(){
    var cat_count = category.length;
    var cat_list = document.getElementById('p_cat_list');
    category[cat_count] = document.getElementById('new_cat').value;

    var catInput = document.createElement("option"); //Creating New Option
    catInput.value = category[cat_count]; //Giving new Option it's value

    var option_text = document.createTextNode(category[cat_count]);//Setting the Option's Text
    catInput.appendChild(option_text);//Adding the text to options node
    var att = document.createAttribute("selected");
    catInput.setAttributeNode(att);   
    cat_list.appendChild(catInput);//adding the Option to Select node
    addChildCategory();
}

function addChildCategory(){
    ajaxFunction('addChildCategory', '', 'subcat');
}


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
				    ajaxDisplay.innerHTML = ajaxRequest.responseText;
				}
				else if(ajaxRequest.readyState == 4 && ajaxRequest.status == 419){
					var ajaxDisplay = document.getElementById("app");
					ajaxDisplay.innerHTML = ajaxRequest.responseText;
				}
	    } 

		if(instruction == "addChildCategory"){
			ajaxRequest.open("GET", "/product/addchild", true);
			ajaxRequest.send();
		}
		if(instruction == "newClientValidation"){
			ajaxRequest.open("POST", "/clients/validateonly/", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
}