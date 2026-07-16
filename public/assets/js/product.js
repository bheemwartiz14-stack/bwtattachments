/**
 * --------------------------------------------------------------------------
 * BWT Attachments — Product Module
 * --------------------------------------------------------------------------
 * Handles: category/subcategory chained selects, Trix editor sync,
 * favorite toggling, product image gallery, lightbox, and file preview.
 * Written with full jQuery.
 * --------------------------------------------------------------------------
 */

// Init on DOM ready
$(function () {
    initCategorySubcategory();
    initTrix();
    initProductGallery();
});

// Re-init gallery after Livewire navigation
$(document).on('livewire:navigated', function () {
    $('#lightbox').removeClass('opacity-100').addClass('opacity-0 pointer-events-none');
    $('body').css('overflow', '');
    initProductGallery();
});

/**
 * Category / Subcategory chained dropdowns
 * Loads subcategories via AJAX when a category is selected.
 */
function initCategorySubcategory() {
    var $category = $('#category_id');
    var $subcategory = $('#subcategory_id');
    if (!$category.length || !$subcategory.length) return;

    function loadSubcategories(id, selected) {
        $subcategory.html('<option value="">Loading...</option>').prop('disabled', true);

        if (!id) {
            $subcategory.html('<option value="">Select Category first</option>').prop('disabled', true);
            return;
        }

        $.get('/admin/categories/' + id + '/subcategories', function (res) {
            $subcategory.html('<option value="">Select Subcategory</option>').prop('disabled', false);
            if (res.data) {
                $.each(res.data, function (id, name) {
                    $subcategory.append($('<option>', { value: id, text: name }));
                });
                if (selected) $subcategory.val(selected);
            }
        });
    }

    var selected = $subcategory.data('selected');
    if ($category.val() && selected) {
        loadSubcategories($category.val(), selected);
    }

    $category.on('change', function () {
        loadSubcategories($(this).val());
    });
}

function initTrix() {
    var $editor = $('trix-editor');
    console.log('zxcxzcxzxzc');
    // if (!$editor.length) return;
    // var inputId = $editor.attr('input');
    // var $input = $('#' + inputId);
    // if (!$input.length) return;
    // $editor.on('trix-initialize', function () {
    //     if ($input.val() && !this.editor.getValue()) {
    //         this.editor.loadHTML($input.val());
    //     }
    // });
}

window.toggleFavorite = function(btn) {
    var $btn = $(btn);
    var productId = $btn.data('favorite');
    var url = '/favorites/toggle/' + productId;
    var csrf = $('meta[name="csrf-token"]').attr('content');

    $.post(url, { _token: csrf }, function (res) {
        $btn.data('favorited', res.favorited);
        $btn.attr('title', res.favorited ? 'Remove from favorites' : 'Add to favorites');
        $btn.find('svg').toggleClass('hidden');
        if (window.showToast) {
            window.showToast(res.message, res.favorited ? 'success' : 'info');
        }
    }).fail(function () {
        if (window.showToast) {
            window.showToast('Failed to update favorite', 'error');
        }
    });
};

function initProductGallery() {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;

    var $images = $gallery.find('.gallery-image');
    var $dots = $gallery.find('.gallery-dot');
    var total = $images.length;

    $gallery.data('current', 0);

    function showImage(index) {
        if (index < 0) index = total - 1;
        if (index >= total) index = 0;
        $gallery.data('current', index);
        $images.each(function (i) {
            $(this).toggle(i === index);
        });
        $dots.each(function (i) {
            $(this).toggleClass('bg-white w-5', i === index).toggleClass('bg-white/50 w-2', i !== index);
        });
        var $thumbs = $gallery.find('.gallery-thumb');
        $thumbs.each(function (i) {
            $(this).toggleClass('ring-2 ring-bwtblue', i === index).toggleClass('ring-0 hover:ring-1 hover:ring-slate-300', i !== index);
        });
    }

    $gallery.find('.gallery-prev').on('click', function () {
        showImage($gallery.data('current') - 1);
    });

    $gallery.find('.gallery-next').on('click', function () {
        showImage($gallery.data('current') + 1);
    });

    $gallery.find('.gallery-dot').on('click', function () {
        showImage($(this).data('index'));
    });

    $gallery.find('.gallery-expand').on('click', function () {
        var idx = $gallery.data('current');
        $('#lightboxImage').attr('src', $images.eq(idx).attr('src'));
        $('#lightbox').removeClass('opacity-0 pointer-events-none').addClass('opacity-100');
        $('body').css('overflow', 'hidden');
    });
}

window.showGalleryImage = function (index) {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var $images = $gallery.find('.gallery-image');
    var $dots = $gallery.find('.gallery-dot');
    var $thumbs = $gallery.find('.gallery-thumb');
    var total = $images.length;
    if (index < 0) index = 0;
    if (index >= total) index = total - 1;
    $gallery.data('current', index);
    $images.each(function (i) { $(this).toggle(i === index); });
    $dots.each(function (i) {
        $(this).toggleClass('bg-white w-5', i === index).toggleClass('bg-white/50 w-2', i !== index);
    });
    $thumbs.each(function (i) {
        $(this).toggleClass('ring-2 ring-bwtblue', i === index).toggleClass('ring-0 hover:ring-1 hover:ring-slate-300', i !== index);
    });
}

window.galleryCloseLightbox = function () {
    $('#lightbox').removeClass('opacity-100').addClass('opacity-0 pointer-events-none');
    $('body').css('overflow', '');
};

window.galleryPrevLightbox = function () {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var idx = $gallery.data('current') - 1;
    var $images = $gallery.find('.gallery-image');
    var total = $images.length;
    if (idx < 0) idx = total - 1;
    $gallery.data('current', idx);
    $images.each(function (i) { $(this).toggle(i === idx); });
    $gallery.find('.gallery-dot').each(function (i) {
        $(this).toggleClass('bg-white w-5', i === idx).toggleClass('bg-white/50 w-2', i !== idx);
    });
    $('#lightboxImage').attr('src', $images.eq(idx).attr('src'));
};

window.galleryNextLightbox = function () {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var idx = $gallery.data('current') + 1;
    var $images = $gallery.find('.gallery-image');
    var total = $images.length;
    if (idx >= total) idx = 0;
    $gallery.data('current', idx);
    $images.each(function (i) { $(this).toggle(i === idx); });
    $gallery.find('.gallery-dot').each(function (i) {
        $(this).toggleClass('bg-white w-5', i === idx).toggleClass('bg-white/50 w-2', i !== idx);
    });
    $('#lightboxImage').attr('src', $images.eq(idx).attr('src'));
};

$(document).on('keydown', function (e) {
    var $lb = $('#lightbox');
    if ($lb.hasClass('opacity-0')) return;
    if (e.key === 'Escape') window.galleryCloseLightbox();
    else if (e.key === 'ArrowLeft') window.galleryPrevLightbox();
    else if (e.key === 'ArrowRight') window.galleryNextLightbox();
});

$(document).on('change', function (e) {
    var $input = $(e.target);
    var selector = $input.data('file-preview');
    if (!selector) return;
    var $preview = $(selector);
    var file = $input[0].files && $input[0].files[0];
    if ($preview.length && file) {
        var reader = new FileReader();
        reader.onload = function (ev) {
            $preview.attr('src', ev.target.result).removeClass('hidden');
            var $placeholder = $preview.parent().find('[id$="-placeholder"]');
            if ($placeholder.length) $placeholder.addClass('hidden');
        };
        reader.readAsDataURL(file);
    }
});
