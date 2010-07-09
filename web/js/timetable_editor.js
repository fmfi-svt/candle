var TimetableEditor = new Class({
    Implements: Events,
    initialize: function(timetableEditorElement, changeLessonsURL) {
        this.timetableEditorElement = $(timetableEditorElement);
        this.timetableForm = this.timetableEditorElement.getElement('form');
        this.bindCheckboxes();
        this.bindCommandBar();
        this.changeLessons = new Request({
           url: changeLessonsURL,
           link: 'chain'
        });
        this.changeLessonsRefresh = new Request({
           url: changeLessonsURL,
           link: 'chain'
        });
        this.changeLessonsRefresh.addEvent('success', this.changeLessonsRefreshSucceded.create({
            bind: this
        }));
    },
    timetableForm: null,
    changeLessons: null,
    timetableEditorElement: null,
    bindCheckboxes: function() {
        // checkboxy na upravu zvyraznenia
        this.timetableForm.getElements('input[type=checkbox][name="lesson_selection[]"]')
                .each(function(checkbox) {
            checkbox.addEvent('change', this.lessonSelectionCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
    },
    bindCommandBar: function() {
        var selectionSource = $('timetable_editor_selection_source');
        if ($chk(selectionSource)) {
            selectionSource.addEvent('change', this.selectionSourceChanged.create({
                event: true,
                bind: this
            }))
        }
    },
    refreshTimetable: function() {
        var html = new Request.HTML({
            url: window.location.href,
            onSuccess: function(responseTree, responseElements,
                responseHTML, responseJavaScript) {
                this.refreshTimetableSucceeded(responseTree, responseElements,
                responseHTML, responseJavaScript);
            }.create({
                bind: this
            }),
            onFailure: function() {
                alert('Nepodarilo sa obnovi\u0165 rozvrh');
            }
        }).get();
    },
    refreshTimetableSucceeded: function(responseTree, responseElements,
                responseHTML, responseJavaScript) {
        var newTimetableEditor = $(responseTree[0]);
        var parent = this.timetableEditorElement.getParent();
        parent.removeChild(this.timetableEditorElement);
        this.timetableEditorElement = newTimetableEditor;
        parent.appendChild(this.timetableEditorElement);
        this.timetableForm = this.timetableEditorElement.getElement('form');
        this.bindCheckboxes();
        //this.timetableEditorElement.set('html', responseHTML);
    },
    lessonSelectionCheckboxChanged: function(event) {
        var checkbox = $(event.target);
        var cell = this.getTimetableCell(checkbox);
        if (checkbox.checked) {
            cell.addClass('selected');
        }
        else {
            cell.removeClass('selected');
        }
        this.change();
    },
    selectionSourceChanged: function(event) {
        var select = $(event.target);
        if (select.value == 'highlight') {
            $('timetable_editor_selection_action_highlight').disabled = "disabled";
        }
        else {
            $('timetable_editor_selection_action_highlight').disabled = undefined;
        }
    },
    getTimetableCell: function(el) {
        // najdi bunku pre tento element (on sam, alebo najblizsi rodic typu td)
        var element = $(el);
        if (element.get('tag') == 'td') return element;
        return element.getParent('td');
    },
    change: function() {
        this.fireEvent('change');
    },
    serverHighlightLesson: function(lesson_id) {
        this.changeLessons.post({'lessonHighlighted[]': lesson_id});
    },
    serverUnhighlightLesson: function(lesson_id) {
        this.changeLessons.post({'lessonHighlightedBefore[]': lesson_id});
    },
    addLesson: function(lesson_id) {
        this.change();
        this.changeLessonsRefresh.post({'lesson[]': lesson_id});
    },
    removeLesson: function(lesson_id) {
        this.change();
        this.changeLessonsRefresh.post({'lessonBefore[]': lesson_id});
    },
    addSubject: function(subject_id) {
        this.change();
        this.changeLessonsRefresh.post({'subject[]': subject_id});
    },
    removeSubject: function(lesson_id) {
        this.change();
        this.changeLessonsRefresh.post({'subjectBefore[]': lesson_id});
    },
    changeLessonsRefreshSucceded: function(responseText, responseXML) {
        this.refreshTimetable();
    },
    lessonRemoved: function(lesson_id) {
        this.fireEvent('lessonRemoved', [lesson_id]);
    }
});

var TimetableEditorPanel = new Class({
    Implements: Events,
    initialize: function(panelForm) {
        this.panelForm = $(panelForm);
        this.bindCheckboxes();
    },
    panelForm: null,
    bindCheckboxes: function() {
        // na hodiny
        this.panelForm.getElements('input[type=checkbox][name="lesson[]"]').each(function(checkbox) {
            checkbox.addEvent('change', this.lessonCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
        // na cele predmety
        this.panelForm.getElements('input[type=checkbox][name="subject[]"]').each(function(checkbox) {
            checkbox.addEvent('change', this.subjectCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
    },
    lessonUpdateState: function(checkbox) {
        var subjectItem = this.getSubjectItem(checkbox);
        var all = 0;
        var checked = 0;
        subjectItem.getElements('input[type=checkbox][name="lesson[]"]')
            .each(function(checkbox) {
                all += 1;
                if (checkbox.checked) checked += 1;
            }, this);
        subjectItem.getElement('input[type=checkbox][name="subject[]"]').checked = (all == checked);
    },
    getLessonCheckbox: function(lessonId) {
        return $('panel_lesson_cb_'+lessonId);
    },
    lessonCheckboxChanged: function(event) {
        var checkbox = $(event.target);
        this.lessonUpdateState(checkbox);
        var lesson_id = checkbox.getProperty('value');
        this.lessonChange(lesson_id, checkbox.checked);
    },
    subjectCheckboxChanged: function(event) {
        var checkbox = $(event.target);
        var subject_id = checkbox.getProperty('value');
        var checked = checkbox.checked;
        var subjectItem = this.getSubjectItem(checkbox);
        subjectItem.getElements('input[type=checkbox]').each(function(checkbox) {
            checkbox.checked = checked;
        });
        this.subjectChange(subject_id, checked);
    },
    lessonChange: function(lesson_id, selected) {
        this.fireEvent('lessonChange', [lesson_id, selected]);
    },
    subjectChange: function(subject_id, selected) {
        this.fireEvent('subjectChange', [subject_id, selected])
    },
    getSubjectItem: function(element) {
        return element.getParent('li');
    }
});

window.addEvent('domready', function() {

  var timetable_editor_element = $('timetable_editor');

  if ($chk(timetable_editor_element) && $chk(timetableEditor_changeLessonsURL)) {
      var editor = new TimetableEditor(timetable_editor_element, timetableEditor_changeLessonsURL);

      editor.addEvent('change', function() {
         tabManager.setState('upravený');
      });

      var panel_change_lessons_form = $('panel_change_lessons');

      if ($chk(panel_change_lessons_form)) {
          var editorPanel = new TimetableEditorPanel(panel_change_lessons_form);
          editorPanel.addEvent('lessonChange', function(lessonId, selected) {
              if (selected) {
                  editor.addLesson(lessonId);
              }
              else {
                  editor.removeLesson(lessonId);
              }
          });
          editorPanel.addEvent('subjectChange', function(subjectId, selected) {
              if (selected) {
                  editor.addSubject(subjectId);
              }
              else {
                  editor.removeSubject(subjectId);
              }
          });
          editor.addEvent('lessonRemoved', function(lessonId) {
              var checkbox = editorPanel.getLessonCheckbox(lessonId);
              if (checkbox) {
                  checkbox.checked = false;
                  editorPanel.lessonUpdateState(checkbox);
              }
          });
      }
  }

});