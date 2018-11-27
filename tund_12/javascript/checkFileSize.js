//window.alert("Hello world");
//console.log("Hello console");

window.onload = function(){
	document.getElementById("submitImage").disabled = true;
	document.getElementById("fileToUpload").addEventListener("change", checkSize);
}

function checkSize(){
	let fileToUpload = document.getElementById("fileToUpload").files[0];
	if(fileToUpload.size <= 2500000){
		document.getElementById("submitImage").disabled = false;
	}
}