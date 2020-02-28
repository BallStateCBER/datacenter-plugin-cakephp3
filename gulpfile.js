var _ = require('lodash');
var gulp = require('gulp');
var jshint = require('gulp-jshint');
var notify = require("gulp-notify");
var phpcs = require('gulp-phpcs');
var phpunit = require('gulp-phpunit');
var stylish = require('jshint-stylish');


/**************
 *    PHP     *
 **************/

gulp.task('php_cs', function (cb) {
    return gulp.src(['src/**/*.php', 'config/*.php', 'tests/*.php', 'tests/**/*.php'])
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

function testNotification(status, pluginName, override) {
    var options = {
        title:   ( status === 'pass' ) ? 'Tests Passed' : 'Tests Failed',
        message: ( status === 'pass' ) ? 'All tests have passed!' : 'One or more tests failed',
        icon:    __dirname + '/node_modules/gulp-' + pluginName +'/assets/test-' + status + '.png'
    };
    options = _.merge(options, override);
    return options;
}

gulp.task('php_unit', function() {
    gulp.src('phpunit.xml')
        .pipe(phpunit('', {notify: true}))
        .on('error', notify.onError(testNotification('fail', 'phpunit')))
        .pipe(notify(testNotification('pass', 'php_unit')));
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
