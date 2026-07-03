(function () {
    'use strict';

    var quotationItems = [];
    var autoSaveTimer = null;

    function ready(fn) {
        if (document.readyState !== 'loading') { fn(); }
        else { document.addEventListener('DOMContentLoaded', fn); }
    }

    ready(function () {
        initCustomerSelection();
        initDateDefaults();
        initFormSubmission();
        initAutoSave();
        initDropZones();
        initCollapsibleSections();
        initDeliveryCountrySync();
        initMarginSync();
    });

    /* ---------- CUSTOMER SELECTION ---------- */
    function initCustomerSelection() {
        var contactInfo = document.getElementById('contact-info');
        var nameDisplay = document.getElementById('contact-name-display');
        var emailDisplay = document.getElementById('contact-email-display');
        var phoneDisplay = document.getElementById('contact-phone-display');

        function ensureHidden(id, name) {
            if (document.getElementById(id)) return;
            var input = document.createElement('input');
            input.type = 'hidden';
            input.id = id;
            input.name = name;
            document.getElementById('quotation-form').appendChild(input);
        }
        ensureHidden('contact_name', 'contact_name');
        ensureHidden('contact_email', 'contact_email');
        ensureHidden('contact_phone', 'contact_phone');
        ensureHidden('reseller_id', 'reseller_id');

        window.addEventListener('customerSelected', function (e) {
            if (e.detail && contactInfo) {
                contactInfo.classList.remove('hidden');
                if (nameDisplay) nameDisplay.textContent = e.detail.name || '—';
                if (emailDisplay) emailDisplay.textContent = e.detail.email || '—';
                if (phoneDisplay) phoneDisplay.textContent = e.detail.phone || '—';
                setVal('contact_name', e.detail.name || '');
                setVal('contact_email', e.detail.email || '');
                setVal('contact_phone', e.detail.phone || '');
                setVal('reseller_id', e.detail.id || '');
            }
        });
        window.addEventListener('customerCleared', function () {
            if (contactInfo) contactInfo.classList.add('hidden');
            if (nameDisplay) nameDisplay.textContent = '—';
            if (emailDisplay) emailDisplay.textContent = '—';
            if (phoneDisplay) phoneDisplay.textContent = '—';
            ['contact_name', 'contact_email', 'contact_phone', 'user_id'].forEach(function (id) { setVal(id, ''); });
        });
    }

    /* ---------- DATE DEFAULTS ---------- */
    function initDateDefaults() {
        var today = new Date();
        var dateStr = today.toISOString().split('T')[0];
        var el = document.getElementById('issue_date');
        if (el && !el.value) el.value = dateStr;

        var validUntil = document.querySelector('input[name="valid_until"]');
        if (validUntil && !validUntil.value) {
            var d = new Date();
            d.setDate(d.getDate() + 30);
            validUntil.value = d.toISOString().split('T')[0];
        }
    }

    /* ---------- DELIVERY COUNTRY SYNC ---------- */
    function initDeliveryCountrySync() {
        var select = document.getElementById('delivery_country');
        if (select && typeof Livewire !== 'undefined') {
            select.addEventListener('change', function () {
                Livewire.dispatch('countryChanged', { country: this.value });
            });
        }
    }

    /* ---------- FORM SUBMISSION ---------- */
    function initFormSubmission() {
        var form = document.getElementById('quotation-form');
        if (!form) return;

        var itemsJsonInput = document.getElementById('items-json');
        if (!itemsJsonInput) {
            itemsJsonInput = document.createElement('input');
            itemsJsonInput.type = 'hidden';
            itemsJsonInput.id = 'items-json';
            itemsJsonInput.name = 'items';
            form.appendChild(itemsJsonInput);
        }

        var marginHidden = document.getElementById('margin_percentage_hidden');

        window.addEventListener('itemsUpdated', function (e) {
            if (e.detail && e.detail.items) {
                quotationItems = e.detail.items;
                itemsJsonInput.value = JSON.stringify(quotationItems);
            }
            if (e.detail && e.detail.margin !== undefined && marginHidden) {
                marginHidden.value = e.detail.margin;
            }
        });

        form.addEventListener('submit', function (e) {
            var submitter = e.submitter;
            var action = submitter ? submitter.getAttribute('data-action') : 'draft';
            var actionInput = document.getElementById('form-action');
            if (actionInput) actionInput.value = action;
            if (quotationItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the quotation.');
                return;
            }
            var r = document.getElementById('reseller_id');
            if (!r || !r.value) {
                e.preventDefault();
                alert('Please select a customer.');
                return;
            }
        });
    }

    /* ---------- AUTO-SAVE ---------- */
    function initAutoSave() {
        var form = document.getElementById('quotation-form');
        if (!form) return;

        form.addEventListener('input', function () {
            markDirty();
        });
    }

    function markDirty() {
        var indicator = document.getElementById('last-saved');
        if (indicator) indicator.textContent = 'Unsaved changes...';
    }

    /* ---------- DROP ZONES ---------- */
    function initDropZones() {
        document.querySelectorAll('.drop-zone').forEach(function (zone) {
            zone.addEventListener('click', function () {
                var input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.onchange = function (e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (ev) {
                            zone.innerHTML = '<img src="' + ev.target.result + '" class="max-h-32 mx-auto rounded-lg">';
                        };
                        reader.readAsDataURL(file);
                    }
                };
                input.click();
            });
        });
    }

    /* ---------- COLLAPSIBLE SECTIONS ---------- */
    function initCollapsibleSections() {
        document.querySelectorAll('.section-collapsible').forEach(function (header) {
            header.addEventListener('click', function () {
                this.classList.toggle('collapsed');
            });
        });
    }

    /* ---------- MARGIN SYNC ---------- */
    function initMarginSync() {
        var marginInput = document.getElementById('margin_percentage');
        if (marginInput && typeof Livewire !== 'undefined') {
            marginInput.addEventListener('change', function () {
                Livewire.dispatch('marginChanged', { margin: parseFloat(this.value) || 0 });
            });
            marginInput.addEventListener('input', function () {
                Livewire.dispatch('marginChanged', { margin: parseFloat(this.value) || 0 });
            });
        }
    }

    /* ---------- HELPERS ---------- */
    function setVal(id, val) {
        var el = document.getElementById(id);
        if (el) el.value = val;
    }

})();
