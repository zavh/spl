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
}
function deleteDesignation(designation, designationid){
    var confirmation = confirm("Please confirm deletion of Designation : '"+designation+"'");
    if(confirmation){
        var uDeleteForm = document.createElement("form");
        uDeleteForm.target = "";
        uDeleteForm.method = "POST";
        uDeleteForm.action = "/designations/"+designationid;

        var methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "delete";
        uDeleteForm.appendChild(methodInput);

        var csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');;
        uDeleteForm.appendChild(csrfInput);
        document.body.appendChild(uDeleteForm);

        uDeleteForm.submit();	
    }
}