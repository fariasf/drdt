<?php
/*
Plugin Name: RD Listicle Post Type
Version: 1.1.0
Description: Add the listicle custom post type <a href='https://readersdigest.atlassian.net/browse/WPDT-1053' target='_blank'>Read more at WPDT-3057 ...</a> For more about this see <a hreaf'https://en.wikipedia.org/wiki/Listicle ' target='_blank'>Listicles on Wikipedia</a> and <a href='http://mag.uchicago.edu/arts-humanities/listicle-literary-form' target='_blank'>The University of Chicago Magazine</a>.
Author: Mikel King
Text Domain: listicle-post-type
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause

	Copyright (C) 2016, Mikel King, rd.com, (mikel.king AT rd DOT com)
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

		* Redistributions of source code must retain the above copyright notice, this
		list of conditions and the following disclaimer.

		* Redistributions in binary form must reproduce the above copyright notice,
		this list of conditions and the following disclaimer in the documentation
		and/or other materials provided with the distribution.

		* Neither the name of the {organization} nor the names of its
		contributors may be used to endorse or promote products derived from
		this software without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
	IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
	FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
	DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
	SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
	CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
	OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
	OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
require( 'inc/listicle-cpt.php' );
require( 'inc/listicle-post-navigation.php' );
require( 'inc/listicle-meta-boxes.php' );
require( 'inc/media-attachment-field.php' );
require( 'inc/rd-listicle-ui.php' );
require( 'inc/rd-listicle-images.php' );
require( 'inc/rd-listicle-embed-video.php' );
//require( 'inc/rd-listicle-shortcode.php' ); //Removed this as we are doing this function in multisite plugin TMBI Embedded Listicle

class Listicle_Controller extends WP_Base {
	const VERSION     = '1.1.0';

	public $listicle;

	public function __construct() {
		parent::__construct();

		global $lpn;
		// sets up the activation, deactivation & uninstall plugin hooks
		$this->init();
		$this->listicle = new Listicle_CPT();

		//add listicle navigation instance
		if ( class_exists( 'Listicle_Post_Navigation' ) ) {
			$lpn = new Listicle_Post_Navigation();
		}
		if ( class_exists( 'Media_Attachments' ) ) {
			new Media_Attachments();
		}

		if ( class_exists( 'Listicle_Meta_Boxes' ) ) {
			$obj = new Listicle_Meta_Boxes();
		}
		$rdlui = RD_Listicle_UI::get_instance();
		$rd_listicle_video = RD_Listicle_Video::get_instance();
		$rd_listicle_image_sizes = RD_Listicle_Image_Sizes::get_instance();
	}

	public function activator() {
		// setup listicle post type
		$this->listicle = new Listicle_CPT();

		// flush the rules to add the appropriate endpoint
		flush_rewrite_rules();
	}
}
$lpn = null;
$lc = Listicle_Controller::get_instance();
