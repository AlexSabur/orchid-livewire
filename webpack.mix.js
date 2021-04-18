const mix = require('laravel-mix');

if (mix.inProduction()) {
  mix.version();
}

mix
  .js('src/js/livewire-turbolinks.js', 'js/livewire-turbolinks.js')
  .setPublicPath('public');

