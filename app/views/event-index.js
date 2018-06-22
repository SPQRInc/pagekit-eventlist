module.exports = {

	el: '#eventlist-index',

	data: function () {
		return _.merge ({
			events: false,
			config: {
				filter: this.$session.get ('events.filter', {order: 'date desc'})
			},
		}, window.$data);
	},

	ready: function () {
		this.resource = this.$resource ('api/eventlist/event/search{/id}');
		this.$watch ('config.page', this.load, {immediate: true});
	},

	watch: {
		'config.filter': {
			handler: function (filter) {
				this.load ();
				this.$session.set ('events.filter', filter);
			},
			deep: true
		}
	},

	created () {
		this.config.filter = _.extend ({status: '', form: '', order: 'date desc'}, this.config.filter);
	},

	computed: {},

	methods: {

		load: function () {
			this.resource.query ({filter: this.config.filter}).then (function (res) {
				let data = res.data;
				this.$set ('events', data.events);
			});
		},

	},
	filters: {}
};

Vue.ready (module.exports);