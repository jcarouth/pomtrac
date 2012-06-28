window.App = {};

App.init = function() {
  var ActivityInventory = null;

  $.ajaxSetup({
    'dataType': 'json'
  });

  Backbone.emulateHTTP = true;

  ActivityInventory = new App.ActivityInventoryView({ 
    el: $('#activityinventory')
  });
};
