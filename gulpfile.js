const { series, parallel, watch, src, dest } = require( 'gulp' );
const gulpZip = require( 'gulp-zip' );
const gulpSass = require( 'gulp-sass' );
const postcss = require( 'gulp-postcss' );
const autoprefixer = require( 'autoprefixer' );
const mqpacker = require( 'css-mqpacker' );
const cssnano = require( 'cssnano' );
const packageImporter = require( 'node-sass-package-importer' );
const babel = require( 'gulp-babel' );
const del = require( 'del' );
const webpackStream = require( 'webpack-stream' );
const webpack = require( 'webpack' );

const webpackConfig = require( './webpack.config' );


/**
 * PostCssで使うプラグイン
 */
const postcssPlugins = [
    autoprefixer( {
        grid: 'autoplace'
    } ),
    mqpacker(),
    cssnano()
];
const postcssPluginsParts = [
    autoprefixer( {
        overrideBrowserslist: [ 'last 2 version, not ie < 11' ],
        grid: 'autoplace'
    } ),
    mqpacker()
];
/**
 * babel option
 */
const babelOption = {
    presets: [ '@babel/preset-env', 'minify' ],
    comments: false
};

/**
 * sass
 */
function sass() {
    return src( './src/sass/*.scss' )
        .pipe( gulpSass( {
            importer: packageImporter( {
                extensions: [ '.scss', '.css' ]
            } )
        } ) )
        .pipe( postcss( postcssPlugins ) )
        .pipe( dest( './css' ) )
}

/**
 * sass - parts
 */
function sassParts() {
    return src( './src/sass/inline-parts/*.scss' )
        .pipe( gulpSass() )
        .pipe( postcss( postcssPluginsParts ) )
        .pipe( dest( './src/css/inline-parts' ) )
}

/**
 * JS
 */
function js() {
    return src( 'src/js/*.js' )
        .pipe( babel( babelOption ) )
        .pipe( dest( 'js/' ) )
}

function jsAdmin() {
    return src( 'src/js/admin/*.js' )
        .pipe( babel( babelOption ) )
        .pipe( dest( 'js/admin/' ) )
}

function buildWebpack() {
    return webpackStream( webpackConfig, webpack )
        .pipe( dest( 'js/' ) )
}

/**
 * 必要ファイルのコピー
 */
function copyProductionFiles() {
    return src(
        [
            '**',
            '!.editorconfig',
            '!.eslintrc.json',
            '!.gitignore',
            '!.travis.yml',
            '!node_modules',
            '!node_modules/**',
            '!gulpfile.js',
            '!package.json',
            '!package-lock.json',
            '!phpcs.ruleset.dist',
            '!phpcs.ruleset.xml',
            '!phpunit.xml.dist',
            '!webpack.config.js',
            '!ystandard-info.json',
            '!ystandard-info-beta.json',
            '!tests',
            '!tests/**',
            '!bin',
            '!bin/**',
            '!src',
            '!src/**',
            '!block/**/*.js',
            '!block/**/*.scss',
            '!.github',
            '!.github/**',
            '!build',
            '!build/**',
            '!*.zip',
            '!ystandard',
            '!ystandard/**',
        ],
        { base: '.' }
    )
        .pipe( dest( './ystandard' ) );
}

/**
 * create zip file
 */
function zip() {
    return src( 'ystandard/**', { base: '.' } )
        .pipe( gulpZip( 'ystandard.zip' ) )
        .pipe( dest( 'build' ) );
}

function copyJson() {
    return src( [ 'ystandard-info.json', 'ystandard-info-beta.json' ] )
        .pipe( dest( 'build' ) );
}

function cleanFiles( cb ) {
    return del(
        [
            './ystandard',
            './build'
        ],
        cb );
}

/**
 * サーバーにデプロイするファイルを作成
 */
exports.createDeployFiles = series( cleanFiles, copyProductionFiles, parallel( zip, copyJson ) );
/**
 * タスクの登録
 */
exports.zip = series( copyProductionFiles, zip );
exports.clean = series( cleanFiles );
exports.js = parallel( js, jsAdmin );
exports.sass = parallel( sass, sassParts );
exports.webpack = series( buildWebpack );
exports.build = series( cleanFiles, parallel( sass, sassParts, js, jsAdmin, buildWebpack ) );

/**
 * default
 */
exports.default = function () {
    cleanFiles();
    sass();
    watch( [ './src/sass/**/*.scss', '!./src/sass/inline-parts/**/*.scss' ], sass );
    watch( './src/sass/inline-parts/**/*.scss', sassParts );
    watch( './src/js/*.js', js );
    watch( './src/js/admin/*.js', jsAdmin );
    watch( './src/js/**/*.js', buildWebpack );
};
