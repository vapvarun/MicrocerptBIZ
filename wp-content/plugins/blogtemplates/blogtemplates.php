<?php
  /*
Plugin Name: New Blog Templates
Plugin URI: http://premium.wpmudev.org/project/new-blog-template
Description: Allows the site admin to create new blogs based on templates, to speed up the blog creation process
Author: Jason DeVelvis, Ulrich Sossou (Incsub)
Author URI: http://premium.wpmudev.org/
Version: 1.4
Network: true
Text Domain: blog_templates
WDP ID: 130
*/

/*  Copyright 2010-2011 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !is_multisite() )
	exit( __( 'The New Blog Template plugin is only compatible with WordPress Multisite.', 'blog_templates' ) );

require_once( 'blogtemplatesfiles/blog_templates.php' );
require_once( 'blogtemplatesfiles/filters.php' );

/**
 * Show notification if WPMUDEV Update Notifications plugin is not installed
 **/

