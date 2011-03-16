window.addEvent('domready', function() {
    var loading = $('lessons_box_loading');
    var searchRequest = new Request.HTML({
        url: candleFrontendAbsoluteUrl+"/panel/list-lessons",
        link: 'cancel',
        onSuccess: function(responseTree, responseElements,
            responseHTML, responseJavaScript) {
            if ($chk(loading)) {
                loading.removeClass('active');
            }
            $('list_lessons_box').innerHTML = responseHTML;
            if ($chk(document.timetableEditor)) {
                createEditorPanel(document.timetableEditor);
            }

        },
        onFailure: function() {
            if ($chk(loading)) {
                loading.removeClass('active');
            }
            $('list_lessons_box').innerHTML = 'Nepodarilo sa načítať ' +
                'zoznam predmetov.';
        }
    });


    $('showLessons').addEvent('keyup', function() {
        var cas = new Date().getTime();
        if ($chk(loading)) {
            loading.addClass('active');
        }

        var data = {
            'cas':cas,
            'showLessons':$('showLessons').value};

        if ($chk(document.candleTimetableEditor_timetableId)) {
            data.timetable_id = document.candleTimetableEditor_timetableId;
        }
        searchRequest.get(data);
  });
});