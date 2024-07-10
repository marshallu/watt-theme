# WATT Theme
This is forked from the "_s" for Timber: a dead-simple theme that you can build from. This theme comes ready to use with Alpine JS, Tailwind CSS and Timber.

## Installing the theme
Getting started you will need to install theme dependencies with npm and composer.

1. Navigate to the theme directory in your project folder.
2. Run `composer install` to install Timber, Twig, WordPress Coding Standards and other dependencies in the theme
3. Run `npm install` to install Tailwind CSS and other dependencies.
4. Ensure your project has ACF Pro installed and activated. This theme uses ACF Pro for blocks, custom fields and options pages.

## Theme Development
While developing locally run `npm run dev` to start the Tailwind CSS watcher, which will compile CSS on every file save.

When ready to push to production run `npm run build` to build and minify Tailwind CSS.

Please follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) when developing the theme.

## Other Resources
- [WordPress](https://wordpress.org)
- [Alpine JS](https://alpinejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Timber](https://timber.github.io/docs/)
