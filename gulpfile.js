var gulp = require('gulp');
var jshint = require('gulp-jshint');
var stylish = require('jshint-stylish');
var phpcs = require('gulp-phpcs');

gulp.task('default', ['js_lint', 'php_cs']);



/**************
 *    PHP     *
 **************/

gulp.task('php_cs', function (cb) {
    return gulp.src(['src/**/*.php', 'config/*.php'])
        // Validate files using PHP Code Sniffer
        .pipe(phpcs({
            bin: '.\\vendor\\bin\\phpcs.bat',
            standard: '.\\vendor\\cakephp\\cakephp-codesniffer\\CakePHP',
            errorSeverity: 1,
            warningSeverity: 1
        }))
        // Log all problems that was found
        .pipe(phpcs.reporter('log'));
});



/**************
 * Javascript *
 **************/
var srcJsFiles = [
    'webroot/js/admin.js',
    'webroot/js/datacenter.js',
    'webroot/js/flash.js',
    'webroot/js/tag_manager.js'
];

gulp.task('js_lint', function () {
    return gulp.src(srcJsFiles)
        .pipe(jshint())
        .pipe(jshint.reporter(stylish));
});
