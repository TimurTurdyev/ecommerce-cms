import $ from 'jquery';

// jQuery плагин для Bootstrap Tooltip
$.fn.tooltip = function(options) {
    return this.each(function() {
        if (!this._tooltip) {
            this._tooltip = new bootstrap.Tooltip(this, options);
        }
    });
};

// jQuery плагин для Bootstrap Popover
$.fn.popover = function(options) {
    return this.each(function() {
        if (!this._popover) {
            this._popover = new bootstrap.Popover(this, options);
        }
    });
};

// jQuery плагин для Bootstrap Modal
$.fn.modal = function(action, options) {
    if (action === 'show' || action === 'hide' || action === 'toggle') {
        const modal = new bootstrap.Modal(this[0], options);
        return modal[action]();
    }
    return new bootstrap.Modal(this[0], options);
};

// jQuery плагин для Bootstrap Dropdown
$.fn.dropdown = function(action) {
    if (action === 'show' || action === 'hide' || action === 'toggle') {
        const dropdown = new bootstrap.Dropdown(this[0]);
        return dropdown[action]();
    }
    return new bootstrap.Dropdown(this[0]);
};

// jQuery плагин для Bootstrap Collapse
$.fn.collapse = function(action) {
    if (action === 'show' || action === 'hide' || action === 'toggle') {
        const collapse = new bootstrap.Collapse(this[0]);
        return collapse[action]();
    }
    return new bootstrap.Collapse(this[0]);
};

// jQuery плагин для Bootstrap Toast
$.fn.toast = function(options) {
    const toast = new bootstrap.Toast(this[0], options);
    this.data('bs.toast', toast);
    return this;
};
