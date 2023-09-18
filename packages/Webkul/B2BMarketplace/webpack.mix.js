const mix = require("laravel-mix");
require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = "../../../public/themes/default/assets";
    var publicPath = "../../../public/themes/b2b/assets";
}


mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js([__dirname + "/src/Resources/assets/js/app.js"], "js/b2b-marketplace.js")
    .copyDirectory(__dirname + "/src/Resources/assets/images", publicPath + "/images")
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/b2b-marketplace.css")
    .sass(__dirname + "/src/Resources/assets/sass/shop.scss", "css/b2b-marketplace-shop.css")
    .sass(__dirname + "/src/Resources/assets/sass/admin.scss", "css/b2b-marketplace-admin.css")
    .sass(__dirname + "/src/Resources/assets/sass/b2bvelocity.scss", "css/b2bvelocity.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}