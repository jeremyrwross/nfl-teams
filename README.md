# NFL Teams

A basic WordPress plugin built to show a list of NFL teams returned from an API.

## Libraries Used

* [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
* [WordPress HTTP API](https://developer.wordpress.org/plugins/http-api/)
* [WordPress Settings API](https://developer.wordpress.org/plugins/settings/settings-api/)
* [WordPress Transients API](https://codex.wordpress.org/Transients_API)
* [Football Icon](https://commons.wikimedia.org/wiki/File:PICOL_icon_Football.svg)
* [Bootstrap 5](https://getbootstrap.com/)
* [Laravel Mix](https://laravel-mix.com/)
* [PurgeCSS](https://purgecss.com/)


## Development

To speed up development this plugin uses [Boostrap 5](https://getbootstrap.com/docs/5.0/getting-started/introduction/) to leverage the [tabs component](https://getbootstrap.com/docs/5.0/components/navs-tabs/) and [utility classes](https://getbootstrap.com/docs/5.0/utilities/spacing/). [Laravel Mix](https://laravel-mix.com/) is used to compile SCSS and JavaScript.

Bootstrap 5 was chosen because it no longer requires jQuery as a dependency. Only components that are required are imported into the JavaScript and SCSS. To further optimize the plugins CSS, PurgeCSS is used to remove unused CSS.

### Setup and Usage

* Run `npm install` to install all dependencies.
* Run `npm run watch` to have Laravel Mix watch all SCSS and JavaScript files for changes.

