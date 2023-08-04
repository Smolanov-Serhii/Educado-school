import WebpackNotifierPlugin  from 'webpack-notifier'

export const script = () => {
	return app.gulp.src(app.path.src.js)
		.pipe(app.plugins.webpackStream({
			mode: 'development',
			output: {
				filename: 'main.min.js'
			},
			plugins: [
				new WebpackNotifierPlugin(),
			],
			module: {
		        rules: [{
		            test: /\.js$/,
		            exclude: /node_modules/,
		            use: ['babel-loader']
		        }]
		    },
			resolve: {
				fallback: { "path": false }
			}
		}), app.plugins.webpack())
		.pipe(app.gulp.dest(app.path.build.js))
		.pipe(app.plugins.browserSync.stream())
}

export const scriptBuild = () => {
	return app.gulp.src(app.path.src.js)
		.pipe(app.plugins.webpackStream({
			mode: 'production',
			performance: {
				hints: false
			},
			output: {
				filename: 'main.min.js'
			},
			module: {
		        rules: [{
		            test: /\.js$/,
		            exclude: /node_modules/,
		            use: ['babel-loader']
		        }]
		    }
		}), app.plugins.webpack())
		.pipe(app.gulp.dest(app.path.build.js))
		.pipe(app.plugins.browserSync.stream())
}
