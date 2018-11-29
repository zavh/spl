function getQueryString(formname){
    postqstring = '', formData = {};
    var typeInput = document.forms[formname].getElementsByTagName("input");
    var typeTextArea = document.forms[formname].getElementsByTagName("textarea");

    var formData = inputArrFactory(formData, typeInput);
    var formData = inputArrFactory(formData, typeTextArea);
    return JSON.stringify(formData);
}

function inputArrFactory(collection, nodelist){
    var i;
    for(i=0;i<nodelist.length;i++){
        ename = nodelist[i].name;
        evalue = nodelist[i].value;
        if(ename=="")
            continue;
        else{
            collection[ename] = evalue;
        } 
    }
    return collection;
}
function formErrorProcessing(item, index){
    var err = item.split("|");
	var span = document.getElementById(err[0]+'_error_span');
	var errmsg = document.getElementById(err[0]+'_error');
	var inp = document.getElementById(err[0]);
	inp.classList.add("is-invalid");
	span.style.display = 'block';
	errmsg.innerHTML += "&#9654;"+err[1]+"&nbsp;";
}

function clearErrorFormatting(name){
    var textels = document.forms[name].getElementsByTagName("input");
    errorClearFactory(textels);
    var tareaels = document.forms[name].getElementsByTagName("textarea");
    errorClearFactory(tareaels);    
}

function errorClearFactory(nodelist){
	for(i=0;i<nodelist.length;i++){
		ename = nodelist[i].name;
		espan = document.getElementById(ename+"_error_span");
		if(espan != null){
			espan.style.display = 'none';
			document.getElementById(ename).classList.remove("is-invalid");
			document.getElementById(ename+"_error").innerHTML='';
		}			
	}
}

function errorBagProcessing(messages){
    var span, errmsg, inp, i;
    for(var k in messages){
        if(messages.hasOwnProperty(k)){
            span = document.getElementById(k+'_error_span');
            errmsg = document.getElementById(k+'_error');
            inp = document.getElementById(k);
            // console.log(span);
            // console.log(errmsg);
            // console.log(inp);
            inp.classList.add("is-invalid");
            span.style.display = 'block';
            for(i=0;i<messages[k].length;i++){
                errmsg.innerHTML += "&#9654;"+messages[k][i]+"&nbsp;";
            }
        }
    }
}

function getQString(f, c){
    var inputs = document.forms[f].getElementsByClassName(c), i, result = {}, options, index=0, j;
    for(i=0;i<inputs.length;i++){
        if(inputs[i].hasAttribute("disabled"))
            continue;
        if(inputs[i].getAttribute("type") == 'radio' || inputs[i].getAttribute("type") == 'checkbox'){
            if(!inputs[i].checked)
                continue;
        }
        if(inputs[i].type == 'select-multiple'){
            options = inputs[i].options;
            result[inputs[i].name] = [];
            for(j=0;j<options.length;j++){
                if(options[j].selected){
                    result[inputs[i].name][index++] = options[j].value;
                }
            }  
        }
        else 
            result[inputs[i].name] = inputs[i].value;
    }
    return result;
}