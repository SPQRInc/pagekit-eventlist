window.categories = {

	el: '#categories',

	data: function () {
		return _.merge ({
			categories: false,
			config: {
				filter: this.$session.get ('categories.filter', {order: 'date desc', limit: 25})
			},
			pages: 0,
			count: '',
			selected: []
		}, window.$data);
	},
	ready: function () {
		this.resource = this.$resource ('api/eventlist/category{/id}');

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

				this.$session.set ('categories.filter', filter);
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
		active: function (category) {
			return this.selected.indexOf (category.id) != -1;
		},
		save: function (category) {
			this.resource.save ({id: category.id}, {category: category}).then (function () {
				this.load ();
				this.$notify ('Category saved.');
			});
		},
		status: function (status) {

			var categories = this.getSelected ();

			categories.forEach (function (category) {
				category.status = status;
			});

			this.resource.save ({id: 'bulk'}, {categories: categories}).then (function () {
				this.load ();
				this.$notify ('Categories saved.');
			});
		},
		toggleStatus: function (category) {
			category.status = category.status === 2 ? 3 : 2;
			this.save (category);
		},
		remove: function () {

			this.resource.delete ({id: 'bulk'}, {ids: this.selected}).then (function () {
				this.load ();
				this.$notify ('Categories deleted.');
			});
		},
		copy: function () {

			if (!this.selected.length) {
				return;
			}

			this.resource.save ({id: 'copy'}, {ids: this.selected}).then (function () {
				this.load ();
				this.$notify ('Categories copied.');
			});
		},
		load: function () {
			this.resource.query ({filter: this.config.filter, page: this.config.page}).then (function (res) {

				var data = res.data;

				this.$set ('categories', data.categories);
				this.$set ('pages', data.pages);
				this.$set ('count', data.count);
				this.$set ('selected', []);
			});
		},
		getSelected: function () {
			return this.categories.filter (function (category) {
				return this.selected.indexOf (category.id) !== -1;
			}, this);
		},
		removeCategories: function () {
			this.resource.delete ({id: 'bulk'}, {ids: this.selected}).then (function () {
				this.load ();
				this.$notify ('Categories(s) deleted.');
			});
		},
		getStatusText: function (category) {
			return this.statuses[category.status];
		}
	},
	components: {}
};
Vue.ready (window.categories);
