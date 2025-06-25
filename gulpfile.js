var gulp = require('gulp'),
    sass = require('gulp-sass'),
    livereload = require('gulp-livereload'),
    php2html = require('gulp-php2html'),
    ncp = require('ncp').ncp,
    install = require('gulp-install'),
    prettify = require('gulp-jsbeautifier'),
    prettify2 = require('gulp-html-prettify'),
    minify = require('gulp-html-minifier2'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    fs = require('fs');


/****************** Dist Min Code ***************************/
gulp.task('html',() => {
  gulp.src('index.php')
  .pipe(livereload());
});

gulp.task('sass', () => {
	gulp.src('./assets/scss/*.scss')
  .pipe( sourcemaps.init() )
	.pipe(sass().on('error', sass.logError))
  .pipe(autoprefixer({
    browsers: ['last 2 versions'],
  }))
  .pipe( sourcemaps.write('') )
	.pipe( gulp.dest('assets/css') )
	.pipe(livereload());
});
/*********/


/****************** Dist Min Code ***************************/
gulp.task('dist',() => {
  fs.mkdir( __dirname + '/dist', function(e) {
    if(!e || (e && e.code === 'EEXIST')){
      gulp.start('dist-html');
      // gulp.start('dist-node');
      gulp.start('dist-assets');
    }
  });
});

gulp.task('dist-html', () => {
  gulp.src('./*.php')
      .pipe( php2html() )
      .pipe( minify({
        collapseWhitespace: true,
      }) )
      .pipe( prettify2() )
      .pipe(gulp.dest('./dist'));
});

gulp.task('dist-assets', () => {
  fs.mkdir( __dirname + '/dist/assets/', function(e) {
    if(!e || (e && e.code === 'EEXIST')){
      ncp('./assets/', './dist/assets', function(err) {
        if (err) {
          return console.error(err);
        }
        console.log('Copying assets done');
      });
    }
    else {
      console.log(e);
    }
  });
  fs.mkdir( __dirname + '/dist/email-templates/', function(e) {
    if(!e || (e && e.code === 'EEXIST')){
      ncp('./email-templates/', './dist/email-templates/', function(err) {
        if (err) {
          return console.error(err);
        }
        console.log('Copying email templates done');
      });
    }
    else {
      console.log(e);
    }
  });
  ncp('./gulpfile.js', './dist/gulpfile.js', function(err) {
    if (err) {
      return console.error(err);
    }
    console.log('Copying gulpfile done');
  });
  ncp('./package.json', './dist/package.json', function(err) {
    if (err) {
      return console.error(err);
    }
    console.log('Copying package.json done');
  });
});

gulp.task('dist-node', () => {
  gulp.src( __dirname + '/package.json')
      .pipe( gulp.dest('./dist') )
      .pipe( install({production: true}) );
});
/*********/


/****************** Dist Min Code ***************************/
gulp.task('dist-min',() => {
  fs.mkdir( __dirname + '/dist-min', function(e) {
    if(!e || (e && e.code === 'EEXIST')){
      gulp.start('dist-html-min');
      gulp.start('dist-node-min');
      gulp.start('dist-assets-min');
    }
  });
});

gulp.task('dist-html-min', () => {
  gulp.src('./*.php')
      .pipe( php2html() )
      .pipe( minify({
        collapseWhitespace: true,
      }) )
      .pipe(gulp.dest('./dist-min'));
});

gulp.task('dist-assets-min', () => {
  fs.mkdir( __dirname + '/dist-min/assets/', function(e) {
    if(!e || (e && e.code === 'EEXIST')){
      ncp('./assets/', './dist-min/assets', function(err) {
        if (err) {
          return console.error(err);
        }
        console.log('Copying assets done');
      });
    }
    else {
      console.log(e);
    }
  });
  fs.mkdir( __dirname + '/dist-min/email-templates/', function(e) {
    if(!e || (e && e.code === 'EEXIST')){
      ncp('./email-templates/', './dist-min/email-templates', function(err) {
        if (err) {
          return console.error(err);
        }
        console.log('Copying email templates done');
      });
    }
    else {
      console.log(e);
    }
  });
  ncp('./gulpfile.js', './dist-min/gulpfile.js', function(err) {
    if (err) {
      return console.error(err);
    }
    console.log('Copying gulpfile done');
  });
});

gulp.task('dist-node-min', () => {
  gulp.src( __dirname + '/package.json')
      .pipe( gulp.dest('./dist-min') )
      .pipe( install({production: true}) );
});
/*********/

gulp.task('default', () => {
	gulp.watch(['./**/*.scss', '!./node_modules/**'],{ interval: 500 } ,['sass']);
  gulp.watch(['./**/*.{html,php,js}', '!./node_modules/**'], { interval: 500 } ,['html']);
	livereload.listen();
});
