=== Slick Slideshow ===
Contributors: blackbam
Donate link: http://www.blackbam.at/blackbams-blog/slick-slideshow/
Tags: slideshow, jquery, images, html
Requires at least: 3.0
Tested up to: 3.1.2
License: GPLv2
Stable tag: trunk

Slick Slideshow is a highly customizable, but easy-to-use JQuery Slideshow Plugin, to show dynamic images or contents on your website.

== Description ==

Slick Slideshow is a highly customizable, but easy-to-use JQuery Slideshow Plugin, to show dynamic images or contents on your website.
Visit the <a href="http://www.blackbam.at/blackbams-blog/slick-slideshow">Plugin Page</a> to see it in action.

Inspired by a tutorial from <a href="http://sixrevisions.com/tutorials/javascript_tutorial/create-a-slick-and-accessible-slideshow-using-jquery/">sixrevisions.com</a>, we adapted the
extended work from <a href="http://blog.monnet-usa.com/?p=276">Blog Monnet USA</a>. Our part was finally to create a WordPress-Plugin from that.
	

Features:
	
- All Styles, Animation Parameters and Contents are customizable easily over the backend
- Can use Image URLs (unexperienced users) or custom HTML (experienced Users)
- Valid XHTML 1.0 Strict, Valid CSS 2
- Compatibility to other JQuery WordPress Plugins (you can use a Lightbox like Colorbox for example for images in the Slideshow)
- Fast and efficient
- Does not require extra-tables in your database (actually this might be required one day, but currently everything works fine)
- GPLv2 licensed as it is a requirement of the WordPress Codex (donations for the efforts and further development are fair and welcome)
- Languages: English, German
	

== Installation ==

1. Upload the "slick-slideshow" directory in wp-content/plugins/ or use the the WordPress auto-installer for plugins. 

2. Activate the Plugin through the Plugins page.

3. Embed the slick slideshow by either pasting the shorttag

	[slick_slideshow]
	
	into the content of a post or page or just call
	
	<?php slick_slideshow(); ?>
	
	from any position inside your theme.

4.Go to Settings->Slick Slideshow to style and customize your Slideshow in any way!

	- Fill
	- Add and delete as much slides as you want
	- Edit Animation Options and deactivate the JQuery embedding, if you do not need it
	- Edit the Style Options or create your completly customized stylesheet from the prepared CSS code
	
	
== Known issues ==

- The Plugin is not tested in combination with the use of other Javascript-Frameworks than jQuery (like Prototype). There might obviously be compability issues.


== Changelog ==

= 1.1.2 =

Initial Release for the Plugin directory.

