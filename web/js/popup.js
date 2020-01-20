var dropCookie = true;                      // false disables the Cookie, allowing you to style the banner
var cookieDuration = 2;                     // Number of days before the cookie expires, and the banner reappears
var cookieName = 'anketaKolacik2020Zima';   // Name of our cookie
var cookieValue = 'on';                     // Value of cookie
var cookieHideDate = Date.parse('18 February 2020');
 
function createDiv(){
    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');
    div.setAttribute('id','cookie-anketa');
    div.innerHTML = `<div id="cookie-anketa">
      <style></style>
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin-ext" rel="stylesheet">
      <div class="anketa__wrap">
        <div class="anketa__container">
          <div class="anketa__text-block">
            <img src="${candleFrontendAbsoluteUrl}images/smile.svg" class="anketa__image">
            <div class="anketa__subtitle">
              Daj semestru ešte chvíľu
            </div>
          </div>
          <div class="anketa__button-wrap">
            <a class="anketa__button anketa__button--main" href="https://anketa.fmph.uniba.sk/?anketaPopup" target="_blank" rel="noopener noreferrer" onclick="removeMe();">
              Hlasuj v ankete
            </a>
            <a class="anketa__button anketa__button--secondary" href="javascript:void(0);" onclick="removeMe();">
              Neskôr
            </a>
          </div>
        </div>
      </div>
    </div>`;    
     
    bodytag.insertBefore(div,bodytag.firstChild); 
     
    document.getElementsByTagName('body')[0].className+=' cookiebanner';
}
 
 
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000)); 
        var expires = "; expires="+date.toGMTString(); 
    }
    else var expires = "";
    if(window.dropCookie) { 
        document.cookie = name+"="+value+expires+"; path=/"; 
    }
}
 
function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
 
function eraseCookie(name) {
    createCookie(name,"",-1);
}
 
window.onload = function (){
    var today = new Date();
    if((checkCookie(window.cookieName) != window.cookieValue) && (cookieHideDate > today)){
        createDiv(); 
    }
}

function removeMe(){
	var element = document.getElementById('cookie-anketa');
	element.parentNode.removeChild(element);     
        createCookie(window.cookieName,window.cookieValue, window.cookieDuration); // Create the cookie
}
