(function () {
    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    ready(function () {
        initCategorySubcategory();
        initTrix();
    });
})();

function initCategorySubcategory() {
    var category = document.getElementById('category_id');
    var subcategory = document.getElementById('subcategory_id');
    if (!category || !subcategory) return;

    function loadSubcategories(id, selected) {
        subcategory.innerHTML = '<option value="">Loading...</option>';
        subcategory.disabled = true;

        if (!id) {
            subcategory.innerHTML = '<option value="">Select Category first</option>';
            subcategory.disabled = true;
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/admin/categories/' + id + '/subcategories');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                subcategory.innerHTML = '<option value="">Select Subcategory</option>';
                if (res.data) {
                    Object.keys(res.data).forEach(function (id) {
                        var opt = document.createElement('option');
                        opt.value = id;
                        opt.textContent = res.data[id];
                        if (selected && id === selected) opt.selected = true;
                        subcategory.appendChild(opt);
                    });
                }
                subcategory.disabled = false;
            }
        };
        xhr.send();
    }

    category.addEventListener('change', function () {
        loadSubcategories(this.value);
    });

    var selected = subcategory.getAttribute('data-selected');
    if (category.value && selected) {
        loadSubcategories(category.value, selected);
    }
}

function initTrix() {
    var editor = document.querySelector('trix-editor');
    if (!editor) return;
    var inputId = editor.getAttribute('input');
    var input = document.getElementById(inputId);
    if (!input) return;
    editor.addEventListener('trix-initialize', function () {
        if (input.value && !editor.editor.getValue()) {
            editor.editor.loadHTML(input.value);
        }
    });
}

document.addEventListener('change', function (e) {
    var input = e.target;
    var selector = input.getAttribute('data-file-preview');
    if (!selector) return;
    var preview = document.querySelector(selector);
    var file = input.files && input.files[0];
    if (preview && file) {
        var reader = new FileReader();
        reader.onload = function (ev) {
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
            var placeholder = preview.parentElement.querySelector('[id$="-placeholder"]');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});
