<?php
/*
Plugin Name: TMBI Field Validation
Version: 1.1
Description: Validate required fields.
Author: Facundo Farias
License: GPLv2
*/

require_once( 'vendor/autoload.php' );
require_once( 'inc/framework.php' );
require_once( 'inc/default_validations.php' );

new TMBI_Field_Validation_Framework();
new TMBI_Field_Validation_Default_Rules();
