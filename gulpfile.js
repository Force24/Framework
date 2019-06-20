// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var watch = require('gulp-watch');
var batch = require('gulp-batch');
var del = require('del');
var jshint = require('gulp-jshint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var runSequence = require('run-sequence');
var browserSync = require('browser-sync').create();
var spritesmith = require('gulp.spritesmith');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var imagemin = require('gulp-imagemin');
var cleanCSS = require('gulp-clean-css');
var pump = require('pump'); 


// Create reusable paths to files
var paths = (function () {

  var path = {};
  path.root = './';

  path.dev = path.root + 'dev/';
  path.build = path.root + 'build/';

  path.sass = path.dev + 'sass/';
  path.css = path.build + 'css/';

  path.devJS = path.dev + 'js/';
  path.buildJS = path.build + 'js/';
  path.devJSLibs = path.devJS + 'libs/';

  path.singleImages = path.root + 'images/single-images/';
  path.sprite = path.root + 'images/sprite/';
  path.images = path.root + 'images/'

  return path;
})();

var spriteConfig = {
    imgName: 'sprite.png',
    cssName: '_sprite.scss',
    imgPath: '../../images/sprite/sprite.png' // Gets put in the css
};

gulp.task('sprite', function () {
    var spriteData = gulp.src(paths.singleImages + '**/*.png').pipe(spritesmith({
        imgName: spriteConfig.imgName,
        cssName: spriteConfig.cssName,
        imgPath: spriteConfig.imgPath,
        algorithm: 'top-down',
        padding: 30
    }));
    spriteData.img.pipe(gulp.dest(paths.sprite));
    spriteData.css.pipe(gulp.dest(paths.sass + "utilities/"))
    .pipe(browserSync.stream());
});

gulp.task('optimiseImg', function() {
    gulp.src(paths.images + '*/**')
        .pipe(imagemin())
        .pipe(gulp.dest(paths.images))
});

// Clean build folder
gulp.task('clean', function() {
  return del(paths.build);
});

// Clean CSS
gulp.task('cleanCSS', function() {
  return del(paths.css);
});


// Serve file changes through browserSync
gulp.task('browser-sync', function() {
    browserSync.init(null, {
        // Change proxy location to the correct location of your project
        // You may need to change the localhost port number from :8080 to whatever yours is set at
        // and you'll need to add your project folder location to the proxy url - eg: localhost:8080/Force24_Boilerplate
        // this needs to be the root of your project where the gulp file is located
        proxy: 'http://localhost/csv%20formatting', 
        port: 5000
    });
});

// Lint JS for errors
gulp.task('lint', function() {
    return gulp.src( paths.devJS + 'main.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile Our Sass
gulp.task('sass', ['cleanCSS'], function() {
    return gulp.src(paths.sass + '**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest(paths.css))
        .pipe(browserSync.stream());
});

// Concatenate
gulp.task('concatJS', function() {
    return gulp.src(paths.devJS + 'main.js')
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest(paths.buildJS))
    .pipe(browserSync.stream());
});

// Concat JS Libs
gulp.task('concatJSLibs', function() {
    return gulp.src([
        paths.devJSLibs + 'jquery-2.1.4.min.js',
        paths.devJSLibs + 'html5shiv-3.7.3.min.js'
    ])
    .pipe(concat('libs.js'))
    .pipe(gulp.dest(paths.buildJS))
    .pipe(browserSync.stream());
});

gulp.task('minifyCSS', function() {
  return gulp.src(paths.css +'**/*.css')
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest(paths.css));
});

gulp.task('minifyJS', function(cb) {  
    pump([
        gulp.src(paths.buildJS + '**/*.js'),
        uglify(),
        gulp.dest(paths.buildJS)
    ],
    cb
  );
});


gulp.task('watchAll', function() {
  watch(paths.sass + '**/*.scss', batch(function (events, done) {
    gulp.start('sass', done);
  }));

  watch([paths.devJS + '**/*.js', paths.devJSLibs + '*.js'], batch(function (events, done) {
    gulp.start(['lint', 'concatJS', 'concatJSLibs'], done);
  }));

  watch(paths.singleImages + '**/*.png', batch(function (events, done) {
    gulp.start('sprite', done);
  }));
});


// Build task
gulp.task('build', function(callback) {
  runSequence('clean', 'sprite', ['sass', 'concatJS', 'concatJSLibs'], callback);
});

// Default Task
gulp.task('default', function(callback) {
  runSequence('build',  'browser-sync', 'watchAll', callback);
});

// Deployment task
gulp.task('deploy', function(callback) {
  runSequence('build',['minifyCSS', 'minifyJS', 'optimiseImg'], callback);
});
