module.exports = function (grunt) {

	'use strict';

	// Project configuration
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		addtextdomain: {
			options: {
				textdomain: 'gs-acf-donations-block',
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: ['*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*']
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					exclude: ['\.git/*', 'bin/*', 'node_modules/*', 'tests/*'],
					mainFile: 'gs-acf-donations-block.php',
					potFilename: 'gs-acf-donations-block.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},

		concat: {
			/**
			 * navigation.js and skip-link...js are both _S default files.
			 * All edits should be made in main.js if they are to be concat'd into
			 * our production scripts.min.js file.
			 */
			dist: {
				src: [
					'blocks/donations/assets/js/main.js'
				],
				dest: 'blocks/donations/assets/js/scripts.js'
			}
		},

		uglify: {
			/*
			* scripts.js is now uglified into scripts.min.js, which should be loaded on the front end
			*/
			scripts: {
				src: 'blocks/donations/assets/js/scripts.js',
				dest: 'blocks/donations/assets/js/scripts.min.js'
			}
		},

		sass: {
			/*
			* Output /sass folder as style.css in root folder
			*/
			dist: {
				options: {
					style: 'compressed',
					sourcemap: 'none',
					cacheLocation: 'blocks/donations/assets/sass/.sass-cache'
				},
				files: {
					'blocks/donations/assets/style.css': 'blocks/donations/assets/sass/style.scss'
				}
			}
		},

		autoprefixer: {
			/*
			 * Output /sass folder as style.css in root folder
			 */
			dist: {
				files: {
					'blocks/donations/assets/style.css': 'blocks/donations/assets/style.css'
				}
			}
		},

	});

	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-autoprefixer');

	// Do it!
	grunt.registerTask('default', ['concat', 'uglify', 'sass', 'autoprefixer']);

	grunt.registerTask('i18n', ['addtextdomain', 'makepot']);
	grunt.registerTask('readme', ['wp_readme_to_markdown']);

	grunt.util.linefeed = '\n';

};
