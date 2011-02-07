window.addEvent('domready', function() {
    var rozvrhList = $('rozvrhList');
    if (rozvrhList) {
        var d = $(document.createElement('div'));
        d.setAttribute('id','rozvrhListTogglerContainer');
        var chk = $(document.createElement('input'));
        chk.setAttribute('type', 'checkbox');
        chk.setAttribute('id', 'rozvrhListToggler');
        chk.addEvent('click', function() {
           var el = $('rozvrhList');
           if (chk.checked) {
               el.removeClass('hidden');
           }
           else {
               el.addClass('hidden');
           }
        });
        chk.checked = true;
        d.appendChild(chk)
        var lab = $(document.createElement('label'));
        lab.setAttribute('for', 'rozvrhListToggler');
        lab.textContent = 'Zobrazova\u0165/tlačiť zoznam hodín';
        d.appendChild(lab);

        var chk2 = $(document.createElement('input'));
        chk2.setAttribute('type', 'checkbox');
        chk2.setAttribute('id', 'rozvrhListToggler2');
        chk2.addEvent('click', function() {
           var el2 = $('rozvrhList');
           if (chk2.checked) {
               el2.addClass('onNextPage');
           }
           else {
               el2.removeClass('onNextPage');
           }
        });
        chk2.checked = false;
        d.appendChild(chk2)
        var lab2 = $(document.createElement('label'));
        lab2.setAttribute('for', 'rozvrhListToggler2');
        lab2.textContent = 'Tlačiť zoznam na ďaľšej strane';
        d.appendChild(lab2);
        rozvrhList.parentNode.insertBefore(d, rozvrhList)
    }

});