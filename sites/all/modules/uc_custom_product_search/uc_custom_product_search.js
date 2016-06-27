(function ($) {
    Drupal.behaviors.uc_custom_product_search = {
        attach: function(context, settings) {
            $('input.check-all',context).change(function () {
                var currentId = $(this).attr('id');
                var tid = currentId.substring(9,currentId.length);
                console.log(tid);
                var isChecked = $(this).attr("checked");
                $('input:checkbox[id^="edit-children-' + tid + '"]').each(function(){
                    $(this).attr("checked", isChecked);
                });
            });
        }
    }
})(jQuery);