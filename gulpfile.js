var gulp = require('gulp'),
	compass   = require('gulp-compass'),
	cssnano   = require('gulp-cssnano'),
	concatCss = require('gulp-concat-css'),
	concat    = require('gulp-concat'),
	uglify    = require('gulp-uglify'),
	jshint    = require('gulp-jshint'),
	rename    = require('gulp-rename');

var paths = {
	compass:   ['wp-content/themes/informal/sass/*.sass'],
	concatcss: [
		// 'public/libraries/bootstrap/dist/css/bootstrap.min.css',
		// 'public/libraries/formvalidation/dist/css/formValidation.min.css',
		// 'public/libraries/Slidebars/dist/slidebars.min.css',
		'wp-content/themes/informal/css/style.css'
	],
	js: [
		'public/libraries/bootstrap/dist/js/bootstrap.js',
		'public/libraries/formvalidation/dist/js/formValidation.js',
		'public/libraries/formvalidation/dist/js/framework/bootstrap.js',
		'public/libraries/formvalidation/dist/js/language/es_ES.js',
		'public/libraries/Slidebars/dist/slidebars.js',
		'wp-content/themes/informal/js/script.js'
	],
	jshint: ['wp-content/themes/informal/js/script.js']
}

gulp.task('compass', function(){
	gulp.src(paths.compass)
		.pipe(compass({
			css: 'wp-content/themes/informal/css',
			sass: 'wp-content/themes/informal/sass',
			image: 'wp-content/themes/informal/images'
		}))
		.pipe(cssnano())
		.pipe(gulp.dest('wp-content/themes/informal/css'));
});

gulp.task('js', function(){
	gulp.src(paths.js)
		.pipe(concat('main.js'))
		.pipe(rename({suffix: '.min'}))
		// .pipe(uglify())
		.pipe(gulp.dest('wp-content/themes/informal/js/'));
});

gulp.task('concatcss', function(){
	gulp.src(paths.concatcss)
		.pipe(concatCss('master.css'))
		.pipe(cssnano())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest('wp-content/themes/informal/css'));
});

gulp.task('jshint', function(){
	gulp.src(paths.jshint)
		.pipe(jshint())
		.pipe(jshint.reporter('default'));
});

gulp.task('watch', function(){
	gulp.watch(paths.jshint, ['jshint']);
	gulp.watch(paths.compass, ['compass']);
	gulp.watch(paths.js, ['js']);
	// gulp.watch(paths.concatcss, ['concatcss']);
});

gulp.task('default', ['concatcss', 'jshint', 'js']);
gulp.task('dev', ['watch']);
// gulp.task('default', ['compass', 'js', 'concatcss']);