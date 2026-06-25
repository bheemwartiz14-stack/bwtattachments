$(function() {
    var companies = window.__userCreateCompanies || [];
    var storeRoute = window.__userCreateStoreRoute || '';
    var csrfToken = window.__csrfToken || '';
    var initialCompany = window.__initialCompany || null;
    var selectedCompany = initialCompany;
    var searchQuery = '';

    function filteredCompanies() {
        if (!searchQuery) return companies;
        var q = searchQuery.toLowerCase();
        return companies.filter(function(c) {
            return (c.company_name || '').toLowerCase().includes(q);
        });
    }

    function renderDropdown() {
        var list = $('#company-dropdown-list');
        if (!list.length) return;
        list.empty();
        var filtered = filteredCompanies();
        if (filtered.length === 0) {
            list.html('<div class="px-4 py-2 text-sm text-gray-400">No companies found</div>');
            return;
        }
        $.each(filtered, function(_, company) {
            var item = $(
                '<div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer company-option">' +
                    company.company_name +
                '</div>'
            );
            item.data('company', company);
            item.on('click', function() {
                selectedCompany = company;
                $('#company-search').val(company.company_name);
                $('#company-dropdown').addClass('hidden');
            });
            list.append(item);
        });
    }

    $('#company-search').on('input', function() {
        searchQuery = $(this).val();
        renderDropdown();
        $('#company-dropdown').removeClass('hidden');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#company-search, #company-dropdown').length) {
            $('#company-dropdown').addClass('hidden');
        }
    });
});
