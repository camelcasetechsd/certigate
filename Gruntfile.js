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
            dest: 'public/concat',
            themes: 'themes'
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
                    '<%= dirs.bowerSrc %>/bootstrap/dist/js/bootstrap.js',
                    '<%= dirs.nodeSrc %>/bootbox/bootbox.js',
                    '<%= dirs.bowerSrc %>/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.plus.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.plugin.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.picker.js', // base calendar
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.ummalqura.js', // prototype and calcuations
                    '<%= dirs.jsSrc %>/datepicker.js',
                    '<%= dirs.bowerSrc %>/metisMenu/dist/metisMenu.js',
                    '<%= dirs.nodeSrc %>/jquery-latitude-longitude-picker-gmaps/js/jquery-gmaps-latlon-picker.js',
                    '<%= dirs.jsSrc %>/diff_match_patch.js',
                    '<%= dirs.nodeSrc %>/jquery-prettytextdiff/jquery.pretty-text-diff.js',
                    '<%= dirs.bowerSrc %>/addtocalendar/atc.min.js',
                    '<%= dirs.jsSrc %>/addToCalendar.js',
                    '<%= dirs.jsSrc %>/form.js',
                    '<%= dirs.jsSrc %>/signin.js',
                    '<%= dirs.jsSrc %>/reset.js',
                    '<%= dirs.jsSrc %>/menu.js',
                    '<%= dirs.jsSrc %>/menuItemCRUD.js',
                    '<%= dirs.jsSrc %>/pageCRUD.js',
                    '<%= dirs.jsSrc %>/courseCRUD.js',
                    '<%= dirs.jsSrc %>/courseEventCRUD.js',
                    '<%= dirs.jsSrc %>/resourceCRUD.js',
                    '<%= dirs.jsSrc %>/orgReg.js',
                    '<%= dirs.jsSrc %>/CKEditor_config.js',
                    '<%= dirs.jsSrc %>/homepage.js',
                ]

            },
            js_rtl: {
                options: {
                    separator: ';\n',
                },
                nonull: true,
                dest: '<%= dirs.dest %>/app_rtl.js',
                src: [
                    '<%= dirs.bowerSrc %>/jquery/dist/jquery.js',
                    '<%= dirs.bowerSrc %>/chosen/chosen.jquery.min.js',
                    '<%= dirs.bowerSrc %>/bootstrap/dist/js/bootstrap.js',
                    '<%= dirs.nodeSrc %>/bootbox/bootbox.js',
                    '<%= dirs.bowerSrc %>/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars-ar.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.plus.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.plugin.js',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.picker.js', // base calendar
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.picker-ar.js', // base calendar
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.ummalqura.js', // prototype and calcuations
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.ummalqura-ar.js', // prototype and calcuations
                    '<%= dirs.jsSrc %>/datepicker.js',
                    '<%= dirs.bowerSrc %>/metisMenu/dist/metisMenu.js',
                    '<%= dirs.nodeSrc %>/jquery-latitude-longitude-picker-gmaps/js/jquery-gmaps-latlon-picker.js',
                    '<%= dirs.jsSrc %>/diff_match_patch.js',
                    '<%= dirs.nodeSrc %>/jquery-prettytextdiff/jquery.pretty-text-diff.js',
                    '<%= dirs.bowerSrc %>/addtocalendar/atc.min.js',
                    '<%= dirs.jsSrc %>/addToCalendar.js',
                    '<%= dirs.jsSrc %>/form.js',
                    '<%= dirs.jsSrc %>/signin.js',
                    '<%= dirs.jsSrc %>/reset.js',
                    '<%= dirs.jsSrc %>/menu.js',
                    '<%= dirs.jsSrc %>/menuItemCRUD.js',
                    '<%= dirs.jsSrc %>/pageCRUD.js',
                    '<%= dirs.jsSrc %>/courseCRUD.js',
                    '<%= dirs.jsSrc %>/courseEventCRUD.js',
                    '<%= dirs.jsSrc %>/resourceCRUD.js',
                    '<%= dirs.jsSrc %>/orgReg.js',
                    '<%= dirs.jsSrc %>/CKEditor_config.js',
                    '<%= dirs.jsSrc %>/homepage.js',
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
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.picker.css',
                    '<%= dirs.cssSrc %>/custom.calendars.css',
                    '<%= dirs.bowerSrc %>/font-awesome/css/font-awesome.css',
                    '<%= dirs.nodeSrc %>/jquery-latitude-longitude-picker-gmaps/css/jquery-gmaps-latlon-picker.css',
                    '<%= dirs.cssSrc %>/style.css',
                    '<%= dirs.cssSrc %>/certigateTheme.css',
                    '<%= dirs.cssSrc %>/form.css',
                    '<%= dirs.cssSrc %>/chat.css',
                    '<%= dirs.cssSrc %>/userForm.css',
                    '<%= dirs.cssSrc %>/courseForm.css',
                    '<%= dirs.cssSrc %>/course-calendar.css',
                    '<%= dirs.cssSrc %>/course-details.css',
                    '<%= dirs.cssSrc %>/errors.css',
                    '<%= dirs.cssSrc %>/inactive.css',
                    '<%= dirs.cssSrc %>/CKEditor_Style.css',
                    '<%= dirs.cssSrc %>/evaluation.css',
                    '<%= dirs.cssSrc %>/diff.css',
                    '<%= dirs.cssSrc %>/atc-style-menu-wb.css',
                    // certigate theme css
                    '<%= dirs.themes %>/certigate/assets/css/carousel.css'

                ]
            },
            css_rtl: {
                nonull: true,
                dest: '<%= dirs.dest %>/app_rtl.css',
                src: [
                    '<%= dirs.bowerSrc %>/bootstrap/dist/css/bootstrap.css',
                    '<%= dirs.bowerSrc %>/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
                    '<%= dirs.bowerSrc %>/chosen-bootstrap/chosen.bootstrap.css',
                    // bootstrap rtl
                    '<%= dirs.cssSrc %>/bootstrap.rtl.css',
//                    '<%= dirs.bowerSrc %>/metisMenu/dist/metisMenu.css',
                    '<%= dirs.bowerSrc %>/font-awesome/css/font-awesome.css',
                    '<%= dirs.nodeSrc %>/jquery-latitude-longitude-picker-gmaps/css/jquery-gmaps-latlon-picker.css',
                    '<%= dirs.jsSrc %>/datepicker/jquery.calendars.picker.css',
                    '<%= dirs.cssSrc %>/custom.calendars.rtl.css',
                    '<%= dirs.cssSrc %>/style.css',
                    '<%= dirs.cssSrc %>/certigateTheme.css',
                    '<%= dirs.cssSrc %>/form.css',
                    '<%= dirs.cssSrc %>/chat.css',
                    '<%= dirs.cssSrc %>/userForm.css',
                    '<%= dirs.cssSrc %>/course-calendar.css',
                    '<%= dirs.cssSrc %>/course-details.css',
                    '<%= dirs.cssSrc %>/courseForm.css',
                    '<%= dirs.cssSrc %>/errors.css',
                    '<%= dirs.cssSrc %>/inactive.css',
                    '<%= dirs.cssSrc %>/CKEditor_Style.css',
                    '<%= dirs.cssSrc %>/evaluation.css',
                    '<%= dirs.cssSrc %>/diff.css',
                    '<%= dirs.cssSrc %>/atc-style-menu-wb.css',
                    // certigate theme css
                    '<%= dirs.themes %>/certigate/assets/css/carousel.rtl.css',
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
                    '<%= dirs.dest %>/app.min.js': ['<%= dirs.dest %>/app.js'],
                    '<%= dirs.dest %>/app_rtl.min.js': ['<%= dirs.dest %>/app_rtl.js']
                }
            }
        },
        cssmin: {
            dist: {
                files: {
                    '<%= dirs.dest %>/app.min.css': ['<%= dirs.dest %>/app.css'],
                    '<%= dirs.dest %>/app_rtl.min.css': ['<%= dirs.dest %>/app_rtl.css']
                }
            }
        },
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        dot: true,
                        cwd: '<%= dirs.bowerSrc %>/font-awesome',
                        src: ['fonts/*.*'],
                        dest: '<%= dirs.public %>'
                    },
                    {
                        expand: true,
                        dot: true,
                        cwd: '<%= dirs.themes %>/certigate/assets',
                        src: ['images/*.*'],
                        dest: '<%= dirs.public %>'
                    }
                ]
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
