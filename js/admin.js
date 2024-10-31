jQuery(document).ready(function($){
  initializeOptions();
  $('.pf-color-picker').wpColorPicker();
  $("#n_options").change(changeNumOptions);


  function initializeOptions() {
    var numOptions = $("#n_options").val();
    var rows = $('tr.option');

    for (var i=0; i<5; i++) {
      if (i < numOptions) {
        $(rows[i]).show();
      } else {
        $(rows[i]).hide();
      }
    }
  }

  function changeNumOptions(e) {
    var numOptions = $(e.target).val();
    var rows = $('tr.option');

    for (var i=0; i<5; i++) {
      if (i < numOptions) {
        $(rows[i]).show();
      } else {
        $(rows[i]).hide();
      }
    }
  }
})
