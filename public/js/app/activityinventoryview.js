App.ActivityInventoryView = Backbone.View.extend({
  initialize: function() {
    this.collection = new App.Tasks();
    this.collection.fetch({
      success: _.bind(function(resp, status, xhr) {
        this.render(); 
      }, this)
    });
  }, 

  render: function() {
    this.collection.each(this.renderTask, this);
  },

  renderTask: function(model) {
    var taskView = new App.TaskView({model: model});
    this.$el.append(taskView.el);
  }
});
