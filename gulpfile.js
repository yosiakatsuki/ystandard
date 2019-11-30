const gulp = require('gulp');
const zip = require('gulp-zip');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const mqpacker = require('css-mqpacker');
const cssnano = require('cssnano');
const packageImporter = require('node-sass-package-importer');
const babel = require('gulp-babel');


/**
 * PostCssで使うプラグイン
 */
const postcssPlugins = [
    autoprefixer({
        overrideBrowserslist: ['last 2 version, not ie < 11'],
        grid: 'autoplace'
    }),
    mqpacker(),
    cssnano()
];
const postcssPluginsParts = [
    autoprefixer({
        overrideBrowserslist: ['last 2 version, not ie < 11'],
        grid: 'autoplace'
    }),
    mqpacker()
];
/**
 * babel option
 */
const babelOption = {
    presets: ['@babel/preset-env', 'minify'],
    comments: false
};

/**
 * sass
 */
gulp.task('sass', () => {
    return gulp.src('./src/sass/*.scss')
        .pipe(sass({
            importer: packageImporter({
                extensions: ['.scss', '.css']
            })
        }))
        .pipe(postcss(postcssPlugins))
        .pipe(gulp.dest('./css'))
});
/**
 * sass - parts
 */
gulp.task('sass:parts', () => {
    return gulp.src('./src/sass/inline-parts/*.scss')
        .pipe(sass())
        .pipe(postcss(postcssPluginsParts))
        .pipe(gulp.dest('./src/css/inline-parts'))
});

/**
 * JS
 */
gulp.task('js', () => {
    return gulp.src('src/js/*.js')
        .pipe(babel(babelOption))
        .pipe(gulp.dest('js/'))
});
gulp.task('js:admin', () => {
    return gulp.src('src/js/admin/*.js')
        .pipe(babel(babelOption))
        .pipe(gulp.dest('js/admin/'))
});

/**
 * create zip file
 */
gulp.task('zip', function () {
    return gulp.src(
        [
            '**',
            '!node_modules',
            '!node_modules/**',
            '!gulpfile.js',
            '!package.json',
            '!package-lock.json',
            '!webpack.config.js',
            '!ystandard-info.json',
            '!ystandard-info-beta.json',
            '!phpcs.ruleset.dist',
            '!phpcs.ruleset.xml',
            '!phpunit.xml.dist',
            '!tests',
            '!tests/**',
            '!bin',
            '!bin/**',
            '!src',
            '!src/**',
            '!docs',
            '!docs/**',
            '!temp',
            '!temp/**',
            '!*.zip',
        ],
        {base: './'}
    )
        .pipe(zip('ystandard.zip'))
        .pipe(gulp.dest('./'));
});


/**
 * watch
 */
gulp.task('watch', () => {
    gulp.watch(
        ['./src/sass/**/*.scss', '!./src/sass/inline-parts/**/*.scss'],
        gulp.task('sass')
    );
    gulp.watch('./src/sass/inline-parts/**/*.scss', gulp.task('sass:parts'));
    gulp.watch('./src/js/*.js', gulp.task('js'));
    gulp.watch('./src/js/admin/*.js', gulp.task('js:admin'));
});

/**
 * default
 */
gulp.task('default', gulp.task('watch'));
