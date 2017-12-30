const webpack = require('webpack')

module.exports = {
  entry: {
    'ystandard': './src/js/ystandard.js'
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
  devtool: 'source-map',
  plugins: [
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true
    })
  ]
}
