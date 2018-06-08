module.exports = [
	{
		entry: {
			"admin/settings": "./app/views/admin/settings.js",
			"admin/event-index": "./app/views/admin/event-index",
			"admin/event-edit": "./app/views/admin/event-edit",
			"admin/category-index": "./app/views/admin/category-index",
			"admin/category-edit": "./app/views/admin/category-edit",
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