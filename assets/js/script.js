jQuery(document).ready(function($) {

    // 1. Navegación AJAX (Mantener igual)
    $('body').on('click', '.aec-nav-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).closest('.aec-wrapper');
        var year = $(this).data('year');
        var month = $(this).data('month');
        $wrapper.find('.aec-loading').show();

        $.ajax({
            url: aec_vars.ajax_url,
            type: 'POST',
            data: { action: 'aec_load_calendar', year: year, month: month, nonce: aec_vars.nonce },
            success: function(response) { $wrapper.replaceWith(response); },
            error: function() { alert('Error'); $wrapper.find('.aec-loading').hide(); }
        });
    });

    // 2. Filtro Categorías (Visual)
    $('body').on('change', '#aec-filter-category', function() {
        var selectedCat = $(this).val();
        var $calendar = $(this).closest('.aec-wrapper');
        var $details = $calendar.find('#aec-day-details');

        // Limpiar detalles al filtrar para evitar confusión
        $details.html('<div class="aec-details-placeholder">Selecciona un día.</div>');
        $calendar.find('.aec-day.selected').removeClass('selected');

        if (selectedCat === 'all') {
            $calendar.find('.aec-event-icon').show();
        } else {
            $calendar.find('.aec-event-icon').hide();
            $calendar.find('.aec-event-icon.' + selectedCat).show();
        }
    });

    // 3. NUEVO: Click en día para ver detalles
    $('body').on('click', '.aec-day', function() {
        if ($(this).hasClass('aec-empty')) return; // Ignorar espacios vacíos

        var $wrapper = $(this).closest('.aec-wrapper');
        var eventsData = $(this).data('events'); // Leer el JSON
        var $container = $wrapper.find('#aec-day-details');
        
        // Estilo visual de selección
        $wrapper.find('.aec-day').removeClass('selected');
        $(this).addClass('selected');

        // Renderizar
        if (!eventsData || eventsData.length === 0) {
            $container.html('<div class="aec-details-empty">No hay eventos este día.</div>');
            return;
        }

        var html = '<ul class="aec-details-list">';
        
        // Filtrar también la lista si hay un filtro activo
        var currentFilter = $wrapper.find('#aec-filter-category').val();

        var visibleCount = 0;
        $.each(eventsData, function(index, event) {
            // Verificar filtro
            if(currentFilter !== 'all' && ('cat-' + event.cat_id) !== currentFilter) {
                return; // saltar este evento
            }

            visibleCount++;
            html += '<li class="aec-detail-item" style="border-left: 4px solid ' + event.color + '">';
            html += '<div class="aec-detail-header">';
            html += '<i class="' + event.icon + '" style="color:' + event.color + '"></i> ';
            html += '<strong>' + event.title + '</strong>';
            html += '</div>';
            if(event.content) {
                html += '<p class="aec-detail-content">' + event.content + '</p>';
            }
            html += '</li>';
        });
        html += '</ul>';

        if(visibleCount === 0) {
            html = '<div class="aec-details-empty">No hay eventos de esta categoría hoy.</div>';
        }

        $container.html(html).hide().fadeIn(200);
    });

    // Auto-seleccionar el día actual al cargar si existe
    setTimeout(function(){
        $('.aec-day.is-today').trigger('click');
    }, 100);
});