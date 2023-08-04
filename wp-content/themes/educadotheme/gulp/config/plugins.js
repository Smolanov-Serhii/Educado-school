import replace from "gulp-replace";
import browserSync from 'browser-sync';
import uglify from 'gulp-uglify-es';
import concat from 'gulp-concat';
import map from 'gulp-sourcemaps';
import webpack from 'webpack-stream';
import webpackStream from 'webpack-stream';


export const plugins = {
	browserSync: browserSync,
	replace: replace,
	uglify: uglify,
	concat: concat,
	map: map,
	webpack: webpack,
	webpackStream: webpackStream
}