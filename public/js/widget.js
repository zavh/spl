function widgetFunction(instruction, execute_id, divid){
	var ajaxRequest;
    try{
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        try{
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
                try{
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e){
                    alert("Your browser broke!");
                    return false;
                }
        }
    }
    ajaxRequest.onreadystatechange = function(){
            if(ajaxRequest.readyState == 4 && ajaxRequest.status == 200){
                var ajaxDisplay = document.getElementById(divid);
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
            }
    } 

    if(instruction == "widgetShow"){   
        ajaxRequest.open("GET", "/"+execute_id+"/widget", true);
        ajaxRequest.send();
    }        
}