/* Global reqquire */
var gulp   = require('gulp');

// Convert WordPress readme file to readme.md.
var readme = require('gulp-readme-to-markdown');
gulp.task('readme', function () {
	gulp.src(['readme.txt'])
		.pipe(readme({
			details       : false,
			screenshot_ext: ['jpg', 'jpg', 'png'],
			extract       : {}
		}))
		.pipe(gulp.dest('.'));
});