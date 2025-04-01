$(document).ready(function() {
    // Initialize DataTables
    $('.table').each(function() {
        $(this).DataTable({
            responsive: true,
            paging: false,
            searching: false,
            info: false
        });
    });

    // Date pickers
    $('input[type="date"]').each(function() {
        if (!this.value) {
            const today = new Date().toISOString().split('T')[0];
            $(this).val(today);
        }
    });

    // Confirm before delete
    $('form[data-confirm]').on('submit', function(e) {
        if (!confirm($(this).data('confirm'))) {
            e.preventDefault();
        }
    });

    $('.select2-tags').select2({
        theme: 'bootstrap-5',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
        tags: true,
        tokenSeparators: [','],
        createTag: function(params) {
            return undefined; // Prevent free text entry
        },
        templateSelection: function(data, container) {
            // Add custom HTML for selected items
            return $('<span class="selected-tag"><span class="selected-text">' + data.text + '</span><span class="select2-selection__choice__remove" role="presentation">×</span></span>');
        }
    });
    
    // Better handling of remove button clicks
    $(document).on('click', '.select2-selection__choice__remove', function(e) {
        e.stopPropagation();
        var $choice = $(this).parent();
        var $select = $choice.closest('.select2-container').prev('select');
        var value = $choice.data('data').id;
        
        // Remove the option
        $select.find('option[value="' + value + '"]').prop('selected', false);
        
        // Update Select2
        $select.trigger('change');
    });
});