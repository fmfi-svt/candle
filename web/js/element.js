
Element.implement({
    ensureReflow: function() {
        if (!Browser.Engine.webkit) return;
        this.replaceWith(this);
    },
    replaceWith: function(other) {
        var parentNode = this.parentNode;
        var nextSibling = this.nextSibling;
        parentNode.removeChild(this);
        if (nextSibling) {
            parentNode.insertBefore(other, nextSibling);
        }
        else {
            parentNode.appendChild(other);
        }
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