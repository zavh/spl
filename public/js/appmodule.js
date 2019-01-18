var root = {};
var assigned = {};
var origconf = {};

class AppModule {
    constructor(modname, action) {
        this.modname = modname;
        this.action = action;
	}
}

class Action {
    constructor(func, name, url) {
        this.name = name;
		this.func = func;
        this.url = url;
        this.selected = true;
	}
}

function showAppConfig(el){
    var value = el[el.selectedIndex].value;
    document.getElementById('appConfigContainer').innerHTML='';
    if(value == 0){
        var formdat = prepareFormDat(0);
        ajaxFunction('defaultConfig', JSON.stringify(formdat) , '');
    }
    else {
        var formdat = prepareFormDat(value);
        ajaxFunction('deptConfig', JSON.stringify(formdat) , 'appConfigContainer');
    }
}

function assignModule(el){
    var name = el.value;
    if(el.checked){
        var formdat = prepareFormDat(root[name]);
        var ng = createDynEl("div", {id:"container_"+name});
        document.getElementById("appConfigContainer").appendChild(ng);
        ajaxFunction('addMod', JSON.stringify(formdat) , "container_"+name);
        el.disabled = true;
        assigned[name] = root[name];
    }
    else {
        
    }
    if(origconf != assigned){
        console.log('configuration changed');
    }
}

function removeModule(key){
    delete assigned[key];
    document.getElementById('av-'+key).checked = false;
    document.getElementById('av-'+key).disabled = false;
    document.getElementById('container_'+key).remove();
}

function changeConfig(){
    var dept = document.getElementById('inputGroupSelect');
    if(JSON.stringify(assigned) != JSON.stringify(origconf)){
        formdat = prepareFormDat(assigned);
        if(dept.value == 0){
            ajaxFunction('changeDefaultCfg', JSON.stringify(formdat) , "appConfigContainer");
        }
        else{
            formdat['department_id'] = dept.value;
            ajaxFunction('changeDeptCfg', JSON.stringify(formdat) , "appConfigContainer");
        }
    }
    console.log(dept[dept.selectedIndex].value);
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

function renderConfig(ec){

    var availables = document.getElementsByClassName('avmodules');
    for(i=0;i<availables.length;i++){
        availables[i].disabled = false;
        availables[i].checked = false;
    }        
    if(ec != null){
        var aarr, aaction;
        assigned = {};
        origconf = {};
        for (var key in ec) {
            aarr = ec[key].action;
            aaction = [];
            for(i=0;i<aarr.length;i++){
               aaction[i] = new Action(aarr[i].func, aarr[i].name, aarr[i].url);
            }
            origconf[key] = new AppModule(key, aaction);
            assigned[key] = new AppModule(key, aaction);
            document.getElementById('av-'+key).checked = true;
            document.getElementById('av-'+key).disabled = true;
            var formdat = prepareFormDat(origconf[key]);
            var ng = createDynEl("div", {id:"container_"+key});
            document.getElementById("appConfigContainer").appendChild(ng);
            ajaxFunction('addMod', JSON.stringify(formdat) , "container_"+key);
        }
    }
}

function prepareFormDat(dataset){
	var formdat = {};
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['data'] = dataset;
	return formdat;
}

function equalize(){
    origconf = {};
    for (var key in assigned){
        origconf[key] = assigned[key];
    }
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
				if(instruction == "defaultConfig"){
                    var cr = JSON.parse(ajaxRequest.responseText);
                    if(cr.response.result == 'nc'){
                        ajaxDisplay = document.getElementById('configmsg');
                        ajaxDisplay.innerHTML = "No Configuration found!";
                        renderConfig(null);
                    }
                    else {
                        renderConfig(cr.response.config);
                    }
                    return;
                }
                if(instruction == "deptConfig"){
                    var dc = JSON.parse(ajaxRequest.responseText);
                    if(dc.response.result == 'nc'){
                        ajaxDisplay = document.getElementById('configmsg');
                        ajaxDisplay.innerHTML = "No Configuration found!";
                        renderConfig(null);
                    }
                    else {
                        renderConfig(dc.response.config);
                    }
                    return;
                }
                if(instruction == "changeDefaultCfg"){
                    var cd = JSON.parse(ajaxRequest.responseText);
                    ajaxDisplay = document.getElementById('configmsg');
                    ajaxDisplay.innerHTML = cd.response.message;
                    equalize();
                    return;
                }
                if(instruction == "changeDeptCfg"){
                    var cd = JSON.parse(ajaxRequest.responseText);
                    ajaxDisplay = document.getElementById('configmsg');
                    ajaxDisplay.innerHTML = cd.response.message;
                    equalize();
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
		if(instruction == "addMod"){
			ajaxRequest.open("POST", "/appmodules/addmod", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
        if(instruction == "defaultConfig"){
			ajaxRequest.open("POST", "/appmodules/defaultconfig", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
        if(instruction == "changeDefaultCfg"){
			ajaxRequest.open("POST", "/appmodules/changedefaultcfg", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
        if(instruction == "deptConfig"){
			ajaxRequest.open("POST", "/appmodules/deptconfig", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
        if(instruction == "changeDeptCfg"){
			ajaxRequest.open("POST", "/appmodules/changedeptcfg", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
        }
}