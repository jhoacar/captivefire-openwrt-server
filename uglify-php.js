const fs = require("fs");
const glob = require("glob");
const UglifyPHP = require('uglify-php');
const { minify } = require('html-minifier');

const captivefireFolder = './captivefire/';
const captivefireBuild = './build/';

const getDirectories = function (src, callback) {
    glob(src + '/**/*', callback);
};

getDirectories(captivefireFolder, function (err, res) {

    if (err)
        return console.log('Error', err);


    res.map(path => {

        const buildPath = captivefireBuild + path.substring(2);

        if (fs.lstatSync(path).isDirectory() && !fs.existsSync(buildPath)) {
            fs.mkdirSync(buildPath, { recursive: true });
        }

        if (path.includes('captivefire/composer.json')) {
            const rawData = fs.readFileSync(path);
            const compressedData = JSON.stringify(JSON.parse(rawData));
            fs.writeFileSync(buildPath, compressedData);
        }

        if (path.includes('html')) {

            const rawData = fs.readFileSync(path).toString();
            const config = {
                collapseWhitespace: true,
                removeComments: true,
                removeOptionalTags: true,
                removeRedundantAttributes: true,
                removeScriptTypeAttributes: true,
                removeTagWhitespace: true,
                useShortDoctype: true,
                minifyCss: true,
                minifyJs: true,
                preserveLineBreaks: false,
                collapseInlineTagWhitespace: true,
                html5: true
            }
            const compressedData = minify(rawData, config);
            fs.writeFileSync(buildPath, compressedData);
        }

        if (!fs.lstatSync(path).isFile() || !path.includes('.php')) {
            return;
        }

        const options = {
            "excludes": [
                '$GLOBALS',
                '$_SERVER',
                '$_GET',
                '$_POST',
                '$_FILES',
                '$_REQUEST',
                '$_SESSION',
                '$_ENV',
                '$_COOKIE',
                '$php_errormsg',
                '$HTTP_RAW_POST_DATA',
                '$http_response_header',
                '$argc',
                '$argv',
                '$this'
            ],
            "minify": {
                "replace_variables": false,
                "remove_whitespace": true,
                "remove_comments": true,
                "minify_html": true
            }
        }
        UglifyPHP.minify(path, options)
            .then(result => {
                fs.writeFile(buildPath, result, (err) => {
                    if (err) console.log(buildPath, err);
                });
            })
            .catch(error => console.log(error));

    });
});
