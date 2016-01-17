module.exports = function(grunt) {

  // ===========================================================================
  // CONFIGURE GRUNT ===========================================================
  // ===========================================================================
  grunt.initConfig({

    // get the configuration info from package.json ----------------------------
    // this way we can use things like name and version (pkg.name)
    pkg: grunt.file.readJSON('package.json'),

    // javascript linting with jshint
    jshint: {
        options: {
            "force": true
        },
        all: [
            'Gruntfile.js',
            '/src/js/**/*.js'
        ]
    },

    // configure uglify to minify js files -------------------------------------
    uglify: {
      options: {
        banner: '/*\n <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> \n*/\n',
        sourceMap:false
      },
      build: {
        files: {
          'dist/js/main.min.js': 'src/js/main.js'
        }
      }
    },

    // sass
    sass: {
      dist: {
        options: {
          style: 'compressed',
          sourcemap: 'none',
          noCache: true,
        },
        files: {
          'dist/css/style.css': 'src/css/style.scss',
        }
      }
    },

    usebanner: {
        taskName: {
          options: {
            position: 'top',
            banner: '/*\n' +
                'Theme Name: Foursquare Heatery\n' +
                'Theme URI: https://bitbucket.org/weerSan/foursquare_heatery/\n' +
                'Author: Circle Squared Data Labs\n' +
                'Author URI: https://www.csq2.com\n' +
                'Description: Heatery concept applied using the Foursquare API\n' +
                'Version: 0.0.1\n' +
                'Bitbucket Theme URI: https://bitbucket.org/weerSan/foursquare_heatery/\n' +
                'Bitbucket Branch: master\n' +
                'License: GNU General Public License v2 or later\n' +
                'License URI: http://www.gnu.org/licenses/gpl-2.0.html\n' +
                'Text Domain: foursquare_heatery\n' +
                ' */\n',
            linebreak: true
            },
            files: {
                src: [ 'dist/css/style.css' ]
            }
        }
    },

    // configure watch to auto update ------------------------------------------
    watch: {
        options: {
            livereload: true
        },
        files: ['*.html', '*.php'],
        sass: {
            files: ['src/css/style.scss'],
            tasks: ['sass', 'usebanner']
        },
        js: {
            files: ['src/js/*.js'],
            tasks: ['jshint', 'uglify']
        },
        livereload: {
            options: { livereload: true },
            files: ['style.css', 'src/js/*.js']
        }
    }

  });

  // ===========================================================================
  // LOAD GRUNT PLUGINS ========================================================
  // ===========================================================================
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-banner');

  // ===========================================================================
  // CREATE TASKS ==============================================================
  // ===========================================================================
  grunt.registerTask('default', ['sass', 'jshint', 'uglify', 'usebanner', 'watch']);

};
