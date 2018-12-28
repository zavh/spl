var root = [];

class Category {
    constructor(name, level, index, subcategory, params, groups) {
        this.name = name;
		this.level = level;
		this.index = index;
		this.subcategory = subcategory;
		this.params = params;
		this.groups = groups;
	}
}

function initAddCat(el){
	var il = el.dataset; //Index + Level = il
	var level = il.level;
	var index = il.index;

	var cat = addCategory(level, index);
	if(level>1){
		var node = traverseIn(level);
		node[index] = cat;
	}
	else
		root[index] = cat;
	
	el.dataset.index = parseInt(index) + 1;
	renderPreview();
}

function traverseIn(level){
	var tempEl, node = root;
	for(i=1;i<level;i++){
		tempEl = document.getElementById("p_cat_"+i+"_list");
		nodeIndex = tempEl.selectedIndex - 1;
		node = node[nodeIndex].subcategory;
	}
	return node;
}

function addCategory(level, index){
	/* First, create the node*/
	var cat_list = document.getElementById('p_cat_'+level+'_list');
	for(i=0;i<cat_list.length;i++){
		cat_list[i].removeAttribute("selected");
	}
	var newCategoryName = document.getElementById('p_cat_'+level+'_input').value;
	document.getElementById('p_cat_'+level+'_input').value = '';
	var newSubCategory = [];
	var params = [];
	var groups = {};
	let newCategory = new Category(newCategoryName, level, index, newSubCategory, params, groups);
	/* Node creation complete*/

    var catInput = document.createElement("option"); //Creating New Option
    catInput.value = index; //Giving new Option it's value
    var option_text = document.createTextNode(newCategoryName);//Setting the Option's Text
    catInput.appendChild(option_text);//Adding the text to options node
    var att = document.createAttribute("selected");
    catInput.setAttributeNode(att);
    cat_list.appendChild(catInput);//adding the Option to Select node
	addChild(newCategory);
	return newCategory;
}

function addChild(cat){
	var formdat = {};
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['product'] = cat;
    ajaxFunction('addChild', JSON.stringify(formdat), 'category_'+cat.level);
}

function addSubCategory(level, index){
	var formdat = {};
	
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['level'] = level;
	formdat['index'] = index;
	formdat['subcat'] = traverseIn(level);
    ajaxFunction('addSubCat', JSON.stringify(formdat), 'config_'+level);
}

function addParam(level, option){
	var cat = traverseIn(level-1);
	var li = document.getElementById("p_cat_"+(level-1)+"_list").selectedIndex - 1;
	var formdat = {};
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['level'] = level;
	formdat['params'] = cat[li].params;

	if(option == 'params')
		ajaxFunction('addParam', JSON.stringify(formdat), 'config_'+level);
	
	if(option == 'checks'){
		if(cat[li].groups.checks == null)
			cat[li].groups.checks = [];
		
		formdat['data'] = cat[li].groups.checks;
		formdat['type'] = 'check';
		ajaxFunction('showAddGroup', JSON.stringify(formdat), 'config_'+level);
	}
}

function configSubCat(el){
	var x = el.dataset;
	var y = el.selectedIndex;
	var level = x.level;
	var index = x.index;

	if(el[y].value == 'subcategory')
		addSubCategory(level, index);
	if(el[y].value == 'param')
		addParam(level, 'params');
	if(el[y].value == 'checks'){
		addParam(level, 'checks');
	}		
}

function addParamFields(el){
	el.dataset.index++;
	var level = el.dataset.level;
	var index = el.dataset.index;
	var selectOptions = {text:'Text', number:'Number', date:'Date', hidden:'Hidden', delete:'Delete This'};

	var lvl1 = createDynEl('div', {class:"form-group row mb-1", id:"p_param_"+level+"_"+index});
	var lvl2 = createDynEl('div', {class:"input-group input-group-sm col-md-12"});
	var lvl2c1 = createDynEl('div', {class:"input-group-prepend"});
	var lvl2c1span = createDynEl('span', {class:"input-group-text", style:"width:120px"});
	var lvl2c3 = createDynEl('div', {class:"input-group-append input-group-sm"});
	var lvl2c2 = createDynEl('input', {name:'p_param_'+level+'_input',id:'p_param_'+level+'_'+index+'_input', type:'text',
									class:"cpinput form-control",'data-level':level, 'data-index':index});
	var lvl2c3select = prepareSelect(selectOptions, el.dataset.level, el.dataset.index);
	lvl2c1span = addTextNode(lvl2c1span, 'Parameter Name');
	lvl2c1.appendChild(lvl2c1span);
	lvl2c3.appendChild(lvl2c3select);
	lvl2.appendChild(lvl2c1);
	lvl2.appendChild(lvl2c2);
	lvl2.appendChild(lvl2c3);
	lvl1.appendChild(lvl2);

	document.getElementById("param_config_"+level).appendChild(lvl1);
}

function prepareSelect(op, level, index){
	var o;
	var s = createDynEl("select", {id:"p_param_"+level+"_"+index+"_type", class:"form-control", onchange:"deleteParam(this)", 'data-level':level,'data-index':index});
	for(var k in op){
		o = document.createElement("option");
		o.value = k;
		o = addTextNode(o, op[k]);
		s.appendChild(o);
	}
	return s;
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

function addTextNode(el, txt){
	var tn = document.createTextNode(txt);
	el.appendChild(tn);
	return el;
}

function deleteParam(el){
	var y = el.selectedIndex;
	var v = el[y].value;
	var level = el.dataset.level;
	var index = el.dataset.index;
	if(v == 'delete'){
		var cf = confirm('Confirm Deletion of this Parameter');
		if(cf){
			var node = document.getElementById("p_param_"+level+"_"+index);
			node.remove();
			registerParamFields(el);
		}
	}
}

function registerParamFields(el){
	var level = el.dataset.level;
	var f = document.getElementsByName("p_param_"+level+"_input");
	var params = [], type, index;
	if(f.length<1){
		alert('No Parameters defined');
		return;
	}
	for(i=0;i<f.length;i++){
		if(f[i].value.trim() == ''){
			alert('Empty Parameter Name Field');
			return;
		}
		index = f[i].dataset.index;
		type = document.getElementById('p_param_'+level+'_'+index+'_type');
		
		params[i] = {};
		params[i]['title'] = f[i].value;
		params[i]['type'] = type[type.selectedIndex].value;
	}
	var node = traverseIn(level-1);
	var li = document.getElementById("p_cat_"+(level-1)+"_list").selectedIndex - 1;
	node[li].params = params;
	renderPreview();
}

function showCat(el){
	var level = el.dataset.level;
	var cat = traverseIn(level);
	var li = document.getElementById("p_cat_"+(level)+"_list").selectedIndex - 1;
	addChild(cat[li]);
	if(level == 1)
		renderPreview();
}

function renderPreview(){
	var li = document.getElementById("p_cat_1_list").selectedIndex - 1;
	var formdat = prepareFormDat(root[li]);

	ajaxFunction('productPreview', JSON.stringify(formdat), 'preview');
}

function addGroup(el){
	var type = el.dataset.type;
	var index = el.dataset.index;
	var level = el.dataset.level;
	var grpname = document.getElementById("p_"+type+"_input").value.trim();
	
	if(grpname==''){
		alert("Group Name is empty");
		return;
	}
	else {
		var node = traverseIn(level-1);
		var ni = document.getElementById("p_cat_"+(level - 1)+"_list").selectedIndex - 1;
		var nodegrp = node[ni].groups;
		var gi = nodegrp.length;
		console.log(gi);
		var formdat = {};
		formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		formdat['data'] = el.dataset;
		formdat['name'] = grpname;
		ajaxFunction('addGroup', JSON.stringify(formdat), type+'_'+level+'_'+index);
		
		el.dataset.index++;
		var ng = createDynEl("div", {id:type+"_"+level+"_"+el.dataset.index, name:type+"_"+level});
		document.getElementById("group_config").appendChild(ng);
	}
}

function addGroupElement(el){
	var formdat = prepareFormDat(el.dataset);
	var type = el.dataset.type;
	var level = el.dataset.level;
	var index = el.dataset.index;
	var grpindex = el.dataset.grpindex;
	ajaxFunction('addGroupEl', JSON.stringify(formdat), type+'_'+level+'_'+index+'_'+grpindex);
	el.dataset.grpindex++;
	var ne = createDynEl("div", {id:type+"_"+level+"_"+index+"_"+el.dataset.grpindex, class:"mx-1"});
	document.getElementById("el_"+type+"_"+level+"_"+index).appendChild(ne);
}

function registerGroup(el){
	var level = el.dataset.level;
	var type = el.dataset.type;
	var i, y, els, groups = [];
	var grps = document.getElementsByName(type+"_"+level);
	var numgrps = grps.length - 1;
	var node = traverseIn(level - 1);
	var ni = document.getElementById("p_cat_"+(level - 1)+"_list").selectedIndex - 1;
	
	for(i=0;i<numgrps;i++){
		groups[i] = {};
		groups[i]['type'] = type;
		groups[i]['name'] = document.getElementById("grp_name_"+type+"_"+level+"_"+i).value;
		groups[i]['data'] = [];
		els = document.getElementsByName("p_param_"+type+"_"+level+"_"+i+"_input");
		for(y=0;y<els.length;y++){
			groups[i]['data'][y] = {}
			groups[i]['data'][y]['name'] = els[y].value;
		}
		console.log("Node",node[ni].groups.checks);
		console.log("New Groups",groups);
		console.log("Tyle",type);
		if(type == 'check')
			node[ni].groups.checks = groups;
	}

	renderPreview();
}

function prepareFormDat(dataset){
	var formdat = {};
	formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	formdat['data'] = dataset;
	return formdat;
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

		if(instruction == "addChild"){
			ajaxRequest.open("POST", "/product/addchild", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "addSubCat"){
			ajaxRequest.open("POST", "/product/addsubcat", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "addParam"){
			ajaxRequest.open("POST", "/product/addparam", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "showAddGroup"){
			ajaxRequest.open("POST", "/product/showaddgroup", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "addGroup"){
			ajaxRequest.open("POST", "/product/addgroup", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "productPreview"){
			ajaxRequest.open("POST", "/product/preview", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}

		if(instruction == "addGroupEl"){
			ajaxRequest.open("POST", "/product/addgrpel", true);
			ajaxRequest.setRequestHeader("Content-type", "application/json");
			ajaxRequest.send(execute_id);
		}
}