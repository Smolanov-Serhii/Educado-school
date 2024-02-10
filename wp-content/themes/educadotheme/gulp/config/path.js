
import * as nodePath from 'path'
const rootFolder = nodePath.basename(nodePath.resolve())


const buildFolder = `./assets`
const srcFolder = `./src`


export const path = {
	build:{
		html: `${buildFolder}/`,
		css: `${buildFolder}/css/`,
		js: `${buildFolder}/js/`,
		img: `${buildFolder}/img`,
		fonts: `${buildFolder}/fonts/`,
		files: `${buildFolder}/js/libraries/`
	},
	src:{
		html: `${srcFolder}/*.html`,
		htmlComponents: `${srcFolder}/**/*.html`,
		scss: `${srcFolder}/scss/style.scss`,
		js: `${srcFolder}/js/*.js`,
		img: `${srcFolder}/img/**/*.*`,
		fonts: `${srcFolder}/fonts/*.*`,
		files: `${srcFolder}/js/libraries/*.*`
	},
	watch:{
		html: `${srcFolder}/*.html`,
		htmlComponents: `${srcFolder}/**/*.html`,
		scss: `${srcFolder}/scss/**/*.scss`,
		js: `${srcFolder}/js/**/*.js`,
		img: `${srcFolder}/img/**/*.*`,
		fonts: `${srcFolder}/fonts/*.*`,
		files: `${srcFolder}/js/libraries/*.*`
	},
	clean: buildFolder,
	buildFolder: buildFolder,
	srcFolder: srcFolder,
	rootFolder: rootFolder,
}
