function createRequestObject(){
	var request_o;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	} else{
		request_o = new XMLHttpRequest();
	}
	return request_o;
}

var http = createRequestObject(); 

function getEvent(eventid){
	http.open('get', 'internal_request.php?action=getevent&id=' + eventid);
	http.onreadystatechange = handleEvent; 
	http.send(null);
}

function handleEvent(){
	if(http.readyState == 4){
		var response = http.responseText;
		document.getElementById('eventcage').innerHTML = response;
	}
}

function newEvent(eventdate, error){
	http.open('get', 'neweventform.php?date=' + eventdate + "&error=" + error);
	http.onreadystatechange = handleNewEvent; 
	http.send(null);
}

function handleNewEvent(){
	if(http.readyState == 4){
		var response = http.responseText;
		document.getElementById('eventcage').innerHTML = response;
	}
}
