var gulp					 = require('gulp');
var plumber				 = require('gulp-plumber');
var watch					 = require('gulp-watch');
var rename				 = require('gulp-rename');
var sass					 = require('gulp-sass');
var cleanCSS 			 = require('gulp-clean-css');
var autoprefixer	 = require('gulp-autoprefixer');
var uglify				 = require('gulp-uglify');
var browserSync		 = require('browser-sync');
var babel          = require('gulp-babel');
var webpackStream   = require('webpack-stream');
var webpack         = require('webpack');
var cmq						 = require('gulp-combine-media-queries');

/**
 * browserSyncで監視するファイル
 */
var bsWatchFiles = [
  './css/*.min.css',
  './js/*.min.js',
  './**/*.php'
];

/**
 * browserSyncのオプション
 */
var bsOptions = {
  proxy: "wp-ystandard.dev",
  open : "external",
  port : "3000"
};

/**
 * sass
 */
var srcSass = [
  './src/sass/**/*.scss'
]

/**
 * css
 */
var srcCss = [
  './css/*.css',
  '!./css/*.min.css',
  '!./css/ys-editor-style.css'
]

/**
 * js
 */
var srcJs = [
  './src/js/**/*.js'
]

/**
 * es2015
 */
var srcEs = [
  './src/js/**/*.js',
  '!./src/js/modules/**/*.js'
]

/**
 * webpack config 読み込み
 */
var webpackConfig = require('./webpack.config');


/**
 * sass
 */
gulp.task('sass', function() {
  gulp.src(srcSass)
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    }))
    .pipe(sass())
    .pipe(autoprefixer({
      browsers: ["last 2 versions", "ie >= 11", "Android >= 4.4","ios_saf >= 9"],
      cascade: false
    }))
    .pipe(cmq({log: true}))
    .pipe(gulp.dest('./css'));
});


/**
 * css圧縮
 */
gulp.task('mincss', function() {
  gulp.src(srcCss)
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(cleanCSS())
    .pipe(gulp.dest('./css'));
});



/**
 * js圧縮
 */
gulp.task('minjs', function() {
  gulp.src(srcJs)
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
    }
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({preserveComments: 'some'}))
    .pipe(gulp.dest('./js'));
});

/**
 * es2015のコンパイル
 */
gulp.task('babel', function () {
  gulp.src(srcEs)
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    }))
    .pipe(babel())
    .pipe(gulp.dest('./js'));
});



/**
 * webpack
 */
gulp.task('webpack', function(){
  return plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    })
    .pipe(webpackStream(webpackConfig, webpack))
    .pipe(gulp.dest('./js'));
});


/**
 * browser-sync init
 */
gulp.task('bs-init', function() {
  browserSync.init(bsOptions);
});

/**
 * browser-sync reload
 */
gulp.task('bs-reload', function() {
  browserSync.reload()
});



/**
 * メディアクエリの整理
 * ※エラーが発生する場合、node_modules/gulp-combine-media-queries/index.js の 152行目をコメントアウト
 */
gulp.task('cmq', function() {
  gulp.src(srcCss)
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err.messageFormatted);
        this.emit('end');
      }
    }))
    .pipe(cmq({log: true}))
    .pipe(gulp.dest('./css/cmq/'));
});





/** --------------------------------------
 * watch
 * --------------------------------------- */
/**
 * コード
 */
gulp.task('watch',['sass','webpack'],function() {
  watch(srcSass, function(event) {
    gulp.start('sass');
  });
  // watch(srcJs, function(event) {
  //   gulp.start('minjs');
  // });
  watch(srcCss, function(event) {
    gulp.start('mincss');
  });
  watch(srcEs, function(event) {
    gulp.start('webpack');
  });
});


/**
 * browserSync
 */
gulp.task('watch-bs',['bs-init','watch'],function() {
  watch(bsWatchFiles, function(event) {
    gulp.start('bs-reload');
  });
});

/**
 * sassのみ
 */
gulp.task('watch-sass',['sass'],function() {
  watch(srcSass, function(event) {
    gulp.start('sass');
  });
});
