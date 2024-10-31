var gulp = require('gulp'),
postcss = require('gulp-postcss'),
sourcemaps = require('gulp-sourcemaps'),
cssnext = require('postcss-cssnext'),
lost = require('lost');

var paths = {
    cssSource: 'assets/src/css/',
    cssDestination: 'assets/dist/css/'
};

gulp.task('styles', function() {
    var plugins = [
        cssnext({browsers: ['last 1 version']}),
        lost()
    ];
    return gulp.src(paths.cssSource + '**/*.css')
    .pipe(sourcemaps.init())
    .pipe(postcss(plugins))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(paths.cssDestination));
});

gulp.watch(paths.cssSource + '**/*.css', ['styles']);

gulp.task('default', ['styles']);
