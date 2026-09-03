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
        initQuillEditor();
        initDateSync();
        initFormSubmission();
        initAutoSave();
        initDropZones();
        initCollapsibleSections();
        initDeliveryCountrySync();
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
            var form = document.getElementById('order-form') || document.getElementById('quotation-form');
            if (form) form.appendChild(input);
        }
        ensureHidden('contact_name', 'contact_name');
        ensureHidden('contact_email', 'contact_email');
        ensureHidden('contact_phone', 'contact_phone');
        ensureHidden('reseller_id', 'reseller_id');

        window.addEventListener('customerSelected', function (e) {
            if (e.detail) {
                if (contactInfo) {
                    contactInfo.classList.remove('hidden');
                    if (nameDisplay) nameDisplay.textContent = e.detail.name || '—';
                    if (emailDisplay) emailDisplay.textContent = e.detail.email || '—';
                    if (phoneDisplay) phoneDisplay.textContent = e.detail.phone || '—';
                }
                setVal('contact_name', e.detail.name || '');
                setVal('contact_email', e.detail.email || '');
                setVal('contact_phone', e.detail.phone || '');
                setVal('reseller_id', e.detail.id || '');
                var marginSpan = document.getElementById('margin_percentage');
                if (marginSpan) marginSpan.textContent = (e.detail.margin ?? 0).toFixed(2) + '%';
                setVal('margin_percentage_hidden', e.detail.margin ?? 0);
            }
        });
        window.addEventListener('customerCleared', function () {
            if (contactInfo) contactInfo.classList.add('hidden');
            if (nameDisplay) nameDisplay.textContent = '—';
            if (emailDisplay) emailDisplay.textContent = '—';
            if (phoneDisplay) phoneDisplay.textContent = '—';
            ['contact_name', 'contact_email', 'contact_phone', 'reseller_id'].forEach(function (id) { setVal(id, ''); });
            var marginSpan = document.getElementById('margin_percentage');
            if (marginSpan) marginSpan.textContent = '0.00%';
            setVal('margin_percentage_hidden', 0);
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
        var form = document.getElementById('order-form');
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

        // Initialize from current hidden value (handles server-rendered cartItemsJson)
        try {
            var initial = JSON.parse(itemsJsonInput.value || '[]');
            if (Array.isArray(initial) && initial.length > 0) {
                // Only accept array of objects with product_id
                var valid = initial.filter(function (it) { return it && typeof it === 'object' && it.product_id; });
                if (valid.length > 0) quotationItems = valid;
                else if (initial.length > 0 && typeof initial[0] === 'string') {
                    // Strings will be hydrated server-side, keep empty to allow server hydration
                    quotationItems = [];
                }
            }
        } catch (e) { }

        window.addEventListener('itemsUpdated', function (e) {
            if (e.detail && e.detail.items) {
                quotationItems = e.detail.items;
                itemsJsonInput.value = JSON.stringify(quotationItems);
            }
            if (e.detail && e.detail.margin !== undefined && marginHidden) {
                marginHidden.value = e.detail.margin;
            }
        });
        if (typeof Livewire !== 'undefined' && Livewire.on) {
            Livewire.on('itemsUpdated', function (data) {
                var items = data.items || (data.detail && data.detail.items) || data;
                if (Array.isArray(items)) {
                    quotationItems = items;
                    itemsJsonInput.value = JSON.stringify(quotationItems);
                }
                if (data.margin !== undefined && marginHidden) marginHidden.value = data.margin;
            });
        }

        // Keep hidden #form-action in sync with clicked button's data-action (draft/pdf/send)
        form.querySelectorAll('button[data-action]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var actionInput = document.getElementById('form-action');
                if (actionInput) actionInput.value = this.getAttribute('data-action') || 'draft';
            });
        });

        form.addEventListener('submit', function (e) {
            var submitter = e.submitter;
            var action = submitter ? submitter.getAttribute('data-action') : (document.getElementById('form-action').value || 'draft');
            var actionInput = document.getElementById('form-action');
            if (actionInput) actionInput.value = action;
            if (quotationItems.length === 0) {
                try {
                    var parsed = JSON.parse(itemsJsonInput.value);
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        quotationItems = parsed;
                        itemsJsonInput.value = JSON.stringify(quotationItems);
                    }
                } catch (e) { }
            }
            if (quotationItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the quotation.');
                return;
            }
            // Wholesale orders are sent to admin, no reseller selection required.
            var isOrderForm = form && form.id === 'order-form';
            if (!isOrderForm) {
                var r = document.getElementById('reseller_id');
                if (!r || !r.value) {
                    e.preventDefault();
                    alert('Please select a customer.');
                    return;
                }
            }
        });
    }

    /* ---------- AUTO-SAVE ---------- */
    function initAutoSave() {
        var form = document.getElementById('order-form') || document.getElementById('quotation-form');
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

    /* ---------- QUILL EDITOR ---------- */
    function initQuillEditor() {
        var editorEl = document.getElementById('notes_editor');
        var hiddenInput = document.getElementById('notes_input');
        if (editorEl && hiddenInput && typeof Quill !== 'undefined') {
            var quill = new Quill(editorEl, {
                theme: 'snow',
                placeholder: 'Enter any additional notes, terms, or instructions...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['blockquote', 'code-block'],
                        ['link'],
                        ['clean']
                    ]
                }
            });
            if (hiddenInput.value) {
                quill.root.innerHTML = hiddenInput.value;
            }
            quill.on('text-change', function () {
                hiddenInput.value = quill.root.innerHTML;
            });
            var form = document.getElementById('order-form') || document.getElementById('quotation-form');
            if (form) {
                form.addEventListener('submit', function () {
                    hiddenInput.value = quill.root.innerHTML;
                });
            }
        }
    }

    /* ---------- DATE SYNC ---------- */
    function initDateSync() {
        var issueDate = document.getElementById('issue_date');
        var validUntil = document.getElementById('valid_until');
        if (issueDate && validUntil) {
            issueDate.addEventListener('change', function () {
                validUntil.min = this.value;
                if (validUntil.value && validUntil.value < this.value) {
                    validUntil.value = this.value;
                }
            });
        }
    }

    /* ---------- CART BADGE + QUOTATION (for product-card) ---------- */
    document.addEventListener('cartUpdated', function (e) {
        var c = e.detail?.count ?? e.detail?.[0]?.count;
        if (c !== undefined) {
            var badgeUpdate = typeof updateCartBadge === 'function' ? updateCartBadge : function () { };
            try { badgeUpdate(c); } catch (e) { }
            var h = document.querySelector('[data-cart-header-count]');
            if (h) h.textContent = c + ' ' + (c === 1 ? 'item' : 'items');
        }
    });
    document.addEventListener('livewire:init', function () {
        if (window.Livewire) {
            Livewire.on('cartUpdated', function (data) {
                var c = data.count ?? data[0]?.count;
                if (c !== undefined) {
                    updateCartBadge(c);
                    var h = document.querySelector('[data-cart-header-count]');
                    if (h) h.textContent = c + ' ' + (c === 1 ? 'item' : 'items');
                }
            });
        }
    });

    function updateCartBadge(cartCount) {
        var count = parseInt(cartCount, 10) || 0;
        document.querySelectorAll('[data-cart-badge]').forEach(function (badge) {
            badge.textContent = count;
            if (count > 0) badge.classList.remove('hidden');
            else badge.classList.add('hidden');
        });
    }

    function getQtyVal(el){ if(!el) return 1; if(el.tagName==='INPUT') return parseInt(el.value)||1; return parseInt(el.textContent)||1; }
    function setQtyVal(el,val){ if(!el) return; if(el.tagName==='INPUT') el.value=val; else el.textContent=val; }
    // Direct input change sync
   window.toggleQuoteItem = async function (btn) {
        if(btn.dataset.loading==='true') return;
        var productId = btn.dataset.quote;
        if (!productId) return;
        var qtyEl2 = document.getElementById('qty-' + productId);
        var selectedQty = qtyEl2 ? getQtyVal(qtyEl2) : 1;
        selectedQty = Math.min(50, Math.max(1, selectedQty));
        var url = (window.APP_CONFIG?.appUrl||'') + '/quote-cart/quantity/' + productId;
        if(!window.APP_CONFIG?.appUrl) url='/quote-cart/quantity/'+productId;
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
        btn.dataset.loading='true'; btn.disabled=true;
        try {
            var response = await fetch(url, {
                method: 'POST',
                headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrfToken,'X-Requested-With':'XMLHttpRequest'},
                body: JSON.stringify({ _token: csrfToken, quantity: selectedQty }),
                credentials: 'same-origin'
            });
            if (!response.ok) {
                throw new Error((await response.text()) || 'Request failed');
            }
            var data = await response.json();
            if (data.success===false) {throw new Error(data.message || 'Failed to update quantity' );}
            var cartCount = data.cartCount ?? data.count ?? 0;
            var newQty = 1;
            var qEl = document.getElementById(`qty-${productId}`);
            if (qEl) { setQtyVal(qEl, newQty); }
            btn.dataset.added='true';
           // Update cart badge
           updateCartBadge(cartCount);
           var headerCountEl = document.querySelector('[data-cart-header-count]');
           if (headerCountEl) {
               headerCountEl.textContent =
                   `${cartCount} ${cartCount === 1 ? 'item' : 'items'}`;
           }

           // --------------------------------
           // Show quantity wrapper
           // --------------------------------

           var cardQtyWrap =
               document.getElementById('qty-wrap-' + productId);

           if (cardQtyWrap) {
               cardQtyWrap.classList.remove('hidden');
               cardQtyWrap.classList.add('flex');
           }

           // --------------------------------
           // Update button
           // --------------------------------

           btn.textContent = 'Add To Cart';

           btn.classList.remove(
               'bg-bwtblue',
               'hover:bg-bwtblue2',
               'bg-blue-400',
               'hover:bg-blue-500'
           );

           btn.classList.add(
               'bg-green-600',
               'hover:bg-green-700'
           );

           // --------------------------------
           // Success Toast
           // --------------------------------

           if (window.showToast) {
               window.showToast(
                   data.message || 'Quantity updated successfully',
                   'success'
               );
           }

       } catch (e) {

           console.error('Quantity update error:', e);

           if (window.showToast) {
               window.showToast(
                   e.message || 'Failed to update quantity',
                   'error'
               );
           }

       } finally {

           btn.dataset.loading = 'false';
           btn.disabled = false;
       }
};

    /* ---------- HELPERS ---------- */
    function setVal(id, val) {
        var el = document.getElementById(id);
        if (el) el.value = val;
    }

})();
