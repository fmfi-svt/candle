/*
   Doplnky k mriežke rozvrhu: čiara s aktuálnym časom, zvýraznenie dnešného
   dňa, posúvanie hlavičky s dňami na mobile, tlačidlo tlače a prepínače
   zoznamu hodín pod rozvrhom.
*/

var timetableIndicator = {
    timer: null,
    // nastaví polohu čiary s aktuálnym časom a zvýrazní dnešný deň
    update: function() {
        var table = $('rozvrh');
        var line = $('timetable__current_time');
        var now = new Date();
        var today = now.getDay() - 1; // 0 = pondelok, -1 = nedeľa

        $$('.timetable__day').each(function(dayElement) {
            if (dayElement.get('data-day') == today) {
                dayElement.addClass('timetable__day--today');
            }
            else {
                dayElement.removeClass('timetable__day--today');
            }
        });

        if (!$chk(table) || !$chk(line)) return;

        var start = parseInt(table.get('data-start'), 10);
        var slotMinutes = parseInt(table.get('data-slot-minutes'), 10);
        var slotHeight = parseInt(table.get('data-slot-height'), 10);
        var height = parseInt(table.getStyle('height'), 10);
        var minutes = now.getHours() * 60 + now.getMinutes() - start;
        var top = Math.round(minutes * slotHeight / slotMinutes);

        if (today >= 0 && today <= 4 && top >= 0 && top <= height) {
            line.setStyle('top', top + 'px');
            line.removeClass('hidden');
        }
        else {
            line.addClass('hidden');
        }
    },
    start: function() {
        this.update();
        if (this.timer === null) {
            this.timer = setInterval(this.update.bind(this), 60 * 1000);
        }
    }
};

// na užších obrazovkách sa rozvrh posúva vodorovne, hlavička s dňami
// je mimo posúvanej časti, tak ju posúvame spolu s ním
var timetableHeaderSync = function() {
    var table = $('rozvrh');
    var days = $('timetable__days');
    if (!$chk(table) || !$chk(days)) return;
    var sync = function() {
        days.setStyle('transform', 'translateX(' + (-table.scrollLeft) + 'px)');
    };
    table.addEvent('scroll', sync);
    sync();
};

var printButtonPrepare = function() {
    $$('.js-print').each(function(button) {
        button.addEvent('click', function(event) {
            event.preventDefault();
            window.print();
        });
    });
};

var timetablePrepare = function() {
    timetableIndicator.start();
    timetableHeaderSync();

    var rozvrhList = $('rozvrhList');
    if (rozvrhList && !$('rozvrhListTogglerContainer')) {
        var d = $(document.createElement('div'));
        d.setAttribute('id','rozvrhListTogglerContainer');
        var chk = $(document.createElement('input'));
        chk.setAttribute('type', 'checkbox');
        chk.setAttribute('id', 'rozvrhListToggler');
        chk.addEvent('click', function() {
           var el = $('rozvrhList');
           if (chk.checked) {
               el.removeClass('hidden');
               Cookie.write("candle_timetable_list_show", "show", {});
           }
           else {
               el.addClass('hidden');
               Cookie.write("candle_timetable_list_show", "hide", {});
           }
        });
        var cookie1 = Cookie.read("candle_timetable_list_show");
        if ($chk(cookie1)) {
            var showList = (cookie1 == "show");
            chk.checked = showList;
            if (!showList) {
                rozvrhList.addClass('hidden');
            }
        }
        else {
            chk.checked = true;
        }
        d.appendChild(chk)
        var lab = $(document.createElement('label'));
        lab.setAttribute('for', 'rozvrhListToggler');
        lab.innerHTML = 'Zobrazovať/tlačiť zoznam hodín';
        d.appendChild(lab);

        var chk2 = $(document.createElement('input'));
        chk2.setAttribute('type', 'checkbox');
        chk2.setAttribute('id', 'rozvrhListToggler2');
        chk2.addEvent('click', function() {
           var el2 = $('rozvrhList');
           if (chk2.checked) {
               el2.addClass('onNextPage');
               Cookie.write("candle_timetable_list_nextPage", "enabled", {});
           }
           else {
               el2.removeClass('onNextPage');
               Cookie.write("candle_timetable_list_nextPage", "disabled", {});
           }
        });
        var cookie2 = Cookie.read("candle_timetable_list_nextPage");
        if ($chk(cookie2)) {
            var showList2 = (cookie2 == "enabled");
            chk2.checked = showList2;
            if (showList2) {
                rozvrhList.addClass('onNextPage');
            }
        }
        else {
            chk2.checked = false;
        }
        d.appendChild(chk2)
        var lab2 = $(document.createElement('label'));
        lab2.setAttribute('for', 'rozvrhListToggler2');
        lab2.innerHTML = 'Tlačiť zoznam na ďaľšej strane';
        d.appendChild(lab2);
        rozvrhList.parentNode.insertBefore(d, rozvrhList)
    }

}

window.addEvent('domready', timetablePrepare);
window.addEvent('domready', printButtonPrepare);
