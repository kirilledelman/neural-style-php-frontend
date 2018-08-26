<?php

/* Internals */

define ( 'ROOT', realpath(__DIR__ . '/..' ) ); // project directory
define ( 'USER', trim(  shell_exec( 'whoami' ) ) ); // user running php script

/* Config */

// set to the location of "th" executable - assumes same folder as this project
// but can be changed to a different location. Just make sure that user apache
// can execute `th`
define ( 'TORCH_PATH', ROOT . '/torch/install/bin/th' );

// set to the location of jcjohnson/neural-style repo (needed for /models folder)
define ( 'NEURAL_STYLE_PATH', ROOT . '/neural-style/' );

