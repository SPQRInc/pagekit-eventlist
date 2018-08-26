window.settings = {

	el: '#settings',

	data: {
		config: $data.config,
		newSocialmediaicon: {
			'title': '',
			'href_class': '',
			'icon': ''
		}
	},

	methods: {

		save: function () {
			this.$http.post ('admin/eventlist/save', {config: this.config}, function () {
				this.$notify ('Settings saved.');
			}).error (function (data) {
				this.$notify (data, 'danger');
			});
		},
		add: function add (e) {
			e.preventDefault ();
			if (!this.newSocialmediaicon || !this.newSocialmediaicon.title || !this.newSocialmediaicon.icon) return;

			this.config.socialmedia_icons.push ({
				title: this.newSocialmediaicon.title,
				href_class: this.Socialmediaicon.href_class,
				icon: this.newSocialmediaicon.icon
			});

			this.newSocialmediaicon = {
				title: '',
				href_class: '',
				icon: ''
			};

		},
		remove: function (socialmedia_icon) {
			this.config.socialmedia_icons.$remove (socialmedia_icon);
		}
	},
	components: {}
};

Vue.ready (window.settings);
