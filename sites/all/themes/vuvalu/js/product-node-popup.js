(function($) {
    /*
    Drupal.behaviors.quiaTallasPopup = {
        attach : function(context, settings) {
            $(".tallas-popup-trigger > a").magnificPopup({
                type:"inline",
                removalDelay: 300,
                mainClass: "mfp-fade",
                midClick: true
            });
        }
    };*/
    Drupal.behaviors.envioConsultaPopup = {
        attach : function(context, settings) {
            $(".webform-popup-trigger > a").magnificPopup({
                type:"inline",
                removalDelay: 300,
                mainClass: "mfp-fade",
                midClick: true
            });
        }
    };
})(jQuery);
