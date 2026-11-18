# Codigo Wordpress Template for SD Engineerss
A clean slate Wordpress theme template with Bootstrap(5), Sass, and a Webpack config with Babel.
Based in [understrap.com](https://understrap.com) and in [Codigo Theme](https://github.com/pablorica/wordpress_codigo_theme)


[![version](https://img.shields.io/badge/version-1.2.0-green.svg)](https://semver.org)



## Production Version

[https://sd-engineers.com/](https://sd-engineers.com/)


## Staging Version

[https://sdengineersstg.wpengine.com/](https://sdengineersstg.wpengine.com/)

 - Username:   sdengineersstg
 - password:   tm-studio


 ## Local Version

[http://local.danny.sdengineers.com/](http://local.danny.sdengineers.com/)


---

## Hubspot

We had developed a custom plugin to connect Wordpress forms to HubSpot and reveals gated content after successful submission.

- [SD HubSpot](wp-content/plugins/sd-hubspot/README.md)

---

### Understrap

Website: [understrap.com](https://understrap.com)

Parent Theme Project: [github.com/understrap/understrap](https://github.com/understrap/understrap)

Premium Child Themes: [understrap.com/child-themes/](https://understrap.com/child-themes/)

Understrap customised version [Pablo Rica Understrap Theme](https://github.com/pablorica/understrap-academy)

## Basic Features

-   Combines Underscore’s PHP/JS files and Bootstrap’s HTML/CSS/JS.
-   Comes with Bootstrap v5 Sass source files and additional .scss files. Nicely sorted and ready to add your own variables and customize the Bootstrap variables.
-   Uses sass and postCSS to handle compiling all of the styles into one style sheet. The theme also includes rollup.js to handle javascript compilation and minification.
-   Uses a single minified CSS file for all the basic stuff.
-   [Font Awesome](http://fortawesome.github.io/Font-Awesome/) integration (v4.7.0)
-   Jetpack ready
-   WooCommerce support
-   Contact Form 7 support
-   Translation ready

## Breakpoints

- `xs` - 0px
- `sm` - 576px
- `md` - 782px
- `lg` - 992px
- `xl` - 1200px
- `xxl` - 1400px
- `3xl` - 1720px
- `4xl` - 1600px


## Installation

Understrap uses [npm](https://www.npmjs.com/) as manager for dependency packages like Bootstrap and Underscores. And it uses tools like [rollup.js](https://www.rollupjs.org/) and [postCSS](https://postcss.org) as taskrunners to compile .scss code into .css, minify .js code, etc. The following chapter describes the usage and workflow.

### [Preparations: Install node.js](https://docs.understrap.com/#/understrap-child/npm?id=preparations-install-nodejs)

At first you need node.js installed on your computer globally. If you already done this before skip this section. If not: You have to install node.js (comes along with npm) before you can proceed. To install node.js visit the node.js website for the latest installer for your OS. Download and install it like any other program, too.

We also recommend using [NVM - Node Version Manager](https://github.com/nvm-sh/nvm) to keep closer control over your version of node.js and switch between versions.

### [Installing dependencies](https://docs.understrap.com/#/understrap-child/npm?id=installing-dependencies)

-   Make sure you have installed Node.js and Browser-Sync* (* optional, if you wanna use it) on your computer globally
-   Then open your terminal and browse to the location of your Understrap copy
-   Run: `npm install`

#### Engine

There is an `engines` option in `package.json`. It set requirements for node and npm versions:

```json
"engines": {
    "npm" : ">=7.0.5",
    "node": ">=14"
}
```

To enforce this via npm you can create a   `.npmrc` file where sthe `engines-strict` option is set to true, which will cause npm commands such as npm install to fail if the required engine versions to not match:
##### .npmrc

```javascript
engine-strict=true
```

### [Running](https://docs.understrap.com/#/understrap-child/npm?id=running)

To work and compile your Sass files on the fly start:

```
npm run watch
```

Or, to run with Browser-Sync:

First change the browser-sync options to reflect your environment in the file `/src/build/browser-sync.config.js` in the beginning of the file:

```
module.exports = {
    "proxy": "localhost/", // Change here
    "notify": false,
    "files": ["./css/*.min.css", "./js/*.min.js", "./**/*.php"]
};
```

Replace `localhost/theme_test/` with the URL to your local WordPress test environment. For example if you run MAMP and your WordPress installation is placed in a htdocs subfolder called `/understrap` you have to add `localhost:8888/understrap`. Its the same URL you have to type in to see your local WordPress installation. Then run:

```
npm run watch-bs
```


## Required Plugins
* [Carousel Slider Block for Gutenberg](https://en-gb.wordpress.org/plugins/carousel-block/)
* [Page Builder Gutenberg Blocks – CoBlocks](https://en-gb.wordpress.org/plugins/coblocks/)
* [Advanced Custom Fields](https://www.advancedcustomfields.com/pro/)


### Debug and Development Plugins
* [FakerPress](https://wordpress.org/plugins/fakerpress/)
* [Custom Post Type UI](https://en-gb.wordpress.org/plugins/custom-post-type-ui/)
* [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/)
* [Query Monitor](https://en-gb.wordpress.org/plugins/query-monitor/)
* [Default Admin Color Scheme](https://en-gb.wordpress.org/plugins/default-admin-color-scheme/)

### Recommended Plugins
* [Yoast Duplicate Post](https://en-gb.wordpress.org/plugins/duplicate-post/)
* [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/)


## Acknowledgements

* [HTML 5 Blank](https://github.com/toddmotto/html5blank)

## Copyright and License

Copyright 2023 Codigo Wordpress Theme released under the [MIT](https://github.com/pablorica/tavaziva/blob/main/LICENSE) license.

## Versioning

We use [SemVer](https://semver.org/) for versioning. For the versions available, [list of tags can be found in this page](https://github.com/pablorica/tavaziva/tags).

### Changelog

[CHANGELOG.md](https://github.com/pablorica/tavaziva/blob/main/CHANGELOG.md)