App.Tasks = Backbone.Collection.extend({
  model: App.Task,

  url: "/tasks",

  comparator: function(task) {
    return moment(task.get("createdDate"));
  }
});
