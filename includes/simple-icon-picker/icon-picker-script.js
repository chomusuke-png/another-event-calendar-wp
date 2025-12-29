jQuery(document).ready(function($) {
    
    // --- Lógica de Selección y Borrado (Igual que antes) ---
    $('body').on('click', '.sip-icon-option', function() {
        var $this = $(this);
        var $wrapper = $this.closest('.sip-icon-picker-wrapper');
        $wrapper.find('.sip-icon-option').removeClass('selected');
        $this.addClass('selected');
        $wrapper.find('.sip-icon-input-target').val($this.data('value')).trigger('change');
    });

    $('body').on('click', '.sip-remove-icon-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).closest('.sip-icon-picker-wrapper');
        $wrapper.find('.sip-icon-option').removeClass('selected');
        $wrapper.find('.sip-icon-input-target').val('').trigger('change');
    });

    // --- NUEVO: Navegación por Pestañas ---
    $('body').on('click', '.sip-tab-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var targetId = $btn.data('target'); // ej: "oficina-y-negocios"
        var $wrapper = $btn.closest('.sip-icon-picker-wrapper');
        var $container = $wrapper.find('.sip-icon-grid-container');
        var $targetSection = $wrapper.find('#sip-section-' + targetId);

        // 1. Activar pestaña visualmente
        $wrapper.find('.sip-tab-btn').removeClass('active');
        $btn.addClass('active');

        // 2. Calcular posición para scroll
        if ($targetSection.length) {
            // Posición actual del scroll + posición relativa del elemento
            var scrollTop = $container.scrollTop() + $targetSection.position().top;
            
            $container.animate({
                scrollTop: scrollTop
            }, 300); // 300ms de animación suave
        }
    });

    // --- Buscador ---
    $('body').on('keyup', '.sip-icon-search', function() {
        var term = $(this).val().toLowerCase();
        var $wrapper = $(this).closest('.sip-icon-picker-wrapper');
        var $options = $wrapper.find('.sip-icon-option');
        var foundTotal = false;

        $options.each(function() {
            var $el = $(this);
            var keywords = $el.data('keywords');
            var iconClass = $el.data('value');

            if (keywords.indexOf(term) > -1 || iconClass.indexOf(term) > -1) {
                $el.show();
                foundTotal = true;
            } else {
                $el.hide();
            }
        });

        // Ocultar secciones vacías durante la búsqueda
        $wrapper.find('.sip-icon-section').each(function() {
            if($(this).find('.sip-icon-option:visible').length === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        if (foundTotal) {
            $wrapper.find('.sip-no-results').hide();
        } else {
            $wrapper.find('.sip-no-results').show();
        }
    });
});