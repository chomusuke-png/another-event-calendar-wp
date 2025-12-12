jQuery(document).ready(function($) {

    // 1. Delegación de eventos para navegación (AJAX)
    // Usamos 'on' en document body porque el calendario se reemplaza a sí mismo
    $('body').on('click', '.aec-nav-btn', function(e) {
        e.preventDefault();
        
        var $wrapper = $(this).closest('.aec-wrapper');
        var year = $(this).data('year');
        var month = $(this).data('month');
        var $loading = $wrapper.find('.aec-loading');

        $loading.show(); // Mostrar spinner

        $.ajax({
            url: aec_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'aec_load_calendar',
                year: year,
                month: month,
                nonce: aec_vars.nonce
            },
            success: function(response) {
                // Reemplazamos todo el contenedor .aec-wrapper con el nuevo HTML
                $wrapper.replaceWith(response);
            },
            error: function() {
                alert('Error al cargar el calendario');
                $loading.hide();
            }
        });
    });

    // 2. Filtro de Categorías (Visual)
    $('body').on('change', '#aec-filter-category', function() {
        var selectedCat = $(this).val();
        var $calendar = $(this).closest('.aec-wrapper');

        if (selectedCat === 'all') {
            // Mostrar todo
            $calendar.find('.aec-event-icon').show();
            $calendar.find('.aec-tooltip-item').show();
        } else {
            // Ocultar todo primero
            $calendar.find('.aec-event-icon').hide();
            $calendar.find('.aec-tooltip-item').hide();
            
            // Mostrar solo la clase coincidente
            $calendar.find('.aec-event-icon.' + selectedCat).show();
            $calendar.find('.aec-tooltip-item.' + selectedCat).show();
        }
    });

});