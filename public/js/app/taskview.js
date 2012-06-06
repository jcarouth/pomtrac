App.TaskView = Backbone.View.extend({
  tagName: "li",

  template: _.template("<li><%= summary %></li>"),

  initialize: function() {
    this.render();
  },

  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    return this;
  }
});
