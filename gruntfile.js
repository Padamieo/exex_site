module.exports = function(grunt){

  var pkg = grunt.file.readJSON('package.json');

  require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: pkg,

		copy:{
      build_wordpress:{
        files:[{
          cwd: 'bower_components/wordpress/',
          src: ['**'],
          dest: 'build/',
          nonull: false,
          expand: true,
          flatten: false,
          filter: 'isFile',
        }]
      },
			plugins:{
				files:[{
					cwd: 'bower_components/',
					src: [
            'wp-maintenance-mode/**',
						'ninja-forms/**',
            'woocommerce/**',
            'bbpress/**'
					],
					dest: 'build/wp-content/plugins/',
					nonull: false,
					expand: true,
					flatten: false,
					filter: 'isFile',
				}]
			},
      wordpress_toplevel: {
        files: [{
          cwd: 'wordpress/',
          src: [
            'BingSiteAuth.xml',
            'favicon.ico',
            'google7389dbcbf91df492.html'
          ],
          dest: '../build/',
          nonull: false,
          expand: true,
          flatten: false,
          filter: 'isFile',
        }]
      }
		},

    clean: {
			tidy: {
				src: [
					"build/*.{php,html,txt}",
					"!build/wp-config.php",
					"!build/.htaccess",
					"build/wp-admin/**",
					"build/wp-includes/**",
					"build/wp-content/themes/**",
					"build/wp-content/plugins/**"
				]
			},
			setup: {
				src: [
					"build/*.{html,txt}",
					"build/wp-config-sample.php"
				]
			}
		},
    run: {
      main:{
        options: {
          wait: true,
          cwd: 'src/main/',
        },
        exec: "grunt build"
      },
      motg:{
        options: {
          wait: true,
          cwd: 'src/motg/',
        },
        exec: "grunt build"
      },
      aime:{
        options: {
          wait: true,
          cwd: 'src/aime/',
        },
        exec: "grunt build"
      }

    }

	});

	// Default build task creating everything on the site
	grunt.registerTask('default', [
    'copy:build_wordpress',
    'copy:plugins',
    'run:main',
    'run:motg'
  ]);

  grunt.registerTask('wordpress', [
    'copy:build_wordpress'
  ]);

  grunt.registerTask('plugins', [
    'copy:plugins'
  ])


};
