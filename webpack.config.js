module.exports = [
	{
		entry: {
			"admin/link": "./app/components/admin/link.vue",
			"admin/settings": "./app/views/admin/settings",
			"admin/event-index": "./app/views/admin/event-index",
			"admin/event-edit": "./app/views/admin/event-edit",
			"admin/event-performer": "./app/components/admin/event-performer.vue",
			"admin/category-index": "./app/views/admin/category-index",
			"admin/category-edit": "./app/views/admin/category-edit",
			"event-index": "./app/views/event-index",
			"event-details": "./app/views/event-details"
		},
		output: {
			filename: "./app/bundle/[name].js"
		},
		module: {
			loaders: [
				{test: /\.vue$/, loader: "vue"},
				{test: /\.js$/, exclude: /node_modules/, loader: "babel-loader"}
			]
		}
	}
];