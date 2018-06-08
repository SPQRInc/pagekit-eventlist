window.events = {

	el: '#events',

	data: function () {
		return _.merge ({
			events: false,
			config: {
				filter: this.$session.get ('events.filter', {order: 'date desc', limit: 25})
			},
			pages: 0,
			count: '',
			selected: []
		}, window.$data);
	},
	ready: function () {
		this.resource = this.$resource ('api/eventlist/event{/id}');

		this.$watch ('config.page', this.load, {immediate: true});
	},
	watch: {
		'config.filter': {
			handler: function (filter) {
				if (this.config.page) {
					this.config.page = 0;
				} else {
					this.load ();
				}

				this.$session.set ('events.filter', filter);
			},
			deep: true
		}
	},
	computed: {
		statusOptions: function () {
			var options = _.map (this.$data.statuses, function (status, id) {
				return {text: status, value: id};
			});

			return [{label: this.$trans ('Filter by'), options: options}];
		},
		users: function () {

			var options = _.map (this.$data.users, function (user) {
				return {text: user.username, value: user.user_id};
			});

			return [{label: this.$trans ('Filter by'), options: options}];
		}

	},
	methods: {
		active: function (event) {
			return this.selected.indexOf (event.id) != -1;
		},
		save: function (event) {
			this.resource.save ({id: event.id}, {event: event}).then (function () {
				this.load ();
				this.$notify ('Event saved.');
			});
		},
		status: function (status) {

			var events = this.getSelected ();

			events.forEach (function (event) {
				event.status = status;
			});

			this.resource.save ({id: 'bulk'}, {events: events}).then (function () {
				this.load ();
				this.$notify ('Events saved.');
			});
		},
		toggleStatus: function (event) {
			event.status = event.status === 2 ? 3 : 2;
			this.save (event);
		},
		remove: function () {

			this.resource.delete ({id: 'bulk'}, {ids: this.selected}).then (function () {
				this.load ();
				this.$notify ('Events deleted.');
			});
		},
		copy: function () {

			if (!this.selected.length) {
				return;
			}

			this.resource.save ({id: 'copy'}, {ids: this.selected}).then (function () {
				this.load ();
				this.$notify ('Events copied.');
			});
		},
		load: function () {
			this.resource.query ({filter: this.config.filter, page: this.config.page}).then (function (res) {

				var data = res.data;

				this.$set ('events', data.events);
				this.$set ('pages', data.pages);
				this.$set ('count', data.count);
				this.$set ('selected', []);
			});
		},
		getSelected: function () {
			return this.events.filter (function (event) {
				return this.selected.indexOf (event.id) !== -1;
			}, this);
		},
		removeEvents: function () {
			this.resource.delete ({id: 'bulk'}, {ids: this.selected}).then (function () {
				this.load ();
				this.$notify ('Events(s) deleted.');
			});
		},
		getStatusText: function (event) {
			return this.statuses[event.status];
		}
	},
	components: {}
};
Vue.ready (window.events);
