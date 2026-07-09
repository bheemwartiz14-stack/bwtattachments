import "./libs/lexxy";
import $ from 'jquery';

window.$ = window.jQuery = $;

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

// Select All text on focus
$(document).on('focus', 'input[data-select-all]', function() {
    $(this).select();
});
