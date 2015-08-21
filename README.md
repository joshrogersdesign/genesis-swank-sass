=======
# Genesis Sample Theme (with Sass & Grunt)

Github project link: https://github.com/gregrickaby/genesis-sample/tree/sass

This fork includes Sass and Grunt (and even the Grunt sound). I *try* to keep it up to date with Genesis 2.0 development. Your pull requests are welcome. Now, go get Sassy!

## Theme Installation Instructions

- Install Node: http://nodejs.org/
- Install Grunt: `npm install -g grunt-cli`
- Clone this repository: `git@github.com:gregrickaby/genesis-sample.git`
- Navigate to the cloned repository's directory:
`cd /applications/MAMP/htdocs/genesis-sample`
- Type: `npm install`

The Node Package Manager will now install all the required dependencies for Grunt.

![How to use Grunt](http://i.imgur.com/D2yoFdu.gif)

That's it! You're now ready to use Grunt!

## Using Grunt

- `grunt styles` - Will compile Sass, auto prefix, css comb, and then minify style.css to style.min.css
- `grunt javascript` - Will concatenate scripts, and then minify them
- `grunt imageminnewer` - Will PNG Smush (optimize) all images

- `grunt` - Will run all tasks mentioned above

- `grunt watch` - Will watch for changes and then compile Sass and JS

## Better Script Management

I've created a `/js/` folder and inside it a `/partials/` folder. Any scripts placed into the partials folder will be concatenated and placed into `project.js` when you run `grunt` or `grunt javascript`.

This fork also enqueues Live Reload for extensionless live reloading while using Grunt Watch. It also does a check to see if the `SCRIPT_DEBUG` constant is present in `wp_config.php`.

To load minified versions of styles and scripts add this to `wp_config.php`:

- `define('SCRIPT_DEBUG', TRUE);`  <-- will load un-minified files
- `define('SCRIPT_DEBUG', FALSE);` <-- will load minified files

## Theme Support

Please visit http://my.studiopress.com/help/ for theme support.
