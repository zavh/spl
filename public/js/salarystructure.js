var root = [];

class Fields {
        constructor(param_name, param_uf_name, value) {
                this.param_name = param_name;
                this.param_uf_name = param_uf_name;
                this.value = value;
        }
}
function ajaxFunction(instruction, execute_id, divid) {
        var ajaxRequest;  // The variable that makes Ajax possible!
        try {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
        } catch (e) {
                // Internet Explorer Browsers
                try {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                        try {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                                // Something went wrong
                                alert("Your browser broke!");
                                return false;
                        }
                }
        }
        // Create a function that will receive data sent from the server
        ajaxRequest.onreadystatechange = function () {
                if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
                        if(instruction == "createStructure"){
                                var ssr = JSON.parse(ajaxRequest.responseText);
                                if(ssr.result.status == 'failed'){
                                        errorBagProcessing(ssr.result.messages);
                                        return;
                                }
                                else if(ssr.result.status == 'success'){
                                        location.href = "/salarystructures";
                                        return;
                                }
                        }
                        if(instruction == "updateStructure"){
                                var ssr = JSON.parse(ajaxRequest.responseText);
                                if(ssr.result.status == 'failed'){
                                        errorBagProcessing(ssr.result.messages);
                                        return;
                                }
                                else if(ssr.result.status == 'success'){
                                        location.href = "/salarystructures";
                                        return;
                                }
                        }
                        var ajaxDisplay = document.getElementById(divid);
                        ajaxDisplay.innerHTML = ajaxRequest.responseText;
                }
                else if (ajaxRequest.readyState == 4 && ajaxRequest.status == 419) {
                        var ajaxDisplay = document.getElementById("app");
                        ajaxDisplay.innerHTML = ajaxRequest.responseText;
                }
        }
        if (instruction == "showStructure") {
                ajaxRequest.open("GET", "/salarystructures/" + execute_id, true);
                ajaxRequest.send();
        }
        if(instruction == "createStructure"){
                ajaxRequest.open("POST", "/salarystructures", true);
                ajaxRequest.setRequestHeader("Content-type", "application/json");
                ajaxRequest.send(execute_id);
        }
        if(instruction == "updateStructure"){
                var id = document.getElementById('sid').value;
                ajaxRequest.open("POST", "/salarystructures/"+id, true);
                ajaxRequest.setRequestHeader("Content-type", "application/json");
                ajaxRequest.send(execute_id);
        }
}
function deleteSalaryStructure(salarystructure, salarystructureid) {
        var confirmation = confirm("Please confirm deletion of salarystructure : '" + salarystructure + "'");
        if (confirmation) {
                submitDeleteForm("salarystructures", salarystructureid);
        }
}

function showStructure(id) {
        ajaxFunction('showStructure', id, 'salary_structure_details');
}

function submitStructure(e, form) {
        e.preventDefault();
        clearErrorFormatting(form.id);
        var formdat = structureAction();
        ajaxFunction('createStructure', JSON.stringify(formdat) , '');
}

function editStructure(e, form) {
        e.preventDefault();
        clearErrorFormatting(form.id);
        var formdat = structureAction();
        formdat["_method"] = "PUT";
        ajaxFunction('updateStructure', JSON.stringify(formdat) , '');
}

function structureAction(){
        var i, value;
        for (i = 0; i < root.length; i++) {
                value = document.getElementById(root[i].param_name).value;
                root[i].value = value;
        }
        var formdat = {};
        formdat['_token'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formdat['data'] = root;
        formdat['structurename'] = document.getElementById('structurename').value;

        return formdat;
}