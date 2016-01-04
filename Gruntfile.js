module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        dirs: {
            jsSrc: 'public/js',
            cssSrc: 'public/css',
            bowerSrc: 'public/bower_components',
            dest: 'public/concat'
        },
        concat: {
            options: {
                separator: '\n',
                process: function (src, filepath) {
                    return '/* OriginalFileName : ' + filepath + ' */ \n\n' + src;
                }
            },
            js: {
                options: {
                    separator: ';\n',
                },
                nonull: true,
                dest: '<%= dirs.dest %>/app.js',
                src: [
                    '<%= dirs.bowerSrc %>/jquery/dist/jquery.js',
                    '<%= dirs.bowerSrc %>/bootstrap/dist/js/bootstrap.js' ,
                    '<%= dirs.jsSrc %>/reset.js',
                ]

            },
            css: {
                nonull: true,
                dest: '<%= dirs.dest %>/app.css',
                src: [
                    '<%= dirs.bowerSrc %>/bootstrap/dist/css/bootstrap.css',
                    '<%= dirs.cssSrc %>/style.css',
                    '<%= dirs.cssSrc %>/errors.css',
                ]
            }
        },
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                compress: {
                    global_defs: {
                        "DEBUG": false
                    },
                    dead_code: true,
                    drop_console: true
                },
                preserveComments: false
            },
            dist: {
                files: {
                    '<%= dirs.dest %>/app.min.js': ['<%= dirs.dest %>/app.js']
                }
            }
        },
        cssmin: {
            dist: {
                files: {
                    '<%= dirs.dest %>/app.min.css': ['<%= dirs.dest %>/app.css']
                }
            }
        }
    });

    // Load the plugin that provides the required tasks.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    // Default task(s).
    grunt.registerTask('default', ['concat', 'uglify', 'cssmin']);

};