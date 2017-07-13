=== The .TUBE WordPress Theme ===
Contributors: .TUBE gTLD
Requires at least: WordPress 4.7
Tested up to: WordPress 4.7.5
Version: 1.1.5
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: one-column, custom-colors, custom-menu, featured-images, featured-image-header, microformats, rtl-language-support, threaded-comments, full-width-template, translation-ready

== Description ==
The .TUBE WordPress Theme is designed for people who create and share video, and also works great for photographers, journalists, and more. It provides a modern Booststrap-based layout with large featured images, and looks great on mobile, tablet, and desktop. Plus, the .TUBE Theme for WordPress has plenty of customization options, is child-theme-friendly, and includes tons of action hooks and filters for the more technically inclined.

* Responsive Booststrap-based layout
* Modern look and feel with large images
* Built-in color schemes to get you started
* Grid-based layout with numbered pagination
* Extensive customizer options including logos and labels
* Support for social profile links (integrates with Yoast SEO)
* Tons of action hooks and filters for advanced customization
* Complete Spanish-language translation included

== Installation ==

1. In your admin panel, go to Appearance -> Themes and click the 'Add New' button.
2. Type in .TUBE in the search form and press the 'Enter' key on your keyboard.
3. Click on the 'Activate' button to use the .TUBE WordPress Theme right away.
4. Visit the [.TUBE Theme site](https://www.get.tube/wordpress/tube-theme) for comprehensive documentation and customization tips.
5. Navigate to Appearance > Customize in your admin area to dial in your settings.

The .TUBE WordPress Theme also works great with the [.TUBE Video Curator Plugin](https://www.get.tube/wordpress/tube-video-curator-plugin), making it easy to import videos and sync with existing YouTube and Vimeo channels. Look for .TUBE Video Curator Plugin in the WP Plugin Directory.

== Copyright ==

The .TUBE WordPress Theme, Copyright 2017, TUBE®
The .TUBE WordPress Theme is distributed under the terms of the GNU GPL

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.


The .TUBE WordPress Theme bundles the following third-party resources:

HTML5 Shiv v3.7.3, Copyright 2014 Alexander Farkas
Licenses: MIT/GPL2
Source: https://github.com/aFarkas/html5shiv

Bootstrap v3.3.7, Copyright 2011-2015 Twitter, Inc.
License: MIT
Source: http://getbootstrap.com

Font Awesome 4.7.0, By @davegandy
Source: http://fontawesome.io

  Font License
    Applies to all desktop and webfont files in the following directory: fonts/
    License: SIL OFL 1.1
    URL: http://scripts.sil.org/OFL
        
  Code License
    Applies to all CSS and LESS files in the following directories: css/font-awesome.css
    License: MIT License
    URL: http://opensource.org/licenses/mit-license.html
        
wp_bootstrap_navwalker, By Edward McIntyre - @twittem
License: GPL-2.0+
Source: https://github.com/twittem/wp-bootstrap-navwalker


The .TUBE WordPress Theme uses the following images:

8-bit Loading Icon (tube-loader.gif), by Daniel Crocker (danielcrocker.co.uk)
License: Creative Commons BY-SA 4.0
License URL: https://creativecommons.org/licenses/by-sa/4.0/
Source: Based on original by David Ope http://dvdp.tumblr.com/image/115068384728

Diagonal Stripes Image (diagonals-10.png), by Todd Levy
License: Creative Commons BY-SA 4.0
License URL: https://creativecommons.org/licenses/by-sa/4.0/
Source: Original image

Wave (screenshot.png), by Jeremy Bishop
Image URL: https://unsplash.com/photos/FsFGF-ATwKw
License: CC0 1.0 Universal (CC0 1.0)
License URL: https://creativecommons.org/publicdomain/zero/1.0/

Motorcycle (screenshot.png), by Seabass Creatives
Image URL: https://unsplash.com/photos/ibkAn4aPomo
License: CC0 1.0 Universal (CC0 1.0)
License URL: https://creativecommons.org/publicdomain/zero/1.0/

Waterslide (screenshot.png), by Iker Urteaga
Image URL: https://unsplash.com/photos/GIXUMw8wsoc
License: CC0 1.0 Universal (CC0 1.0)
License URL: https://creativecommons.org/publicdomain/zero/1.0/

Skateboarder (screenshot.png), by Jase Daniels
Image URL: https://unsplash.com/photos/dkvoEC3vxwU
License: CC0 1.0 Universal (CC0 1.0)
License URL: https://creativecommons.org/licenses/by/2.0/


== Notes ==

For more information about .TUBE or to buy a .TUBE domain please visit [get.TUBE](https://www.get.tube) today.

To add images to category, tag, and term pages, please use the [WP Term Images](https://wordpress.org/plugins/wp-term-images/) plugin.

To add background videos to static pages and posts, please use the [Video Background](https://wordpress.org/plugins/video-background/) plugin.

To add social footer links, please use the [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) plugin and add your social profile URLs.


== Changelog ==    
                 
= 1.1.5 =
*Released: June 2, 2017*

Bug Fixes

* Remove print_tl() call from class-tube-frontend-menus.php
                 
= 1.1.4 =
*Released: May 31, 2017*

Enhancements

* Added 'in_same_term', 'excluded_terms', and 'taxonomy' capability to get_prevnext_post_links in pagination class, filterable via 'tube_filter_prevnext_post_links_args' ([H/T andrew666](https://wordpress.org/support/topic/single-post-previousnext-links-by-category/#post-9046408))
* New grid-124 classes for use in child themes
* New support for 'tube_masthead_columns' and 'tube_content_columns' custom fields for masthead and content columns for page.php and single.php (e.g. "col-xs-12 col-lg-6 col-lg-push-3")
* Filterable content content columns for index.php, page.php, and single.php, allows you to change the boostrap columns for the page content (e.g. "col-xs-12 col-lg-6 col-lg-push-3")
* New 'tube_show_excerpt_on_page' filter for page.php, allows you to show / hide the excerpt in the page masthead (i.e. return false to hide the excerpt)
* Improved CSS to prevent spacing above :first-child pagination wrap

Bug Fixes

* Hide glory-shot wrap when no image or video to prevent "overlay" from covering page
* Added echo argument to custom_wp_link_pages_args in pagination class to prevent 'Undefined index: echo' notice

Deprecations

* Removed 'tube_filter_link_pages_args', can use stardard 'wp_link_pages_args' instead
* Removed 'comment_form_default_fields' filter, now simply passing fields into comment_form() but can still use same filter since it's built into native function         
                 
= 1.1.3 =
*Released: February 26, 2017*

Enhancements

* Changed sidebars to regigster bottom sidebar first

Best Practices

* Updated screenshot to use creative commons license


= 1.1.2 =
*Released: February 18, 2017*

Enhancements

* New action hooks for 'tube_after_comments', 'tube_after_post_comments' and 'tube_after_page_comments'
* Added comments_template() to page.php
* Added support for show_option_none in show_terms_list
* New attachment.php template
* Now using native WP Custom Header image and video

Bug Fixes

* Fixed inconsistent line endings in classes/class-tube-colors.php

Deprecations

* Changed minimum required version to WP 4.7
* Removed custom textarea control in place of native textarea
* Removed xs image size
* Removed home image and video (use WP Custom Header instead)

Best Practices

* Removed custom textarea control in place of native textarea
* Removed all uses of global post
* Additional url escaping in comments class and header
* Add non-minified scripts for Boostrap and Font Awesome
* Removed unused typeahead code
* Improved sanitization of nonce / setteings in terms list template metabox

Licensing

* Updated license to GPL 3+
* Updated some theme image licenses to Creative Commons BY-SA 4.0


= 1.1.1.1 =
*Released: February 7, 2017*

Bug Fixes

* Fixed a translastion function

= 1.1.1 =
*Released: February 7, 2017*

Enhancements

* Support for tranlations
* Mexico / Spanish translation (thanks Oshua Moreno)
* Many escape functions updated with 'context' for translators
* Support for "none" as gloryshot position for Featured Image
* Improved display for "More Like This" to look like tags
* Improved salience for Prev / Next post link on single post
* Only show Comment Count link in post meta bar on single post if comments open or has comments
* Theme CSS now versioned based on last modified date
* Revised featured image / glory shot and page title area for improved presentation

Bug Fixes

* Update sticky posts query to properly return most recent

Deprecations

* Removed "Custom CSS" feature, legacy CSS will auto-migrate to native "Additional CSS" (requires WP 4.7+)


= 1.1.0.1 =
*Released: November 19, 2016*

Bug Fixes

* Fixes for missing translations strings 


= 1.1.0 =
*Released: November 19, 2016*

Enhancements

* Updated .excerpt CSS wrapper for homepage excerpt
* New action hooks...
** 'tube_home_masthead_content' for homepage masthead content
** 'tube_before_page_content' and 'tube_after_page_content' action hooks
** 'tube_after_wp_footer' action in footer.php
** and a few more
* New filters, including...
** 'tube_filter_home_headline' and 'tube_filter_home_excerpt'
** 'tube_filter_google_fonts_subsets' filter allows control of Google font subsets 
** 'tube_filter_paginated_query_label_raw' and 'tube_filter_paginated_query_label' filters
** 'tube_filter_post_meta_args' to sculpt the post meta display
** and many more
* New theme labels in customizer, plus filters for all labels
** all_posts_button_text
** search_results_header_label
** no_results_heading
** search_no_results_message
* Updated Libraries...
** Update to Bootstrap 3.3.7
** Update to Font Awesome 4.7.0
* Simplified excert logic on page and template-terms-list

Deprecations

* Removed "get_post_thumbnail_image_data" function, no longer used
* Removed "get_image_data" function, no longer used
* Removed "boostrap-xl" css classes and enque, no longer used
* Removed checks for dynamic_sidebar in sidebar templates (to ensure dynamic_sidebar_before / after fires)

Theme Review Revisions

* Added textdomain to esc_attr_x in searchform.php
* Converted all line endings to UNIX in class-tube-theatre-video.php
* Removed is_tl, print_tl, and exit_tl functions
* Removed class-tube-admin-menus.php 
** Editor link no longer removed from appearance menu
** Deep customizer links removed from appearance menu
* Removed unused rename_static_front_page_section function from class-tube-customizer.php
* Add "Tube" namespace to following libraries...
** Textarea Control (class-tube-otto-customize-control-textarea.php)
** Canvas Video Player (/lib/tube-html-canvas-video-player)
** Bootstrap Nav Walker (/lib/class-tube-bootstrap-nav-walker.php)
* Removed Ad Units and associated JT_Customize_Control_Checkbox_Multiple library
* Removed filter to support widgets in shortcodes
* Removed wp_update_nav_menu_item that automatically added "Home" to the menu   
* Removed mov format support via 'tube_support_mov_format' filter on 'wp_video_extensions'
* Removed class-tube-typeahead.php
** Typeahead no longer supported in header nav
* Updated theme license to GPL v3
* Declared license for HTML5Shiv
* Declared licesnse for diagonals.png image in images folder
* Revised screenshot.png and declared license for images
* Remove all "multiple commented" and otherwise unused code
* Removed wp_is_mobile check from add_home_background_video_to_page
* Enque scripts / styles without registering them
* Using get_stylesheet_uri to enque main theme CSS
* Remove all .map, sass, less, git etc development files from 3rd party libs
* URL encoding for google fonts URL, plus support for subsets
* Added / improved escaping in...
** 'social_links' function in class-tube-footer.php
** 'draw_list_menu' function in class-tube-frontend-menus.php
** 'add_gloryshot_to_page', 'add_gloryhsot_position_to_featured_image_metabox' functions in class-tube-images.php
** 'add_background_video_to_masthead' function in class-tube-images.php
** 'add_label_above_post_list_title' function in class-tube-labels.php
** 'prevnext_post_links' function in class-tube-pagination.php
** 'get_post_meta_output' function in class-tube-post-meta.php
** 'post_terms_links_module' function in class-tube-taxonomy.php
** 'tube_insert_video_into_masthead' function in class-tube-theatre-video.php
** many more
** various spots in terms-list.php
** various spots in header.php
** various spots in single.php
** various spots in index.php
* Simplified .TUBE footer link to be basic credit link, no utm_params, default is ON
** Changes made per private chat with reviewer "acosmin"
* Using 'wp_add_inline_script' for background video
* Fix unstranslated text strings in class-tube-images.php
* Remove various commented code, unused codeblocks
* Updated prev/next pagination functions to use get_posts_nav_link
* Updated numeric pagination functions to use get_the_posts_pagination
* Remove HTML5 theme_support for search-form and comment-list


= 1.0.8 =
*Released: November 10, 2016*

Enhancements

* Change theme name from .TUBE to TUBE per WP request


= 1.0.7 =
*Released: November 9, 2016*

Enhancements

* New "tube_filter_copyright" to filter the copyright text
* Apply wpautop and wptexturize filters to copyright text
* Support for "fancy" page title when using theatre mode with .TUBE Video Curator and no featured image
* Update get_video_embed to use local cache for reuse on a single pageload
* Ability to filter post types that get labels above title in post lists
* New "top" sidebar for above content
* Support for descriptions on Single Tag, Single Custom Taxononmy Term, and Author archives 

Bug Fixes

* Fix for homepage bg video overlap issue on Firefox, IE, etc
* Fix for homepage bg video offset issue on Safari, IE, etc
* Modified conditional logic for index.php masthead to else/if to prevent duplicate headers
* CSS fix to ensure responsive images with caption

Deprecations

* The "main-sidebar" is no longer available, it has been replaced with "bottom"


= 1.0.6 =
*Released: August 26, 2016*

General

* Tested up to WP 4.6

Bug Fixes

* Fix for issue when no taxonomy selected for label above title in post lists


= 1.0.5 =
*Released: August 21, 2016*

Enhancements

* Support for Home Page background video (mp4) in Customizer
* Support for "[Video Background](https://wordpress.org/plugins/video-background/)" plugin on pages / posts
* New "Theatre (Above Title)" placement option for users of [.TUBE Video Curator Plugin](https://www.get.tube/wordpress/tube-video-curator-plugin)
* Dedicated Customizer page for "Labels & Text Strings"
 * Ability to customize search placeholder
 * Ability to customize pagination label
 * Ability to select taxonomy for label above title in post lists
* New Eclectic Eggplant color scheme
* Misc CSS updates (e.g. em based paragraph margins)
* Allow shortcodes in widgets
* New hooks in single.php for "tube_before_post_content", "tube_before_masthead_content" and "tube_after_masthead_content"
* Need hooks in post list partial for "tube_post_list_before_title", "tube_post_list_after_title", "tube_post_list_after_meta", and "tube_post_list_after_excerpt"
* Update index.php to support custom post type archives
* Allow <style> tags in ad units

Bug Fixes

* Fix for ads units not appearing on single category pages


= 1.0.4 =
*Released: July 17, 2016*

Bug Fixes

* Prevent % in term slugs from throwing sprintf error on single post

Enhancements

* Ensure tube_footer_menu nav only shows 1 level depth
* Updated CSS for tag cloud


= 1.0.3 =
*Released: July 7, 2016*

* Fix botched version number


= 1.0.2 =
*Released: July 7, 2016*

Enhancements

* Update all get.TUBE URLs to use https
* Add utm_medium param to Get Your .TUBE Domain footer button URL


= 1.0.1 =
*Released: July 1, 2016*

Enhancements

* Extra check for current user can activate plugins on plugin check
* No longer renaming "Static Front Page" section
* Modification and cleanup to Plugin Check classes, use is_plugin_active rather than global variable
* Updates theatre video placement to use new tube_vc options prefix due to plugin changes
* Update footer text color on Nolan Cyan
* Minor revisions to theme description

Bug Fixes

* Move "Home" settings from static_front_page to new Home / Latest Posts section due to issue when blog has NO pages, also changed labels, revised admin menu to reflect change

Removed

* Reseller ID setting from Footer Customizer

= 1.0.0.0 =
*Released: June 14, 2016*

* Initial release

