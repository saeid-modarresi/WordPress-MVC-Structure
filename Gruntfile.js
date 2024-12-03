module.exports = function (grunt) {
    grunt.initConfig({
        cssmin: {
            options: {
                report: 'gzip',
            },
            frontend: {
                files: {
                    'assets/frontend.min.css': [
                        'assets/libs/bootstrap/bootstrap.min.css',
                        'assets/css/frontend/frontend-style.css'
                    ]
                }
            }
        },
        uglify: {
            frontend: {
                files: {
                    'assets/frontend.min.js': [
                        'assets/libs/jquery/jquery-3.6.0.min.js',
                        'assets/libs/bootstrap/bootstrap.min.js'
                    ]
                }
            }
        },
        watch: {
            styles: {
                files: [
                    'assets/css/**/*.css',
                    'templates/**/*.css',
                ],
                tasks: ['cssmin:frontend'],
                options: {
                    livereload: true,
                },
            },
            scripts: {
                files: [
                    'assets/js/**/*.js',
                    'templates/**/*.js'
                ],
                tasks: ['uglify:frontend'],
                options: {
                    livereload: true,
                },
            },
        },
        concurrent: {
            options: {
                logConcurrentOutput: true
            },
            target: ['watch', 'cssmin:frontend', 'uglify:frontend']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-concurrent');

    grunt.registerTask('default', ['concurrent']);
};
