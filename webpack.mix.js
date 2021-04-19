const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-purgecss');

mix.js('src/nfl-teams-public.js', 'public/js/');

mix.sass('src/nfl-teams-public.scss', 'public/css/').purgeCss({
	enabled: true,
	content: [path.join(__dirname, 'public/**/*.php')],
});
