var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
// var minifycss = require('gulp-minify-css');

gulp.task('minifyjs', function() {
    return gulp.src('assert/**/*.js')
        .pipe(concat('app.js'))
        .pipe(gulp.dest('dist'))
        .pipe(uglify())
        .pipe(rename('app.min.js'))
        .pipe(gulp.dest('dist'));
});

// gulp.task('minifycss', function() {
//     return gulp.src('css/*.css')
//         .pipe(minifycss())
//         .pipe(gulp.dest('css/min'));
// });

gulp.task('watch', function() {
    gulp.watch('assert/**/*.js', ['minifyjs']);
    // gulp.watch('css/*.css', ['minifycss']);
});

gulp.task('default', ['minifyjs', 'watch']);