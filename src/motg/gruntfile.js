module.exports = function(grunt){

  var pkg = grunt.file.readJSON('../../package.json');

	grunt.initConfig({
		pkg: pkg,

		copy:{
			build_theme:{
				files:[{
					cwd: 'src/motg/src/',
					src: ['**', '!**/**.less', '!**/**.css', '!**/**.js', '!**/**.{png,jpg,gif}', 'style.css'],
					dest: 'build/wp-content/themes/motg',
					nonull: false,
					expand: true,
					flatten: false,
					filter: 'isFile',
				}]
			}
		},

		watch: {
			options: {
			  livereload: true,
        spawn: false,
			},
			js:{
				files: ['src/motg/src/**/*.js'],
				tasks: ['uglify']
			},
			css:{
				files: ['src/motg/src/css/*.css'],
				tasks: ['cssmin']
			},
      less:{
        files: "src/motg/src/less/**/*.less",
        tasks: ['less:live']
      },
			copy:{
				files: ['src/motg/src/**/**.php','src/motg/src/**/**.{png,jpg,gif}'],
				tasks: ['copy:build_theme']
			}
		},

    uglify:{
			options:{
				banner: '/*build/wp-content/themes/ V<%= pkg.version %> made on <%= grunt.template.today("yyyy-mm-dd") %>*/\r',
				mangle: true,
        beautify: true
			},
			target:{
				files:{
					'build/wp-content/themes/motg/js/scripts.js': [
						'src/motg/src/js/test.js',
            'src/motg/src/js/functions.js'
						]
				}
			}
		},

		cssmin: {
			minify: {
				expand: true,
				cwd: 'src/motg/src/css/',
				src: ['**/*.css'],
				dest: 'build/wp-content/themes/motg/css',
				ext: '.css',
			}
		},

    imagemin:{
			dynamic:{
				files: [{
					expand: true,
					cwd: 'src/motg/src/img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'build/wp-content/themes/motg/img/',
				}]
			}
		},

    less: {
      live: {
        options: {
          strictMath: true,
          sourceMap: true,
          outputSourceFiles: true,
          sourceMapURL: 'style.css.map',
          sourceMapFilename: 'build/wp-content/themes/motg/css/style.css.map'
        },
        src: 'src/motg/src/less/style.less',
        dest: 'build/wp-content/themes/motg/css/style.css'
      }
    }

	});

  //var cwd = process.cwd();
  process.chdir('../../');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-newer');

  process.chdir(process.cwd());

	// Default build task creating everything on the site
	grunt.registerTask('build', [
    'newer:copy:build_theme',
    'newer:cssmin',
    'newer:uglify',
    'newer:imagemin',
    'less:live'
  ]);

  grunt.registerTask('b', [
    'copy:build_theme'
  ]);

  // updates everything theme related
	grunt.registerTask("update", [
    'newer:copy:build_theme',
    'newer:cssmin',
    'newer:uglify',
    'newer:imagemin',
    'less:live'
  ]);

	grunt.registerTask("default", ['watch']);

  grunt.registerTask("css", ['less:live']);

};
