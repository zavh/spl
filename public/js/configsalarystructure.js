var root = [];

class Category {
    constructor(param_name, param_uf_name, value) {
        this.param_name = param_name;
		this.param_uf_name = param_uf_name;
        this.value = value;
        this.deleted = false;
	}
}

function addParam(el){
    var param_uf_name = document.getElementById("param_name").value.trim();
    if(param_uf_name == ''){
        alert('Empty parameter name not supported');
        return;
    }

    param_name = getParamName(param_uf_name);

    for(i=0;i<root.length;i++){
        if(root[i].param_name == param_name && root[i].deleted == false){
            alert("Parameter with name '"+param_uf_name+"' already exists");
            return;
        }
    }

    var count = root.length;
    root[count] = new Category(param_name, param_uf_name, 0);
    var formdat = prepareFormDat(root[count]);
    formdat['index'] = count;
    ajaxFunction("addParam", JSON.stringify(formdat), "param_"+el.dataset.index);

    el.dataset.index++;
    var ne = createDynEl("div", {id:"param_"+el.dataset.index, class:"form-group row"});
    document.getElementById("params_container").appendChild(ne);
    document.getElementById("param_name").value="";
}

function getParamName(param_uf_name){
    param_name = param_uf_name.replace(/[^\w\s]/gi, ' ');
    pn_arr = param_name.toLowerCase().split(" ");
    param_name = pn_arr.join("_");

    return param_name;
}

function prepareFormDat(dataset){
	var formdat = {};
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['data'] = dataset;
	return formdat;
}

function createDynEl(type, attval){
	var el = document.createElement(type);
	var a;
	for(var att in attval){
		a = document.createAttribute(att);
		a.value = attval[att];
		el.setAttributeNode(a);
	}
	return el;
}
function paramAction(el){
    var command = el[el.selectedIndex].value;
    var index = el.dataset.index;
    var paramid = el.dataset.paramname;
    var paramufname = el.dataset.paramufname;
    
    switch(command){
        case 'delete':
            var y = confirm("CAUTION : "+paramufname+" will be deleted");
            if(y){
                root[index].deleted = true;
                document.getElementById("param_"+index).remove();
            }
            break;
        case 'rename':
            var currentval = document.getElementById(paramid).value;
            if(root[index].param_uf_name == currentval){
                alert("No change in Parameter name");
                el[0].selected = true;
                break;
            }
            else{
                root[index].param_uf_name = currentval;
                root[index].param_name = getParamName(currentval);
                el[0].selected = true;
            }
            break;
    }
}

function submitConfig(){
    if(root.length == 0){
        alert('No configuration set');
        return;
    }
    else{
        var psuedoroot=[], count=0;
        for(i=0;i<root.length;i++){
            if(root[i].deleted == false)
                psuedoroot[count++]=root[i];
        }
    }
    var formdat = prepareFormDat(psuedoroot);
    ajaxFunction("saveConfig", JSON.stringify(formdat), '');
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
				if(instruction == "saveConfig"){
                    location.href = "/salarystructures";
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

		if(instruction == "viewuserreports"){
			ajaxRequest.open("GET", "/user/reports", true);
			ajaxRequest.send();
		}
		if(instruction == "addParam"){
			ajaxRequest.open("POST", "/salarystructures/addparam", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
        if(instruction == "saveConfig"){
			ajaxRequest.open("POST", "/salarystructures/saveconfig", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
}