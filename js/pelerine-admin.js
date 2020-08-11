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
    // Color picker widget.
    $('#html5colorpicker input').change(function() {
      var picked = $(this).val();
      var textAreaTxt = $("#edit-custom-css").val();
      var cursorPos = $("#edit-custom-css").prop('selectionStart');
      // Show hex value next to picker.
      $('#html5colorpicker span').text(picked);
      // Put into textarea at cursor position.
      $("#edit-custom-css").val(textAreaTxt.substring(0, cursorPos) + picked + textAreaTxt.substring(cursorPos));
    });
  });
})(jQuery);
