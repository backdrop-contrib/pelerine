(function ($) {
  "use strict";
  $(document).ready(function() {
    var imgUrl = Backdrop.settings.basePath + $('#edit-image-url').val();
    $('#preview-wrapper').css('background-image', 'url("' + imgUrl + '")');
    // Change dynamically.
    $('#edit-image-url').change(function () {
      imgUrl = Backdrop.settings.basePath + this.options[this.selectedIndex].value;
      $('#preview-wrapper').css('background-image', 'url("' + imgUrl + '")');
    });
  });
})(jQuery);
