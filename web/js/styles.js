
(function () {

// from http://www.alistapart.com/articles/alternate/
// with some modifications

function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
}

function getActiveStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
  }
  return null;
}

function getPreferredStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1
       && a.getAttribute("rel").indexOf("alt") == -1
       && a.getAttribute("title")
       ) return a.getAttribute("title");
  }
  return null;
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

// end of ALA code

setActiveStyleSheet(readCookie("candle_style") || getPreferredStyleSheet());

// addEvent is mootools-specific
window.addEvent('unload', function () {
  createCookie("candle_style", getActiveStyleSheet(), 365);
});

function clickedSwitchButton() {
  var stylesheets = [];
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if (a.getAttribute('rel').indexOf('style') != -1 && a.getAttribute('title')) {
      stylesheets.push(a);
    }
  }

  for(var i = 0; i < stylesheets.length; i++) {
    if(!stylesheets[i].disabled) {
      setActiveStyleSheet(stylesheets[(i + 1) % stylesheets.length].getAttribute('title'));
      break;
    }
  }

  return false;
}

window.addEvent('domready', function () {
  var uls = $$('#vrch_riadok1_vpravo ul');
  if (uls.length == 0) return;
  var link = new Element('a', { text: 'Zmeniť farby', href: '#' });
  link.addEvent('click', clickedSwitchButton);
  uls[0].grab((new Element('li')).grab(link));
});

})();

