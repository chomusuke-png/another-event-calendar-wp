jQuery(document).ready(function($) {
    
    // --- Lógica del Icon Picker ---

    // 1. Selección de Icono
    $('body').on('click', '.aec-icon-option', function() {
        var $this = $(this);
        var $wrapper = $this.closest('.aec-icon-picker-wrapper');
        var value = $this.data('value');

        // Visual
        $wrapper.find('.aec-icon-option').removeClass('selected');
        $this.addClass('selected');

        // Input
        $wrapper.find('.aec-icon-input-target').val(value).trigger('change');
    });

    // 2. Botón "Quitar"
    $('body').on('click', '.aec-remove-icon-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).closest('.aec-icon-picker-wrapper');

        // Visual: quitar selección
        $wrapper.find('.aec-icon-option').removeClass('selected');

        // Input: vaciar
        $wrapper.find('.aec-icon-input-target').val('').trigger('change');
    });

    // 3. Buscador en tiempo real (Filtro)
    $('body').on('keyup', '.aec-icon-search', function() {
        var term = $(this).val().toLowerCase();
        var $wrapper = $(this).closest('.aec-icon-picker-wrapper');
        var $options = $wrapper.find('.aec-icon-option');
        var found = false;

        $options.each(function() {
            var $el = $(this);
            var keywords = $el.data('keywords'); // "reunión equipo..."
            var iconClass = $el.data('value');   // "fa-solid fa-users"

            // Buscar en palabras clave Y en el nombre de la clase
            if (keywords.indexOf(term) > -1 || iconClass.indexOf(term) > -1) {
                $el.show();
                found = true;
            } else {
                $el.hide();
            }
        });

        // Mostrar mensaje si no hay resultados
        if (found) {
            $wrapper.find('.aec-no-results').hide();
        } else {
            $wrapper.find('.aec-no-results').show();
        }
    });

});