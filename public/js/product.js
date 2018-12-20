var root = [];

class Category {
    constructor(name, level, index, subcategory) {
        this.name = name;
		this.level = level;
		this.index = index;
		this.subcategory = subcategory;
	}
}

function initAddCat(el){
	var il = el.dataset; //Index + Level = il
	var level = il.level;
	var index = il.index;

	var cat = addCategory(level, index);
	if(level>1)
		traverseIn(level, index, cat);
	else
		root[index] = cat;
	
	el.dataset.index = parseInt(index) + 1;
}

function traverseIn(level, index, cat){
	var tempEl, node = root;
	for(i=1;i<level;i++){
		tempEl = document.getElementById("p_cat_"+i+"_list");
		nodeIndex = tempEl.selectedIndex;
		node = node[nodeIndex].subcategory;
	}
	node[index] = cat;
	console.log(root);
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
	let newCategory = new Category(newCategoryName, level, index, newSubCategory);
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

    ajaxFunction('addSubCat', JSON.stringify(formdat), 'config_'+level);
}

function configSubCat(el){
	var x = el.dataset;
	var y = el.selectedIndex;
	var level = x.level;
	var index = x.index;
	if(el[y].value == 'subcategory')
		addSubCategory(level, index);
	
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
}