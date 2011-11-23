
Element.implement({
    ensureReflow: function() {
        if (!Browser.Engine.webkit) return;
        this.replaceWith(this);
    },
    replaceWith: function(newElement) {
        var parentNode = $(this.parentNode);
        parentNode.insertAfter(newElement, this);
        parentNode.removeChild(this);
    },
    insertAfter: function(newElement, referenceElement) {
        var nextSibling = referenceElement.nextSibling;
        if (nextSibling) {
            this.insertBefore(newElement, nextSibling);
        }
        else {
            this.appendChild(newElement);
        }
    }
});