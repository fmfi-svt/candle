/*
   Zatial len ovladanie panelu
*/

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
        focusTargets: [],
        cookieOptions: {}
    },
    initialize: function(options) {
        this.setOptions(options);
        this.addGroups(this.options.groups, this.options.togglers,
                        this.options.focusTargets);
        if (this.groups.length) {
            var foundConfig = false;
            this.groups.each(function(group, idx) {
                var cookie = Cookie.read(this.getCookieNameForGroup(group.id));
                if ($chk(cookie)) {
                    foundConfig = true;
                    if (cookie == "show") {
                        this.showGroup(idx);
                    }
                    else {
                        this.hideGroup(idx);
                    }
                }
            }, this);
            if (!foundConfig) this.showOnly(this.options.defaultGroup);
        }
    },
    groups: [],
    togglers: [],
    focusTargets: [],
    getCookieNameForGroup: function(group_id) {
        return "layout_panelgroup_"+group_id;
    },
    getGroupId: function(idx) {
        return this.groups[idx].id;
    },
    showGroup: function(idx) {
        this.groups[idx].removeClass(this.options.collapsedClass);
        this.focusTargets[idx].focus();
        Cookie.write(this.getCookieNameForGroup(this.getGroupId(idx)), "show", this.options.cookieOptions);
    },
    hideGroup: function(idx) {
        this.groups[idx].addClass(this.options.collapsedClass);
        Cookie.write(this.getCookieNameForGroup(this.getGroupId(idx)), "hide", this.options.cookieOptions);
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
    var panel_schovat = $("panel_schovat");
    if ($chk(panel_schovat)) {
        panel_schovat.removeClass("hidden");
    }
    $(document.body).addClass("jsactive");

    var panel_toggle = $("panel_toggle");
    if ($chk(panel_toggle)) {
        new Panel($(document.body),
                {clickElement: $("panel_toggle"),
                 hiddenClass: "panel_hidden",
                 additionalElements: [$(document.html), $("obsah_wrap")],
                 ensureReflow: [$("obsah_wrap")]
                });
    }
    new PanelGroup({groups: $$("#panel .panel_cast"),
                    togglers: $$("#panel .panel_cast h2 a"),
                    focusTargets: $$("#panel .panel_cast .panel_search input[type=text]"),
                    cookieOptions: {path: candleFrontendRelativeUrl, domain: candleFrontendDomain }}
                  );
    tabManager = new TabManager('rozvrh_taby');
});