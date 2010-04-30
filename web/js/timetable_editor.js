var TimetableEditor = new Class({
    Implements: [Options, Events],
    options: {

    },
    initialize: function(timetableForm, options) {
        this.setOptions(options);
        this.timetableForm = $(timetableForm);
        this.bindCheckboxes();
    },
    timetableForm: null,
    bindCheckboxes: function() {
        // checboxy na upravu pritomnosti v rozvrhu
        // zatial treba poslat request a loadnut cely rozvrh zo servera
        this.timetableForm.getElements('input[type=checkbox][name="lesson[]"]')
                .each(function(checkbox) {
            checkbox.addEvent('change', this.lessonCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
        // checkboxy na upravu zvyraznenia
        this.timetableForm.getElements('input[type=checkbox][name="lessonHighlighted[]"]')
                .each(function(checkbox) {
            checkbox.addEvent('change', this.lessonHighlightedCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
    },
    refreshTimetable: function() {
        this.timetableForm.submit();
    },
    lessonCheckboxChanged: function(event) {
        this.refreshTimetable();
        this.change();
    },
    lessonHighlightedCheckboxChanged: function(event) {
        var checkbox = $(event.target);
        var cell = this.getTimetableCell(checkbox);
        if (checkbox.checked) {
            cell.addClass('highlighted');
        }
        else {
            cell.removeClass('highlighted');
        }
        this.change();
    },
    getTimetableCell: function(el) {
        // najdi bunku pre tento element (on sam, alebo najblizsi rodic typu td)
        var element = $(el);
        if (element.get('tag') == 'td') return element;
        return element.getParent('td');
    },
    change: function() {
        this.fireEvent('change');
    }
});

window.addEvent('domready', function() {

  $$("#panel_change_lessons input[type=checkbox]").addEvent('change', function() {
      $('panel_change_lessons').submit();
  });

  var editor = new TimetableEditor('timetable_editor_form');

  editor.addEvent('change', function() {
     tabManager.setState('upravený');
  });

});