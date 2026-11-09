/*
   Ovládanie vysúvacieho panelu s vyhľadávaním a záložiek s rozvrhmi
*/

var Sidebar = new Class({
    initialize: function() {
        this.sidebar = $('sidebar');
        this.container = $('sidebar__container');
        this.overlay = $('overlay');
        this.toggleButton = $('sidebar__toggle');
        this.closeButton = $('sidebar__close');
        if (!$chk(this.sidebar) || !$chk(this.container)) return;

        if ($chk(this.toggleButton)) {
            this.toggleButton.addEvent('click', function(event) {
                event.preventDefault();
                this.toggle();
            }.bind(this));
        }
        if ($chk(this.closeButton)) {
            this.closeButton.addEvent('click', function(event) {
                event.preventDefault();
                this.hide();
            }.bind(this));
        }
        if ($chk(this.overlay)) {
            this.overlay.addEvent('click', this.hide.bind(this));
        }
        document.addEvent('keydown', function(event) {
            if (event.key == 'esc' && this.isOpen()) {
                this.hide();
            }
        }.bind(this));
    },
    isOpen: function() {
        return this.container.hasClass('sidebar__container--expanded');
    },
    show: function() {
        this.container.addClass('sidebar__container--expanded');
        this.sidebar.addClass('sidebar--expanded');
        if ($chk(this.overlay)) this.overlay.addClass('overlay--visible');
        if ($chk(this.toggleButton)) this.toggleButton.setProperty('aria-expanded', 'true');
        var firstInput = this.container.getElement('input[type=text]');
        if ($chk(firstInput)) {
            // až po dokončení animácie, inak by prehliadač panel rolovaním rozbil
            (function() { firstInput.focus(); }).delay(400);
        }
    },
    hide: function() {
        this.container.removeClass('sidebar__container--expanded');
        this.sidebar.removeClass('sidebar--expanded');
        if ($chk(this.overlay)) this.overlay.removeClass('overlay--visible');
        if ($chk(this.toggleButton)) this.toggleButton.setProperty('aria-expanded', 'false');
    },
    toggle: function() {
        if (this.isOpen()) {
            this.hide();
        }
        else {
            this.show();
        }
    }
});

var TabManager = new Class({
    Implements: Options,
    options: {

    },
    initialize: function(tabListElement, options) {
        this.setOptions(options);
        this.tabListElement = $(tabListElement);
    },
    tabListElement: null,
    setState: function(label) {
        var link = this.getActiveTabLink();
        if (!$chk(link)) return;
        var state = link.getElement('span.rozvrh_stav');
        var adding = !$chk(state);
        if (adding) {
            state = new Element('span', {'class': 'rozvrh_stav'});
        }
        state.set('text', '['+label+']');
        if (adding) {
            link.appendText(' ');
            link.grab(state);
        }
    },
    getActiveTabLink: function() {
        if (!$chk(this.tabListElement)) return null;
        return this.tabListElement.getElement('li a.selected');
    }
});


/*
    Inicializacia prvkov
*/

var tabManager = null;
var sidebar = null;

window.addEvent('domready', function() {
    $(document.body).addClass("jsactive");
    sidebar = new Sidebar();
    tabManager = new TabManager('rozvrh_taby');
});
