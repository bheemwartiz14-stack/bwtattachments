$(function () {
    initCategorySubcategory();
    initCurrencyConverter();
});

$(document).on('livewire:navigated', function () {
    $('#lightbox').removeClass('opacity-100').addClass('opacity-0 pointer-events-none');
    $('body').css('overflow', '');
});

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

function initCurrencyConverter() {
    var $rmb = $('#ddp_price_rmb');
    var $eur = $('#ddp_price');
    if (!$rmb.length || !$eur.length) return;

    var rate = null;
    var $hint = $('#currency-rate-hint');

    function convert() {
        var val = parseFloat($rmb.val());
        if (rate && !isNaN(val) && val > 0) {
            $eur.val((val * rate).toFixed(2));
        } else {
            $eur.val('');
        }
    }

    $.get('/admin/currency/rate/CNY/EUR', function (res) {
        if (res && res.rate) {
            rate = res.rate;
            if ($hint.length) {
                $hint.text('1 CNY = ' + rate + ' EUR');
            }
            convert();
        }
    }).fail(function () {
        if ($hint.length) {
            $hint.text('Could not load exchange rate. Enter the EUR price manually.');
        }
    });

    $rmb.on('input change', convert);
}

function updateCartBadge(cartCount) {
    var count = parseInt(cartCount, 10) || 0;
    document.querySelectorAll('[data-cart-badge]').forEach(function (badge) {
        badge.textContent = count;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
}

window.toggleQuoteItem = async function (btn) {
    if (btn.dataset.loading === 'true') return;
    var productId = btn.dataset.quote;
    if (!productId) return;

    var url = '/quote-cart/toggle/' + productId;
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    btn.dataset.loading = 'true';
    btn.disabled = true;
    var originalText = btn.textContent;
    var originalClasses = btn.className;

    // Session carts are server-side, so they cannot be changed while offline.
    if (window.BWTPWA && !window.BWTPWA.isOnline()) {
        if (window.showToast) window.showToast('Reconnect to update your quotation cart.', 'error');
        btn.dataset.loading = 'false';
        btn.disabled = false;
        return;
    }

    try {
        var response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ _token: csrfToken }),
            credentials: 'same-origin'
        });

        if (response.status === 401) {
            if (window.showToast) window.showToast('Please login to use quotation cart.', 'error');
            window.location.href = '/login';
            return;
        }
        if (response.status === 419) {
            if (window.showToast) window.showToast('Session expired. Please refresh and try again.', 'error');
            return;
        }
        if (!response.ok) {
            var errText = await response.text();
            throw new Error(errText || 'Request failed');
        }

        var data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Unable to update quotation.');
        }

        var added = !!data.added;
        var cartCount = data.cartCount !== undefined ? data.cartCount : (data.count !== undefined ? data.count : 0);

        updateCartBadge(cartCount);
        // Update cart header count if on cart page
        var headerCountEl = document.querySelector('[data-cart-header-count]');
        if (headerCountEl) {
            headerCountEl.textContent = cartCount + ' ' + (cartCount === 1 ? 'item' : 'items');
        }

        btn.dataset.added = added ? 'true' : 'false';
        btn.textContent = added ? '✓ Added To Quotation' : 'Add To Quotation';
        if (added) {
            btn.classList.remove('bg-bwtblue', 'hover:bg-bwtblue2');
            btn.classList.add('bg-blue-400', 'hover:bg-blue-500');
        } else {
            btn.classList.remove('bg-blue-400', 'hover:bg-blue-500');
            btn.classList.add('bg-bwtblue', 'hover:bg-bwtblue2');
        }

        // If removed from cart page, remove the table row
        if (!added) {
            var tr = btn.closest('tr');
            if (tr && tr.closest('table')) {
                tr.style.transition = 'opacity 0.2s';
                tr.style.opacity = '0';
                setTimeout(function() {
                    tr.remove();
                    var tbody = document.querySelector('table tbody');
                    if (tbody && tbody.querySelectorAll('tr').length === 0 && window.location.pathname === '/cart') {
                        location.reload();
                    }
                }, 200);
            }
        }

        if (window.showToast) window.showToast(data.message || (added ? 'Added to quotation' : 'Removed from quotation'), added ? 'success' : 'info');
    } catch (error) {
        console.error(error);
        if (window.showToast) window.showToast(error.message || 'Failed to update quotation cart', 'error');
    } finally {
        btn.dataset.loading = 'false';
        btn.disabled = false;
    }
};

window.toggleFavorite = function (btn) {
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

function galleryShowImage(index) {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var $images = $gallery.find('.gallery-image');
    var total = $images.length;
    if (!total) return;
    if (index < 0) index = total - 1;
    if (index >= total) index = 0;
    $gallery.data('current', index).attr('data-current', index);
    $images.each(function (i) { $(this).toggle(i === index); });
    $gallery.find('.gallery-dot').each(function (i) {
        $(this).toggleClass('bg-white w-5', i === index).toggleClass('bg-white/50 w-2', i !== index);
    });
    $gallery.find('.gallery-thumb').each(function (i) {
        $(this).toggleClass('ring-2 ring-bwtblue', i === index).toggleClass('ring-0 hover:ring-1 hover:ring-slate-300', i !== index);
    });
}

$(document).on('click', '.gallery-prev', function () {
    var $g = $('#productGallery');
    galleryShowImage((parseInt($g.attr('data-current')) || 0) - 1);
});

$(document).on('click', '.gallery-next', function () {
    var $g = $('#productGallery');
    galleryShowImage((parseInt($g.attr('data-current')) || 0) + 1);
});

$(document).on('click', '.gallery-dot', function () {
    galleryShowImage($(this).data('index'));
});

$(document).on('click', '.gallery-expand', function () {
    console.log('jquery gallery-expand clicked');
    var $gallery = $('#productGallery');
    if (!$gallery.length) { console.log('no productGallery'); return; }
    var $images = $gallery.find('.gallery-image');
    if (!$images.length) return;
    var idx = parseInt($gallery.attr('data-current')) || 0;
    $('#lightboxImage').attr('src', $images.eq(idx).attr('src'));
    $('#lightbox').removeClass('opacity-0 pointer-events-none').addClass('opacity-100');
    $('body').css('overflow', 'hidden');
});

window.openLightbox = function () {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var $images = $gallery.find('.gallery-image');
    if (!$images.length) return;
    var idx = parseInt($gallery.attr('data-current')) || 0;
    $('#lightboxImage').attr('src', $images.eq(idx).attr('src'));
    $('#lightbox').removeClass('opacity-0 pointer-events-none').addClass('opacity-100');
    $('body').css('overflow', 'hidden');
};

window.showGalleryImage = function (index) {
    galleryShowImage(index);
};

window.galleryCloseLightbox = function () {
    $('#lightbox').removeClass('opacity-100').addClass('opacity-0 pointer-events-none');
    $('body').css('overflow', '');
};

window.galleryPrevLightbox = function () {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var idx = (parseInt($gallery.attr('data-current')) || 0) - 1;
    var $images = $gallery.find('.gallery-image');
    var total = $images.length;
    if (idx < 0) idx = total - 1;
    if (idx >= total) idx = 0;
    $gallery.data('current', idx).attr('data-current', idx);
    $images.each(function (i) { $(this).toggle(i === idx); });
    $gallery.find('.gallery-dot').each(function (i) {
        $(this).toggleClass('bg-white w-5', i === idx).toggleClass('bg-white/50 w-2', i !== idx);
    });
    $('#lightboxImage').attr('src', $images.eq(idx).attr('src'));
};

window.galleryNextLightbox = function () {
    var $gallery = $('#productGallery');
    if (!$gallery.length) return;
    var idx = (parseInt($gallery.attr('data-current')) || 0) + 1;
    var $images = $gallery.find('.gallery-image');
    var total = $images.length;
    if (idx < 0) idx = total - 1;
    if (idx >= total) idx = 0;
    $gallery.data('current', idx).attr('data-current', idx);
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
