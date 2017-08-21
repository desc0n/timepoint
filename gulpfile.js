'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var browserify = require('browserify');
var babelify = require('babelify');
var source = require('vinyl-source-stream');



var reactTasks = {
    'sourceDir': './public/react_source',
    'outPutDir': './public/assets/js/',
    'generateGulpTaskName': (/* string */ task)=>'build-react-' + task,
    'tasks': [
        'summary_table'
    ]
};

    reactTasks.tasks.forEach(function(reactTask){
    var gulpTask = reactTasks.generateGulpTaskName(reactTask);
    var entriesFile = reactTasks.sourceDir + '/' + reactTask + '/index.jsx';
    var outputFile = reactTask + '.js';

    gulp.task(gulpTask, function(){
        browserify({
            entries: entriesFile,
            extensions: ['.jsx', '.js'],
            debug: true
        })
            .transform('babelify', {presets: ['react','es2015']})
            .bundle()
            .pipe(source(outputFile))
            .pipe(gulp.dest(reactTasks.outPutDir));
    });
});

gulp.task('build-react', reactTasks.tasks.map((task)=>reactTasks.generateGulpTaskName(task)));

gulp.task('style', function () {
    return gulp.src('./public/scss/styles.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('watch', ()=> {
    reactTasks.tasks.forEach((reactTask)=>{
    var gulpTask = reactTasks.generateGulpTaskName(reactTask);
var sources = ['jsx', 'js'].map((ext)=>reactTasks.sourceDir + '/' + reactTask + '/**/*.' + ext);
gulp.watch(sources, ()=>gulp.run(gulpTask));
});
});

gulp.task('default', ['build-react', 'style']);