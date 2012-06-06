App.Task = Backbone.Model.extend({
  defaults: function() {
    return {
        summary: "",
        addedDate: moment()
    };
  },
  urlRoot: "api/tasks"
});
