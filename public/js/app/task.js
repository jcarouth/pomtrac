App.Task = Backbone.Model.extend({
  urlRoot: "tasks/",
  defaults: function() {
    return {
      id: "",
      summary: "",
      createdDate: moment(),
      estimate: null
    };
  }
});
