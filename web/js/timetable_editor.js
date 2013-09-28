var TimetableEditor = new Class({
    Implements: Events,
    initialize: function(timetableEditorElement, changeLessonsURL) {
        this.timetableEditorElement = $(timetableEditorElement);
        this.timetableForm = this.timetableEditorElement.getElement('form');
        this.bindEditor();
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
    changeLessonsRefresh: null,
    timetableEditorElement: null,
    bindEditor: function() {
        this.bindCheckboxes();
        this.bindCommandBar();
    },
    bindCheckboxes: function() {
        // checkboxy na upravu zvyraznenia
        this.timetableForm.getElements('input[type=checkbox][name="lesson_selection[]"]')
                .each(function(checkbox) {
            checkbox.addEvent('click', this.lessonSelectionCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
    },
    bindCommandBar: function() {
        this.timetableForm.addEvent('submit', this.timetableFormSubmit.create({
            event: true,
            bind: this
        }));

        var selectionSource = $('timetable_editor_selection_source');
        if ($chk(selectionSource)) {
            selectionSource.addEvent('change', this.selectionSourceChanged.create({
                event: true,
                bind: this
            }))
        }

        var highlightLessons = $('timetable_editor_selection_action_highlight')
        if ($chk(highlightLessons)) {
            highlightLessons.addEvent('click', this.highlightLessonsClicked.create({
                event: true,
                bind: this
            }))
        }

        var unhighlightLessons = $('timetable_editor_selection_action_unhighlight')
        if ($chk(unhighlightLessons)) {
            unhighlightLessons.addEvent('click', this.unhighlightLessonsClicked.create({
                event: true,
                bind: this
            }))
        }

        var removeLessons = $('timetable_editor_selection_action_remove')
        if ($chk(removeLessons)) {
            removeLessons.addEvent('click', this.removeLessonsClicked.create({
                event: true,
                bind: this
            }))
        }
    },
    refreshTimetable: function() {
        var cas = new Date().getTime();
        var html = new Request.HTML({
            url: window.location.href,
            data: {'onlyTimetable':'1', 'cas':cas},
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
        this.timetableEditorElement.replaceWith(newTimetableEditor);
        this.timetableEditorElement = newTimetableEditor;
        this.timetableForm = this.timetableEditorElement.getElement('form');
        this.bindEditor();
        if (timetablePrepare) {
            timetablePrepare();
        }
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
        function setEnabled(button, value) {
            button.disabled = value ? undefined : "disabled";
        }
        var highlight_btn = $('timetable_editor_selection_action_highlight');
        var unhighlight_btn = $('timetable_editor_selection_action_unhighlight');
        setEnabled(highlight_btn, select.value != 'highlight');
        setEnabled(unhighlight_btn, select.value != 'highlight_inv');
    },
    getAffectedLessonIds: function() {
        var selectionSource = $('timetable_editor_selection_source');
        function collectBySelector(selector) {
            var lessonIds = Array();
            $('timetable_editor').getElements(selector)
                    .each(function(checkbox) {
                lessonIds.push(checkbox.value);
            });
            return lessonIds;
        }
        if (selectionSource.value == 'selection') {
            return collectBySelector('input[type=checkbox][name="lesson_selection[]"][checked]');
        }
        else if (selectionSource.value == 'selection_inv') {
            return collectBySelector('input[type=checkbox][name="lesson_selection[]"]:not([checked])');
        }
        else if (selectionSource.value == 'highlight') {
            return collectBySelector('.hodina.highlighted input[type=checkbox][name="lesson_selection[]"]');
        }
        else if (selectionSource.value == 'highlight_inv') {
            return collectBySelector('.hodina:not(.highlighted) input[type=checkbox][name="lesson_selection[]"]');
        }
        else if (selectionSource.value == 'all') {
            return collectBySelector('input[type=checkbox][name="lesson_selection[]"]');
        }
        throw "Invalid selectionSource value";
    },
    timetableFormSubmit: function(event) {
        event.preventDefault();
        return false;
    },
    highlightLessonsClicked: function(event) {
        var lessons = this.getAffectedLessonIds();
        lessons.each(function(lessonId) {
            var cell = this.getLessonCell(lessonId);
            if ($chk(cell)) {
                cell.addClass('highlighted');
            }
        }, this);
        this.change();
        this.serverHighlightLessons(lessons);
        event.preventDefault();
        return false;
    },
    unhighlightLessonsClicked: function(event) {
        var lessons = this.getAffectedLessonIds();
        lessons.each(function(lessonId) {
            var cell = this.getLessonCell(lessonId);
            if ($chk(cell)) {
                cell.removeClass('highlighted');
            }
        }, this);
        this.change();
        this.serverUnhighlightLessons(lessons);
        event.preventDefault();
        return false;
    },
    removeLessonsClicked: function(event) {
        var lessons = this.getAffectedLessonIds();
        this.removeLessons(lessons);
        lessons.each(this.lessonRemoved,this);
        event.preventDefault();
        return false;
    },
    getTimetableCell: function(el) {
        // najdi bunku pre tento element (on sam, alebo najblizsi rodic typu td)
        var element = $(el);
        if (element.get('tag') == 'td') return element;
        return element.getParent('td');
    },
    getLessonSelectionCheckbox: function(lessonId) {
        return $('timetable_lesson_selection_cb_'+lessonId);
    },
    getLessonCell: function(lessonId) {
        var checkbox = this.getLessonSelectionCheckbox(lessonId);
        if (!$chk(checkbox)) return null;
        return this.getTimetableCell(checkbox);
    },
    change: function() {
        this.fireEvent('change');
        var cas = new Date().getTime();
        Cookie.write("timetableEditor_changeToken", cas,
            {path: candleFrontendRelativeUrl, domain: candleFrontendDomain });
    },
    serverPostLessonIds: function(lesson_ids, fieldName, refreshTimetable) {
        var dataToSend = "";
        var first = true;
        lesson_ids.each(function(lesson_id) {
            if (!first) dataToSend += "&";
            first = false;

            dataToSend += fieldName+"="+escape(lesson_id);
        });
        if (refreshTimetable) {
            this.changeLessonsRefresh.send({data:dataToSend, method:'post'});
        }
        else {
            this.changeLessons.send({data:dataToSend, method:'post'});
        }
    },
    serverHighlightLesson: function(lesson_id) {
        this.changeLessons.post({'lessonHighlighted[]': lesson_id});
    },
    serverHighlightLessons: function(lesson_ids) {
        this.serverPostLessonIds(lesson_ids, 'lessonHighlighted[]', false);
    },
    serverUnhighlightLesson: function(lesson_id) {
        this.changeLessons.post({'lessonHighlightedBefore[]': lesson_id});
    },
    serverUnhighlightLessons: function(lesson_ids) {
        this.serverPostLessonIds(lesson_ids, 'lessonHighlightedBefore[]', false);
    },
    addLesson: function(lesson_id) {
        this.change();
        this.changeLessonsRefresh.post({'lesson[]': lesson_id});
    },
    addLessons: function(lesson_ids) {
        this.serverPostLessonIds(lesson_ids, 'lesson[]', true);
    },
    removeLesson: function(lesson_id) {
        this.change();
        this.changeLessonsRefresh.post({'lessonBefore[]': lesson_id});
    },
    removeLessons: function(lesson_ids) {
        this.serverPostLessonIds(lesson_ids, 'lessonBefore[]', true);
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
            checkbox.addEvent('click', this.lessonCheckboxChanged.create({
                event: true,
                bind: this
            }));
        }, this);
        // na cele predmety
        this.panelForm.getElements('input[type=checkbox][name="subject[]"]').each(function(checkbox) {
            checkbox.addEvent('click', this.subjectCheckboxChanged.create({
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

function createEditorPanel(editor) {
    var panel_change_lessons_form = $('panel_change_lessons');

    if (!$chk(panel_change_lessons_form)) return;

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

window.addEvent('domready', function() {

  var timetable_editor_element = $('timetable_editor');

  var saving = false;
  var modified = document.candleTimetableEditor_modified || false;

  if ($chk(timetable_editor_element) && $chk(timetableEditor_changeLessonsURL)) {
      var saveButton = $('menuSave');
      saveButton.addEvent('click', function(e){
          saving = true;
      });
      
      var editor = new TimetableEditor(timetable_editor_element, timetableEditor_changeLessonsURL);
      window.onbeforeunload = function() {
          if (saving !== true) {
              saving = false;
              if (modified)
                  return "Zmeny v rozvrhu ešte neboli uložené. Chcete naozaj odísť?";
          }
      };

      editor.addEvent('change', function() {
          tabManager.setState('upravený');
          modified = true;
      });

      createEditorPanel(editor);

      // Po stlaceni tlacitka back sa musi rozvrh znovu nacitat
      if (Cookie.read("timetableEditor_changeToken") != candleTimetableEditor_changeToken) {
          //alert(Cookie.read("timetableEditor_changeToken") + " != " + candleTimetableEditor_changeToken);
          editor.refreshTimetable();
      }
      document.timetableEditor = editor;
  }

});
