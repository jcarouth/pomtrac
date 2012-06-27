$(function() {
  var ActivityInventory = null;

  $.ajaxSetup({
    'dataType': 'json'
  });

  Backbone.emulateHTTP = true;



  ActivityInventory = new App.MainView();
});
