
import "./libs/patch-custom-elements";
import "./libs/lexxy";
// Dark Mode
function initDarkMode() {
    const dark = localStorage.getItem('dark') === 'true' ||
        (!localStorage.getItem('dark') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    if (dark) document.documentElement.classList.add('dark');
}

function toggleDarkMode() {
    const dark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('dark', dark);
}

function reapplyDarkMode() {
    const dark = localStorage.getItem('dark') === 'true';
    if (dark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

$(document).on('click', '[data-toggle-dark]', toggleDarkMode);
$(document).on('toggle-dark-mode', toggleDarkMode);

document.addEventListener('livewire:navigated', reapplyDarkMode);

// Sidebar
$(document).on('click', '[data-toggle-sidebar]', function() {
    const sidebar = document.querySelector('[data-sidebar]');
    if (sidebar) {
        const isOpen = sidebar.classList.toggle('open');
        sidebar.style.display = isOpen ? 'block' : '';
    }
});

$(document).on('toggle-sidebar', function() {
    const sidebar = document.querySelector('[data-sidebar]');
    if (sidebar) {
        const isOpen = sidebar.classList.toggle('open');
        sidebar.style.display = isOpen ? 'block' : '';
    }
});

$(function() {
    initDarkMode();
    const sidebar = document.querySelector('[data-sidebar]');
    if (sidebar && window.innerWidth >= 1024) {
        sidebar.classList.add('open');
        sidebar.style.display = 'block';
    }
});

// Sidebar backdrop click to close on mobile
$(document).on('click', '[data-sidebar-backdrop]', function() {
    const sidebar = document.querySelector('[data-sidebar]');
    if (sidebar) {
        sidebar.classList.remove('open');
        sidebar.style.display = 'none';
    }
    $(this).addClass('hidden');
});

// Dropdown
$(document).on('click', '[data-dropdown-toggle]', function(e) {
    e.stopPropagation();
    const target = $(this).attr('data-dropdown-toggle');
    $('#' + target).toggleClass('hidden');
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('[data-dropdown-toggle], [data-dropdown-menu]').length) {
        $('[data-dropdown-menu]').addClass('hidden');
    }
});

// Modal
window.openModal = function(id) {
    $('#' + id).removeClass('hidden').hide().fadeIn(200);
    $('#' + id + '-backdrop').removeClass('hidden').hide().fadeIn(200);
};

window.closeModal = function(id) {
    $('#' + id).fadeOut(200, function() { $(this).addClass('hidden'); });
    $('#' + id + '-backdrop').fadeOut(200, function() { $(this).addClass('hidden'); });
};

$(document).on('click', '[data-modal-show]', function() {
    openModal($(this).attr('data-modal-show'));
});
$(document).on('click', '[data-modal-hide]', function() {
    closeModal($(this).attr('data-modal-hide'));
});
$(document).on('click', '[data-modal-backdrop]', function() {
    closeModal($(this).attr('data-modal-backdrop'));
});

// Toast
window.showToast = function(message, type) {
    type = type || 'success';
    const toast = $('#toast');
    if (!toast.length) return;
    toast.find('[data-toast-message]').text(message);
    toast.removeClass('hidden').attr('data-toast-type', type);
    toast.css({ opacity: 0, transform: 'translateY(-10px)' }).animate({ opacity: 1, transform: 'translateY(0)' }, 300);
    setTimeout(function() {
        toast.animate({ opacity: 0, transform: 'translateY(-10px)' }, 300, function() {
            toast.addClass('hidden');
        });
    }, 4000);
};

$(document).on('click', '[data-toast-close]', function() {
    const toast = $('#toast');
    toast.animate({ opacity: 0 }, 200, function() { toast.addClass('hidden'); });
});

// Password Generator
$(document).on('click', '[data-generate-password]', function() {
    const input = $(this).closest('[data-password-wrapper]').find('[data-password-input]');
    if (!input.length) return;
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$';
    let pwd = '';
    for (let i = 0; i < 10; i++) pwd += chars[Math.floor(Math.random() * chars.length)];
    input.val(pwd);
});

$(document).on('click', '[data-toggle-password]', function() {
    const input = $(this).closest('[data-password-wrapper]').find('[data-password-input]');
    if (!input.length) return;
    const type = input.attr('type') === 'password' ? 'text' : 'password';
    input.attr('type', type);
    $(this).find('[data-show-icon], [data-hide-icon]').toggleClass('hidden');
});

$(document).on('click', '[data-copy-password]', function() {
    const input = $(this).closest('[data-password-wrapper]').find('[data-password-input]');
    if (!input.length) return;
    navigator.clipboard.writeText(input.val()).then(function() {
        const copied = $(this).closest('[data-password-wrapper]').find('[data-copied]');
        if (copied.length) {
            copied.removeClass('hidden');
            setTimeout(function() { copied.addClass('hidden'); }, 2000);
        }
    }.bind(this));
});



// File Upload Preview
$(document).on('change', '[data-file-preview]', function() {
    const previewTarget = $(this).attr('data-file-preview');
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        $(this).val('');
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        $(previewTarget).attr('src', e.target.result).removeClass('hidden');
        var placeholder = $(previewTarget).parent().find('[id$="-placeholder"]');
        if (!placeholder.length) placeholder = $(previewTarget + '-placeholder');
        placeholder.addClass('hidden');
        $(previewTarget).parent().find('[id="existing-logo"]').addClass('hidden');
    };
    reader.readAsDataURL(file);
});

// Slug Generator
$(document).on('input', '[data-slug-source]', function() {
    const target = $(this).attr('data-slug-source');
    if ($(target).attr('data-slug-locked')) return;
    $(target).val($(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''));
});

// View Toggle
$(document).on('click', '[data-view-toggle]', function() {
    const target = $(this).attr('data-view-toggle');
    const view = $(this).attr('data-view');
    $('[data-view]').removeClass('active');
    $(this).addClass('active');
    $(target).addClass('hidden');
    $(target + '-' + view).removeClass('hidden');
});

// Country sync (vat_id select -> hidden country_code / country_name mirrors)
// Centralized: delegated bindings survive wire:navigate DOM swaps and are
// bound once (no per-page inline scripts needed).
window.syncVatCountry = function() {
    var $select = $('#vat_id');
    if (!$select.length) {
        return;
    }
    var $selectedOption = $select.find('option:selected');
    var value = $select.val();
    if (!value || !$selectedOption.length) {
        $('#country_code').val('');
        $('#country_name').val('');
        return;
    }
    $('#country_code').val($selectedOption.data('iso-id') || '');
    $('#country_name').val($selectedOption.data('name') || '');
};

// Remove any legacy inline handlers, then bind once (delegated).
$(document)
    .off('change.vat-country', '#vat_id')
    .off('select2:clear.vat-country', '#vat_id');

// Country changed
$(document).on(
    'change.vat-country',
    '#vat_id',
    window.syncVatCountry
);

// Select2 clear (x) clicked
$(document).on(
    'select2:clear.vat-country',
    '#vat_id',
    window.syncVatCountry
);

document.addEventListener('livewire:navigated', window.syncVatCountry);

$(function() {
    window.syncVatCountry();
});

// Username auto-slug (name -> username), only on forms that have a username field.
$(document).off('input.username-slug', '#name').on('input.username-slug', '#name', function() {
    var $username = $('#username');
    if (!$username.length) return;
    const name = $(this).val();
    $username.val(name.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim().replace(/\s+/g, '-'));
});

// Password auto-fill for empty password inputs (create forms).
window.autofillPasswordInputs = function() {
    document.querySelectorAll('[data-password-input]').forEach(function(pwd) {
        if (pwd && !pwd.value) {
            var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$';
            var s = '';
            for (var i = 0; i < 10; i++) s += chars[Math.floor(Math.random() * chars.length)];
            pwd.value = s;
        }
    });
};

$(function() {
    window.autofillPasswordInputs();
});

document.addEventListener('livewire:navigated', window.autofillPasswordInputs);

// Select All text on focus
$(document).on('focus', 'input[data-select-all]', function() {
    $(this).select();
});

window.updateCartBadge = window.updateCartBadge || function(cartCount) {
    var count = parseInt(cartCount, 10) || 0;
    document.querySelectorAll('[data-cart-badge]').forEach(function(badge){
        badge.textContent = count;
        if(count>0) badge.classList.remove('hidden'); else badge.classList.add('hidden');
    });
};

document.addEventListener('cartUpdated', function(e){
    var c = e.detail?.count ?? e.detail?.[0]?.count ?? e.detail?.count;
    if(c!==undefined) {
        window.updateCartBadge(c);
        var h=document.querySelector('[data-cart-header-count]');
        if(h) h.textContent=c+' '+(c===1?'item':'items');
    }
});
document.addEventListener('livewire:init', function(){
    if(window.Livewire){
        Livewire.on('cartUpdated', function(data){
            var c = data.count ?? data[0]?.count;
            if(c!==undefined){
                window.updateCartBadge(c);
                var h=document.querySelector('[data-cart-header-count]');
                if(h) h.textContent=c+' '+(c===1?'item':'items');
            }
        });
    }
});



