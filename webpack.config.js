const webpack = require('webpack')
const env = process.env.NODE_ENV;

let config = {
  entry: {
    'ystandard': './src/js/ystandard.js',
    'polyfill': './src/js/polyfill.js',
    'admin': './src/js/admin/custom_uploader.js'
  },
  output: {
    filename: '[name].bundle.js',
    path: `${__dirname}/js`
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        options: {
          presets: [
            ['env', {'modules': false}]
          ]
        }
      }
    ]
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  plugins: [
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true
    })
  ]
}
if (env === 'dev') {
  config.devtool = 'source-map'
} else {
  config.plugins.push(new webpack.optimize.UglifyJsPlugin({
    sourceMap: true
  }))
}

module.exports = config;
