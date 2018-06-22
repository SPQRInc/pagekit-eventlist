module.exports = {

	el: '#eventlist-index',

	data: function () {
		return _.merge ({
			events: false,
		}, window.$data);
	},

	ready: function () {
	},

	watch: {
	},

	created () {
	},

	computed: {},

	methods: {
	},
	filters: {}
};

Vue.ready (module.exports);