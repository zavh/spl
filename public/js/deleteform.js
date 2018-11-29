function submitDeleteForm(action, delid){
	var deleteForm = document.createElement("form");
	deleteForm.target = "";
	deleteForm.method = "POST";
	deleteForm.action = "/"+action+"/"+delid;

	var methodInput = document.createElement("input");
	methodInput.type = "hidden";
	methodInput.name = "_method";
	methodInput.value = "delete";
	deleteForm.appendChild(methodInput);

	var csrfInput = document.createElement("input");
	csrfInput.type = "hidden";
	csrfInput.name = "_token";
	csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	deleteForm.appendChild(csrfInput);
	document.body.appendChild(deleteForm);
	deleteForm.submit();
}