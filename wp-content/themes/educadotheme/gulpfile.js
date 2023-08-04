import gulp from 'gulp'
import { path } from './gulp/config/path.js'
import { plugins } from './gulp/config/plugins.js'


global.app = {
	path: path,
	gulp: gulp,
	plugins: plugins
}


import { reset } from './gulp/task/reset.js'
import { copy } from './gulp/task/copy.js'
import { html } from './gulp/task/html.js'
import { image } from './gulp/task/image.js'
import { fonts } from './gulp/task/fonts.js'
import { server } from './gulp/task/server.js'
import { scss } from './gulp/task/scss.js'
import { scssBuild } from './gulp/task/scss.js'
import { script } from './gulp/task/script.js'
import { scriptBuild } from './gulp/task/script.js'


function watcher() {
	gulp.watch(path.watch.html, html)
	gulp.watch(path.watch.htmlComponents, html)
	gulp.watch(path.watch.scss, scss)
	gulp.watch(path.watch.js, script)
	gulp.watch(path.watch.img, image)
}


const mainTasks = gulp.parallel(copy, html, scss, script, image, fonts)
const mainBuildTasks = gulp.parallel(copy, html, scssBuild, scriptBuild, image, fonts)


const dev = gulp.series(reset, mainTasks, gulp.parallel(watcher, server))
const build = gulp.series(reset, mainBuildTasks)


gulp.task('default', dev)
gulp.task('build', build)