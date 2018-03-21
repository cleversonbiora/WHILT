'use strict';

/* globals MediaRecorder */

// Spec is at http://dvcs.w3.org/hg/dap/raw-file/tip/media-stream-capture/RecordingProposal.html

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
var request;
var blob;
if(getBrowser() == "Chrome"){
	var constraints = {"audio": true, "video": {  "mandatory": {  "minWidth": 640,  "maxWidth": 640, "minHeight": 480,"maxHeight": 480 }, "optional": [] } };//Chrome
}else if(getBrowser() == "Firefox"){
	var constraints = {audio: true,video: {  width: { min: 640, ideal: 640, max: 640 },  height: { min: 480, ideal: 480, max: 480 }}}; //Firefox
}

var recBtn = document.querySelector('button#rec');
var pauseResBtn = document.querySelector('button#pauseRes');
var stopBtn = document.querySelector('button#stop');

var videoElement = document.querySelector('#campreview');
var videoElement2 = document.querySelector('#result');
var dataElement = document.querySelector('#data');
var downloadLink = document.querySelector('a#downloadLink');

videoElement.controls = false;

function errorCallback(error){
	console.log('navigator.getUserMedia error: ', error);	
}

/*
var mediaSource = new MediaSource();
mediaSource.addEventListener('sourceopen', handleSourceOpen, false);
var sourceBuffer;
*/

var mediaRecorder;
var chunks = [];
var count = 0;

function startRecording(stream) {
	document.getElementById('result').style.display = 'none';
	document.getElementById('campreview').style.display = 'block';
	log('Start recording...');
	if (typeof MediaRecorder.isTypeSupported == 'function'){
		/*
			MediaRecorder.isTypeSupported is a function announced in https://developers.google.com/web/updates/2016/01/mediarecorder and later introduced in the MediaRecorder API spec http://www.w3.org/TR/mediastream-recording/
		*/
		if (MediaRecorder.isTypeSupported('video/webm;codecs=h264')) {
		  var options = {mimeType: 'video/webm;codecs=h264'};
		} else if (MediaRecorder.isTypeSupported('video/webm;codecs=vp9')) {
		  var options = {mimeType: 'video/webm;codecs=vp9'};
		} else if (MediaRecorder.isTypeSupported('video/webm;codecs=vp8')) {
		  var options = {mimeType: 'video/webm;codecs=vp8'};
		}
		log('Using '+options.mimeType);
		mediaRecorder = new MediaRecorder(stream, options);
	}else{
		log('Using default codecs for browser');
		mediaRecorder = new MediaRecorder(stream);
	}

	pauseResBtn.textContent = "Pause";

	mediaRecorder.start(10);

	var url = window.URL || window.webkitURL;
	videoElement.src = url ? url.createObjectURL(stream) : stream;
	videoElement.play();

	mediaRecorder.ondataavailable = function(e) {
		//log('Data available...');
		//console.log(e.data);
		//console.log(e.data.type);
		//console.log(e);
		chunks.push(e.data);
	};

	mediaRecorder.onerror = function(e){
		log('Error: ' + e);
		console.log('Error: ', e);
	};


	mediaRecorder.onstart = function(){
		log('Started & state = ' + mediaRecorder.state);
	};

	mediaRecorder.onstop = function(){
		log('Stopped  & state = ' + mediaRecorder.state);

		blob = new Blob(chunks, {type: "video/webm"});
		chunks = [];

		var videoURL = window.URL.createObjectURL(blob);

		downloadLink.href = videoURL;
		videoElement2.src = videoURL;
		document.getElementById("video").value = videoURL;
		/*downloadLink.innerHTML = 'Download video file';
		
		var rand =  Math.floor((Math.random() * 10000000));
		var name  = "video_"+rand+".webm" ;

		downloadLink.setAttribute( "download", name);
		downloadLink.setAttribute( "name", name);*/
	};

	mediaRecorder.onpause = function(){
		log('Paused & state = ' + mediaRecorder.state);
	}

	mediaRecorder.onresume = function(){
		log('Resumed  & state = ' + mediaRecorder.state);
	}

	mediaRecorder.onwarning = function(e){
		log('Warning: ' + e);
	};
}

//function handleSourceOpen(event) {
//  console.log('MediaSource opened');
//  sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp9"');
//  console.log('Source buffer: ', sourceBuffer);
//}

function onBtnRecordClicked (){
	 if (typeof MediaRecorder === 'undefined' || !navigator.getUserMedia) {
		alert('Recurso não suportado para o seu navegador, use Firefox 30 e Chrome 49 ou superior.');
	}else {
		navigator.getUserMedia(constraints, startRecording, errorCallback);
		recBtn.disabled = true;
		pauseResBtn.disabled = false;
		stopBtn.disabled = false;
	//}
}

function verifyCompatibility(){
	 if (typeof MediaRecorder === 'undefined' || !navigator.getUserMedia) {
		alert('ATENÇÃO\nPara o correto funcionamento do sistema e utilização do recurso de gravação via webcam, utilize o Firefox 30 e Chrome 49 ou superior.');
	}
}

function onBtnStopClicked(){
	document.getElementById('result').style.display = 'block';
	document.getElementById('campreview').style.display = 'none';
	mediaRecorder.stop();
	videoElement.controls = true;

	recBtn.disabled = false;
	pauseResBtn.disabled = true;
	stopBtn.disabled = true;
}

function onPauseResumeClicked(){
	if(pauseResBtn.textContent === "Pause"){
		console.log("pause");
		pauseResBtn.textContent = "Resume";
		mediaRecorder.pause();
		stopBtn.disabled = true;
	}else{
		console.log("resume");
		pauseResBtn.textContent = "Pause";
		mediaRecorder.resume();
		stopBtn.disabled = false;
	}
	recBtn.disabled = true;
	pauseResBtn.disabled = false;
}


function log(message){
	dataElement.innerHTML = dataElement.innerHTML+'<br>'+message ;
}



//browser ID
function getBrowser(){
	var nVer = navigator.appVersion;
	var nAgt = navigator.userAgent;
	var browserName  = navigator.appName;
	var fullVersion  = ''+parseFloat(navigator.appVersion);
	var majorVersion = parseInt(navigator.appVersion,10);
	var nameOffset,verOffset,ix;

	// In Opera, the true version is after "Opera" or after "Version"
	if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
	 browserName = "Opera";
	 fullVersion = nAgt.substring(verOffset+6);
	 if ((verOffset=nAgt.indexOf("Version"))!=-1)
	   fullVersion = nAgt.substring(verOffset+8);
	}
	// In MSIE, the true version is after "MSIE" in userAgent
	else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
	 browserName = "Microsoft Internet Explorer";
	 fullVersion = nAgt.substring(verOffset+5);
	}
	// In Chrome, the true version is after "Chrome"
	else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
	 browserName = "Chrome";
	 fullVersion = nAgt.substring(verOffset+7);
	}
	// In Safari, the true version is after "Safari" or after "Version"
	else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
	 browserName = "Safari";
	 fullVersion = nAgt.substring(verOffset+7);
	 if ((verOffset=nAgt.indexOf("Version"))!=-1)
	   fullVersion = nAgt.substring(verOffset+8);
	}
	// In Firefox, the true version is after "Firefox"
	else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
	 browserName = "Firefox";
	 fullVersion = nAgt.substring(verOffset+8);
	}
	// In most other browsers, "name/version" is at the end of userAgent
	else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) <
		   (verOffset=nAgt.lastIndexOf('/')) )
	{
	 browserName = nAgt.substring(nameOffset,verOffset);
	 fullVersion = nAgt.substring(verOffset+1);
	 if (browserName.toLowerCase()==browserName.toUpperCase()) {
	  browserName = navigator.appName;
	 }
	}
	// trim the fullVersion string at semicolon/space if present
	if ((ix=fullVersion.indexOf(";"))!=-1)
	   fullVersion=fullVersion.substring(0,ix);
	if ((ix=fullVersion.indexOf(" "))!=-1)
	   fullVersion=fullVersion.substring(0,ix);

	majorVersion = parseInt(''+fullVersion,10);
	if (isNaN(majorVersion)) {
	 fullVersion  = ''+parseFloat(navigator.appVersion);
	 majorVersion = parseInt(navigator.appVersion,10);
	}


	return browserName;
}
 function generateUUID() {
            var d = new Date().getTime();
            if (window.performance && typeof window.performance.now === "function") {
                d += performance.now(); //use high-precision timer if available
            }
            var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = (d + Math.random() * 16) % 16 | 0;
                d = Math.floor(d / 16);
                return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
            });
            return uuid;
        }
function insertVideo(form) {
	if(form.nome.value.length <= 0){
		alert("Preencha o titulo.");
		return false;
	}
	if(form.descricao.value.length <= 0){
		alert("Adicione uma descrição.");
		return false;
	}
	
		document.body.style.cursor = 'wait';
	var url = generateUUID() + '.webm';
	  var xhr2 = new XMLHttpRequest();
	  xhr2.open('POST', 'savefiles.php?path=video/' + url, false);
	  xhr2.onload = function(e) { console.log("loaded"); };
	  xhr2.onreadystatechange = function(){
		  console.log("state: " + xhr2.readyState);
	  };
	  // Listen to the upload progress.
	  xhr2.send(blob);
	  document.getElementById("video").value = 'video/' + url;
		var dados = $(form).serialize();
		var formData = new FormData(form);
		request = jQuery.ajax({
		xhr: function() {
			var xhr = new window.XMLHttpRequest();

			// Upload progress
			xhr.upload.addEventListener("progress", function(evt){
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					console.log(percentComplete);
				}
		   }, false);

		   // Download progress
		   xhr.addEventListener("progress", function(evt){
			   if (evt.lengthComputable) {
				   var percentComplete = evt.loaded / evt.total;
				   // Do something with download progress
				   console.log(percentComplete);
			   }
		   }, false);

		   return xhr;
		},
        type: "POST",
        url: "savevideo.php",
		cache: false,
		contentType: false,
		processData: false,
        data: formData,
        success: function (data) {
            document.body.style.cursor = 'default';
			location.href = "index.php";

        },
        error: function (jqXHR, textStatus, errorThrown) {
            document.body.style.cursor = 'default';
			location.href = "index.php";
        }
    });
	  return false;
}
/*function insertVideo(form) {
	if(form.nome.value.length <= 0){
		alert("Preencha o titulo.");
		return false;
	}
	if(form.descricao.value.length <= 0){
		alert("Adicione uma descrição.");
		return false;
	}
	  var xhr = new XMLHttpRequest();
	  xhr.open('POST', 'savefiles.php', true);
	  xhr.onload = function(e) { console.log("loaded"); };
	  xhr.onreadystatechange = function(){
		  console.log("state: " + xhr.readyState);
	  };
	  // Listen to the upload progress.
	  xhr.send(blob);
    var dados = $(form).serialize();
	var formData = new FormData(form);
    document.body.style.cursor = 'wait';
    request = jQuery.ajax({
		xhr: function() {
			var xhr = new window.XMLHttpRequest();

			// Upload progress
			xhr.upload.addEventListener("progress", function(evt){
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					console.log(percentComplete);
				}
		   }, false);

		   // Download progress
		   xhr.addEventListener("progress", function(evt){
			   if (evt.lengthComputable) {
				   var percentComplete = evt.loaded / evt.total;
				   // Do something with download progress
				   console.log(percentComplete);
			   }
		   }, false);

		   return xhr;
		},
        type: "POST",
        url: "savefiles.php",
		cache: false,
		contentType: false,
		processData: false,
        data: formData,
        success: function (data) {
            document.body.style.cursor = 'default';
			//location.href = "index.php";

        },
        error: function (jqXHR, textStatus, errorThrown) {
            document.body.style.cursor = 'default';
            alert("Upload cancelado.");
        }
    });
    return false;
}
*/