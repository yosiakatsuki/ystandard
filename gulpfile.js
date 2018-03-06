var gulp           = require('gulp');
var plumber        = require('gulp-plumber');
var watch          = require('gulp-watch');
var rename         = require('gulp-rename');
var sass           = require('gulp-sass');
var postcss        = require('gulp-postcss');
var autoprefixer   = require('autoprefixer');
var cssnano        = require('cssnano');
var uglify         = require('gulp-uglify');
var browserSync    = require('browser-sync');
var webpackStream  = require('webpack-stream');
var webpack        = require('webpack');
var zip            = require('gulp-zip');
var cmq            = require('gulp-combine-media-queries');
var del            = require('del');
var env            = process.env.NODE_ENV;

var dir = {
  src: {
    sass: './src/sass/**/*.scss',
    js: './src/js/**/*.js'
  },
  dist: {
    css: './css',
    js: './js'
  }
}

/**
 * webpack config
 */
var webpackConfig = require('./webpack.config');

/**
 * sass
 */
gulp.task('sass', function() {
  gulp.src(dir.src.sass)
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    }))
    .pipe(sass({
       includePaths: require('node-normalize-scss').includePaths
    }))
    .pipe(postcss([
      autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
      })
    ]))
    .pipe(cmq({log: false}))
    .pipe(gulp.dest(dir.dist.css))
    .pipe(postcss([
      cssnano({
        'zindex': false
      })
    ]))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(dir.dist.css));
});

/**
 * webpack
 */
gulp.task('webpack', function(){
  del(dir.dist.js);
  return plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    })
    .pipe(webpackStream(webpackConfig, webpack))
    .pipe(gulp.dest(dir.dist.js));
});

/**
 * browser-sync init
 */
gulp.task('bs-init', function() {
  browserSync.init({
    proxy: "wp-ystandard.local",
    open : "external",
    port : "3000"
  });
});

/**
 * browser-sync reload
 */
gulp.task('bs-reload', function() {
  browserSync.reload()
});

/**
 * create zip file
 */
gulp.task('zip', function(){
  return gulp.src(
      [
        '**',
        '!node_modules',
        '!node_modules/**',
        '!gulpfile.js',
        '!package.json',
        '!webpack.config.js',
      ],
      {base: './'}
    )
    .pipe(zip('ystandard.zip'))
    .pipe(gulp.dest('./'));
});

/**
 *
 * watch
 *
 */
/**
 * コード
 */
gulp.task('watch',['sass','webpack'],function() {
  watch(dir.src.sass, function(event) {
    gulp.start('sass');
  });
  watch(dir.src.js, function(event) {
    gulp.start('webpack');
  });
});

/**
 * browserSync
 */
gulp.task('browsersync',['bs-init','watch'],function() {
  watch(['./css/*.min.css','./js/*.min.js','./**/*.php'], function(event) {
    gulp.start('bs-reload');
  });
});
