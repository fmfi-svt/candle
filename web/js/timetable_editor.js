window.addEvent('domready', function() {

  $$("#panel_change_lessons input[type=checkbox]").addEvent('change', function() {
      $('panel_change_lessons').submit();
  });

  $$("#timetable_editor_form input[type=checkbox]").addEvent('change', function() {
      $('timetable_editor_form').submit();
  });

});