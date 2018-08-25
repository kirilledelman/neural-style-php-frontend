<?php

/* Internals */

define ( 'ROOT', realpath(__DIR__ . '/..' ) ); // project directory
define ( 'USER', shell_exec( 'id -u -n' ) ); // user running php script

/* Config */

// set to the location of "th" executable
define ( 'TORCH_PATH', '/Users/kirilledelman/torch/install/bin/th' );

// set to the location of jcjohnson/neural-style repo (needed for /models folder)
define ( 'NEURAL_STYLE_PATH', '/Users/kirilledelman/neural-style/' );

