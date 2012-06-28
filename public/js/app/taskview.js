App.TaskView = Backbone.View.extend({
  tagname: 'div',

  className: 'inventory-row',

  template: _.template($("#pomtrac-task-tmpl").html()),

  initialize: function() {
    this.render();
  },

  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    this.summaryInput = this.$('.summary');
    this.estimateInput = this.$('.estimate');
    return this;
  },

  events: {
    'change .textinput': 'update',
    'click .strike': 'strike'
  },

  update: function() {
    this.model.save({
      'summary': this.summaryInput.val(),
      'estimate': this.estimateInput.val()
    });
  },

  strike: function() {
    this.remove();
    this.model.destroy();
  }
});
