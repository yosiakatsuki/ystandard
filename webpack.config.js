module.exports = {
    mode: "production",

    entry: {
        'font-awesome-ystd': './src/js/font-awesome/app.js',
    },
    output: {
        filename: '[name].js',
        path: `${ __dirname }/js`
    },
};
