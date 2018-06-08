window.event = {

	el: '#event',

	data: function () {
		return {
			data: window.$data,
			event: window.$data.event,
			sections: []
		}
	},

	created: function () {

		var sections = [];

		_.forIn (this.$options.components, function (component, name) {

			var options = component.options || {};

			if (options.section) {
				sections.push (_.extend ({name: name, priority: 0}, options.section));
			}

		});

		this.$set ('sections', _.sortBy (sections, 'priority'));

		this.resource = this.$resource ('api/eventlist/event{/id}');
	},

	ready: function () {
		this.tab = UIkit.tab (this.$els.tab, {connect: this.$els.content});
	},

	methods: {

		save: function () {
			var data = {event: this.event, id: this.event.id};

			this.$broadcast ('save', data);

			this.resource.save ({id: this.event.id}, data).then (function (res) {

				var data = res.data;

				if (!this.event.id) {
					window.history.replaceState ({}, '', this.$url.route ('admin/eventlist/event/edit', {id: data.event.id}))
				}

				this.$set ('event', data.event);

				this.$notify ('Event saved.');

			}, function (res) {
				this.$notify (res.data, 'danger');
			});
		}

	},

	components: {
		settings: require ('../../components/admin/event-edit.vue')
	}
};

Vue.ready (window.event);