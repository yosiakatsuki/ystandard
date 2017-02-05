var gulp					 = require('gulp');
var plumber				 = require('gulp-plumber');
var watch					 = require('gulp-watch');
var rename				 = require('gulp-rename');
var sass					 = require('gulp-sass');
var cleanCSS 			 = require('gulp-clean-css');
var autoprefixer	 = require('gulp-autoprefixer');
var uglify				 = require('gulp-uglify');
var browserSync		 = require('browser-sync');
//難あり
var cmq						 = require('gulp-combine-media-queries');

//browserSyncで監視するファイル
var bS_WatchFiles = [
	'./css/*.min.css',
	'./js/*.min.js',
	'./**/*.php'
];

//browserSyncのオプション
var bS_Options = {
	proxy: "ystandard.dev",
	open : "external",
	port : "3000"
};

//sassコンパイルの対象
var src_sass = [
	'./sass/**/*.scss'
]

//css圧縮処理等の対象
var src_css = [
	'./css/*.css',
	'!./css/*.min.css',
	'!./css/ys-editor-style.css'
]

//js圧縮処理等の対象
var src_js = [
	'./js/*.js',
	'!./js/*.min.js'
]

//------------------------------------------------------------------------
//sassコンパイル
//------------------------------------------------------------------------
gulp.task('sass', function() {
	gulp.src(src_sass)
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err.messageFormatted);
				this.emit('end');
			}
		}))
		.pipe(sass())
		.pipe(autoprefixer({
			browsers: ["last 2 versions", "ie >= 11", "Android >= 4","ios_saf >= 8"],
			cascade: false
		}))
		.pipe(gulp.dest('./css'));
});


//------------------------------------------------------------------------
//css圧縮
//------------------------------------------------------------------------
gulp.task('mincss', function() {
	gulp.src(src_css)
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


//------------------------------------------------------------------------
//js圧縮
//------------------------------------------------------------------------
gulp.task('minjs', function() {
	gulp.src(src_js)
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


//------------------------------------------------------------------------
//browser-sync init
//------------------------------------------------------------------------
gulp.task('bs-init', function() {
	browserSync.init(bS_Options);
});

//------------------------------------------------------------------------
//browser-sync reload
//------------------------------------------------------------------------
gulp.task('bs-reload', function() {
	browserSync.reload()
});


//------------------------------------------------------------------------
//メディアクエリをゴニョゴニョ
//------------------------------------------------------------------------
gulp.task('cmq', function() {
	gulp.src(src_css)
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err.messageFormatted);
				this.emit('end');
			}
		}))
		.pipe(cmq({log: true}))
		.pipe(gulp.dest('./css/cmq/'));
});




///////////////////////////////////////////////////////////////////////////////////////////////
//watch
///////////////////////////////////////////////////////////////////////////////////////////////
//コード関連
gulp.task('watch',['sass','minjs'],function() {
	watch(src_sass, function(event) {
		gulp.start('sass');
	});
	watch(src_js, function(event) {
		gulp.start('minjs');
	});
	watch(src_css, function(event) {
		gulp.start('mincss');
	});
});

//browserSync
gulp.task('watch-bs',['bs-init','watch'],function() {
	watch(bS_WatchFiles, function(event) {
		gulp.start('bs-reload');
	});
});

//sassのみ
gulp.task('watch-sass',['sass'],function() {
	watch(src_sass, function(event) {
		gulp.start('sass');
	});
});
