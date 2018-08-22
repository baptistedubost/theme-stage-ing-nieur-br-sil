const gulp = require('gulp'),
    compass = require('gulp-compass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch'),
    cleanCSS = require('gulp-clean-css'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename'),
    ts = require('gulp-typescript'),
    livereload = require('gulp-livereload'),
    sourcemaps = require('gulp-sourcemaps');
    // babili = require("gulp-babel-minify");
var outputDir = 'dist';

var globs = {
    js: 'js/**/*.ts',
    sass: 'sass/**/*.scss',
    templates: ['**/*.php', '**/*.html']
};

function handleError(err) {
    console.log(err.toString());
    this.emit('end');
  }

gulp.task('compass', function () {
    return gulp.src(['sass/*.scss'])
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(compass({
            sourcemap: true,
            css: outputDir + '/css',
            sass: 'sass'
        }))
        .pipe(gulp.dest(outputDir + '/css'))
        .pipe(livereload());
});
gulp.task('minify-css', function () {
    return gulp.src([outputDir + '/css/*.css', '!' + outputDir + '/css/*.min.css'])
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(sourcemaps.init())
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('/'))
        .pipe(gulp.dest(outputDir + '/css'));
    gulp.src([outputDir + '/css/font.css'])
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(sourcemaps.init())
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('/'))
        .pipe(gulp.dest(outputDir + '/css'));
});

// TYPESCRIPT
gulp.task('typescript', function () {
    return gulp.src('js/**/*.ts')
        .pipe(ts({
            noImplicitAny: false,
            // outFile: 'appTs.js'
            target: 'es2016'
        }))
        .pipe(gulp.dest('js/src/'));
});
gulp.task('concat', function () {
    return gulp.src([
            'js/src/*.js',
            'js/vendors/**/*.js'
        ])
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(sourcemaps.init())
        .pipe(concat('app.js'))
        .pipe(sourcemaps.write('/'))
        .pipe(gulp.dest(outputDir + '/js'))
        .pipe(livereload());
});
gulp.task("minify-js", function() {
    return gulp.src("./dist/js/app.js", {allowEmpty: true})
    // .pipe(babili({
    //     mangle: {
    //         keepClassName: true
    //     }
    // }))
    .pipe(rename({
        suffix: '.min'
    }))
    .pipe(gulp.dest("./dist/js"))
});

// DEPENDENCIES
gulp.task('concatDependencies', function () {
    return gulp
      .src([
        "node_modules/jquery/dist/jquery.js",
        "node_modules/masonry-layout/dist/masonry.pkgd.min.js",
      ])
      .pipe(plumber({ errorHandler: handleError }))
      .pipe(sourcemaps.init())
      .pipe(concat("dependencies.js"))
      .pipe(sourcemaps.write("/"))
      .pipe(gulp.dest(outputDir + "/js"));
});

gulp.task('uglifyDependencies', function () {
    return gulp.src([outputDir + '/js/dependencies.js'])
        .pipe(plumber({ errorHandler: handleError }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest(outputDir + '/js'));
});

// WATCH TASK
gulp.task('watch', function () {
    livereload.listen();
    gulp.watch(globs.js, gulp.series('typescript', 'concat', 'minify-js'));
    gulp.watch(globs.sass, gulp.series('compass', 'minify-css'));
    gulp.watch(globs.templates).on('change', function (file) {
        return livereload.changed(file);
    });
});


gulp.task('default', gulp.series(gulp.parallel(
        gulp.series('concatDependencies', 'uglifyDependencies'), 
        gulp.series('compass', 'minify-css'),
        gulp.series('typescript', 'concat', 'minify-js')), 'watch'));
gulp.task('build', gulp.parallel(
    gulp.series('concatDependencies', 'uglifyDependencies'), 
    gulp.series('compass', 'minify-css'), 
    gulp.series('typescript', 'concat', 'minify-js')));