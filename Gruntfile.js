module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
		
		less: {
			wp_css: {
				files: {
					'Assets/css/shared.css': 'Assets/less/shared.less',
					'Assets/css/front.css': 'Assets/less/front.less',
					'Assets/css/admin.css': 'Assets/less/admin.less'
				}
			}		
		},
		
		concat: {
			wp_css: {
				files: {
					'Assets/css/front.css': ['Assets/css/shared.css', 'Assets/css/front.css'],
					'Assets/css/admin.css': ['Assets/css/shared.css', 'Assets/css/admin.css'],					
				}
			},
			wp_js: {
				files: {
					'Assets/js/front.min.js': ['Assets/js/shared.js', 'Assets/js/front.js'],
					'Assets/js/admin.min.js': ['Assets/js/shared.js', 'Assets/js/admin.js'],					
				}
			}			
		},	
		
		terser: {
			wp_js: {
				files: {
					'Assets/js/front.min.js': ['Assets/js/front.min.js'],
					'Assets/js/admin.min.js': ['Assets/js/admin.min.js']					
				}
			}			
		},
		
		cssmin: {
			wp_css: {
				files: {
					'Assets/css/front.min.css': 'Assets/css/front.css',
					'Assets/css/admin.min.css': 'Assets/css/admin.css'
				}
			}	
		},
		
		watch: {				
			wp_css: {
				files: ['Assets/less/*.less'],
				tasks: ['build_wp_css']
			},
			wp_js: {
				files: ['Assets/js/*.js'],
				tasks: ['build_wp_js']
			}		
		}
  });

 	grunt.loadNpmTasks('grunt-terser');
  grunt.loadNpmTasks('grunt-contrib-concat');  
	grunt.loadNpmTasks('grunt-contrib-cssmin');  
	grunt.loadNpmTasks('grunt-contrib-less');  
  grunt.loadNpmTasks('grunt-contrib-watch');	

  grunt.registerTask('default', [
  	'less',
   	'concat',
 		'terser',
  	'cssmin',
  	'watch'
  ]);

  grunt.registerTask('build_wp_css', [
   	'less:wp_css',
 		'concat:wp_css',   	
   	'cssmin:wp_css'
  ]); 

  grunt.registerTask('build_wp_js', [
 		'concat:wp_js',
   	'terser:wp_js'
  ]);           
};
