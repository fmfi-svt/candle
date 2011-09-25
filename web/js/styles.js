
(function () {

function setActiveStyleSheet(title) {
  var styles = $$('link[rel*="style"][title]');
  if (styles.filter(function (s) { return s.title == title; }).length != 0) {
    styles.each(function (s) { s.disabled = (s.title != title); });
  }
}

function getActiveStyleSheet() {
  var enabled = $$('link[rel*="style"][title]:enabled');
  return enabled.length ? enabled[0].title : null;
}

setActiveStyleSheet(Cookie.read("candle_style"));

window.addEvent('unload', function () {
  Cookie.write("candle_style", getActiveStyleSheet(), { duration: 365 });
});

function clickedSwitchButton() {
  var styles = $$('link[rel*="style"][title]');
  for (var i = 0; i < styles.length; i++) {
    if (!styles[i].disabled) {
      setActiveStyleSheet(styles[(i+1) % styles.length].title);
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

