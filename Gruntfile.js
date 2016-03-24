module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        dirs: {
            jsSrc: 'public/js',
            cssSrc: 'public/css',
            bowerSrc: 'public/bower_components',
            nodeSrc: 'node_modules',
            public: 'public',
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
                    '<%= dirs.bowerSrc %>/chosen/chosen.jquery.min.js',
                    '<%= dirs.bowerSrc %>/bootstrap/dist/js/bootstrap.js' ,
                    '<%= dirs.nodeSrc %>/bootbox/bootbox.js' ,
                    '<%= dirs.bowerSrc %>/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
                    '<%= dirs.bowerSrc %>/metisMenu/dist/metisMenu.js',
                    '<%= dirs.jsSrc %>/datepicker.js',
                    '<%= dirs.nodeSrc %>/jquery-latitude-longitude-picker-gmaps/js/jquery-gmaps-latlon-picker.js',
                    '<%= dirs.jsSrc %>/diff_match_patch.js',
                    '<%= dirs.nodeSrc %>/jquery-prettytextdiff/jquery.pretty-text-diff.js',
                    '<%= dirs.jsSrc %>/form.js',
                    '<%= dirs.jsSrc %>/reset.js',
                    '<%= dirs.jsSrc %>/menu.js',
                    '<%= dirs.jsSrc %>/menuItemCRUD.js',
                    '<%= dirs.jsSrc %>/pageCRUD.js',
                    '<%= dirs.jsSrc %>/courseCRUD.js',
                    '<%= dirs.jsSrc %>/courseEventCRUD.js',
                    '<%= dirs.jsSrc %>/resourceCRUD.js',
                    '<%= dirs.jsSrc %>/orgReg.js',                    
                    '<%= dirs.jsSrc %>/CKEditor_config.js',                    
                ]

            },
            css: {
                nonull: true,
                dest: '<%= dirs.dest %>/app.css',
                src: [
                    '<%= dirs.bowerSrc %>/bootstrap/dist/css/bootstrap.css',
                    '<%= dirs.bowerSrc %>/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
                    '<%= dirs.bowerSrc %>/chosen-bootstrap/chosen.bootstrap.css',
                    '<%= dirs.bowerSrc %>/metisMenu/dist/metisMenu.css',
                    '<%= dirs.bowerSrc %>/font-awesome/css/font-awesome.css',
                    '<%= dirs.nodeSrc %>/jquery-latitude-longitude-picker-gmaps/css/jquery-gmaps-latlon-picker.css',
                    '<%= dirs.cssSrc %>/style.css',
                    '<%= dirs.cssSrc %>/form.css',
                    '<%= dirs.cssSrc %>/userForm.css',
                    '<%= dirs.cssSrc %>/courseForm.css',
                    '<%= dirs.cssSrc %>/errors.css',
                    '<%= dirs.cssSrc %>/inactive.css',
                    '<%= dirs.cssSrc %>/CKEditor_Style.css',
                    '<%= dirs.cssSrc %>/evaluation.css',
                    '<%= dirs.cssSrc %>/diff.css',
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
        },
        copy: {
            dist: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= dirs.bowerSrc %>/font-awesome',
                    src: ['fonts/*.*'],
                    dest: '<%= dirs.public %>'
                }]
            }
        }
    });

    // Load the plugin that provides the required tasks.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-copy');

    // Default task(s).
    grunt.registerTask('default', ['concat', 'uglify', 'cssmin', 'copy']);

};
