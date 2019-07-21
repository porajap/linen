// JavaScript Document
function loadIframe(iframeName, url) {
    if ( window.frames[iframeName] ) {
         window.frames[iframeName].location = url;
        return false;
    }
	 window.frames[iframeName].location = url;
    return true;
}