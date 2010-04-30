/*
   Zatial len ovladanie panelu
*/

Element.implement({
    ensureReflow: function() {
        if (!Browser.Engine.webkit) return;
        var parentNode = this.parentNode;
        var nextSibling = this.nextSibling;
        parentNode.removeChild(this);
        if (nextSibling) {
            parentNode.insertBefore(this, nextSibling);
        }
        else {
            parentNode.appendChild(this);
        }
    }
});

var Panel = new Class({
    Implements: Options,
    options: {
        hiddenClass: "hidden",
        additionalElements: [],
        clickElement: undefined,
        ensureReflow: []
    },
    initialize: function(panelElement, options) {
        this.setOptions(options);
        this.panelElement = panelElement;
        this.bindToggle(this.options.clickElement);
    },
    panelElement: undefined,
    show: function() {
        this.panelElement.removeClass(this.options.hiddenClass);
        $$(this.options.additionalElements).removeClass(this.options.hiddenClass);
        $$(this.options.ensureReflow).ensureReflow();
    },
    hide: function() {
        this.panelElement.addClass(this.options.hiddenClass);
        $$(this.options.additionalElements).addClass(this.options.hiddenClass)
        $$(this.options.ensureReflow).ensureReflow();
    },
    toggle: function() {
        if (this.panelElement.hasClass(this.options.hiddenClass)) {
            this.show();
        }
        else {
            this.hide();
        }
        return false;
    },
    bindToggle: function(clicker) {
        if ($chk(clicker)) {
            clicker.addEvent('click', this.toggle.bind(this));
        }
    }
});

var PanelGroup = new Class({
    Implements: Options,
    options: {
        collapsedClass: "collapsed",
        defaultGroup: 0,
        groups: [],
        togglers: [],
        focusTargets: []
    },
    initialize: function(options) {
        this.setOptions(options);
        this.addGroups(this.options.groups, this.options.togglers,
                        this.options.focusTargets);
        if (this.groups.length) {
            this.showOnly(this.options.defaultGroup);
        }
    },
    groups: [],
    togglers: [],
    focusTargets: [],
    showGroup: function(idx) {
        this.groups[idx].removeClass(this.options.collapsedClass);
        this.focusTargets[idx].focus();
    },
    hideGroup: function(idx) {
        this.groups[idx].addClass(this.options.collapsedClass);
    },
    toggleGroup: function(idx) {
        if (this.groups[idx].hasClass(this.options.collapsedClass)) {
            this.showGroup(idx);
        }
        else {
            this.hideGroup(idx);
        }
    },
    showOnly: function(idx) {
        this.groups.each(function(group, index) {
            if (idx==index) {
                this.showGroup(index);
            }
            else {
                this.hideGroup(index);
            }
        }, this);
    },
    togglerClicked: function(event, idx) {
        this.toggleGroup(idx);
        return false;
    },
    addGroups: function(groups, togglers, focusTargets) {
        var gr = $$(groups);
        var tog = $$(togglers);
        var foc = $$(focusTargets);
        if (gr.length != tog.length || tog.length != foc.length) return;
        tog.each(function(toggler, idx) {
            var handler = this.togglerClicked.create({
                arguments: [idx],
                event: true,
                bind: this
            });
            toggler.addEvent('click', handler);
        }, this);
        this.groups.extend(gr);
        this.togglers.extend(tog);
        this.focusTargets.extend(foc);
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
        return this.tabListElement.getElement('li a.selected');
    }
});


/*
    Inicializacia prvkov
*/

var tabManager = null;

window.addEvent('domready', function() {
    $("panel_schovat").removeClass("hidden");
    $$(".jshide").addClass("jshidden");
    $(document.body).addClass("jsactive");
    new Panel($(document.body), 
            {clickElement: $("panel_toggle"),
             hiddenClass: "panel_hidden",
             additionalElements: [$(document.html), $("obsah_wrap")],
             ensureReflow: [$("obsah_wrap")]
            });
    new PanelGroup({groups: $$("#panel .panel_cast"),
                    togglers: $$("#panel .panel_cast h2 a"),
                    focusTargets: $$("#panel .panel_cast .panel_search input[type=text]")}
                  );
    tabManager = new TabManager('rozvrh_taby');
});