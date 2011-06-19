<?php
/*
Plugin Name: Slick Slideshow
Version: 0.1.2
Description:  A slick and fancy JQUery slider for your content (images, html, and so on). Supports Auto Slide, manual handling, Stop&Play-Button and much more.
Plugin URI: http://blog.blackbam.at/
Author: David Stöckl
Author URI: http://www.blackbam.at/
*/

/*

	Copyright (c) David Stöckl <david.stoeckl@blackbam.at>
	
	Released and distributed under the GPL, according to the WordPress Codex.
	
	This Plugin was developed by using Source Codes from sixrevisions.com and blog.monnet-usa.com.
	
	http://sixrevisions.com/tutorials/javascript_tutorial/create-a-slick-and-accessible-slideshow-using-jquery/
	http://blog.monnet-usa.com/?p=276
	
	Last Update: 2011-05-04
	
	readme
	veröffentlichen: Make nice description, Make smooth example, make smooth blog page

	

*/

// Localization support
function handle_load_domain() {
	
	
	$plugin_domain = 'slick_slideshow';
	
	// get current language
	$locale = get_locale();
	
	// locate translation file
	$mofile = WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__)).'/languages/'.$plugin_domain.'-'.$locale.'.mo';
	
	// load translation
	load_textdomain($plugin_domain,$mofile);
}

// when settings are updated in the admin edit screen
$success= "";

if(isset($_POST['slick_slideshow_update']) && $_POST['slick_slideshow_update'] == "yes") {
        update_option('slick_slideshow_css',$_POST['slick_slideshow_css']);
        update_option('slick_slideshow_width',$_POST['slick_slideshow_width']);
        update_option('slick_slideshow_height',$_POST['slick_slideshow_height']);
        update_option('slick_slideshow_background',$_POST['slick_slideshow_background']);
        update_option('slick_slideshow_color',$_POST['slick_slideshow_color']);
        update_option('slick_slideshow_previous', $_POST['slick_slideshow_previous']);
        update_option('slick_slideshow_next', $_POST['slick_slideshow_next']);
        update_option('slick_slideshow_transition_time', $_POST['slick_slideshow_transition_time']);
        update_option('slick_slideshow_viewing_time', $_POST['slick_slideshow_viewing_time']);
        update_option('slick_slideshow_embed_jquery', $_POST['slick_slideshow_embed_jquery']);
		update_option('slick_slideshow_autostart_slideshow',$_POST['slick_slideshow_autostart_slideshow']);
        update_option('slick_slideshow_display_slideshow_control_panel',$_POST['slick_slideshow_display_slideshow_control_panel']);
        update_option('slick_slideshow_rewind_slideshow',$_POST['slick_slideshow_rewind_slideshow']);
        
        // Update Contents
        $all = $_POST['slick_slideshow_tabs_number'];
        $mycontents="";
        
        // Wordpress sometimes tries to escape the HTML when saving something to wp-options
        for($i=1;$i<=$all;$i++) {
        	$slicky = "slicky_".$i;
        	$slickytype = "slide_type_".$i;
        	if(isset($_POST[$slicky]) && $_POST[$slicky] != "") {
        		$slickycontents.=$_POST[$slicky]."<!--SLICKYTYPE-->".atf_slashit($_POST[$slickytype])."<!--NEXTSLICKYTAB-->";
        	}
        }
        
        update_option('slick_slideshow_content', $slickycontents);
}

// add slashes to html if magic quotes is not on
function atf_slashit($stringvar){
    if (!get_magic_quotes_gpc()){
        $stringvar = addslashes($stringvar);
    }
    return $stringvar;
}
// remove slashes if magic quotes is on
function atf_deslashit($stringvar){
    if (1 == get_magic_quotes_gpc()){
        $stringvar = stripslashes($stringvar);
    }
    return $stringvar;
}

// Returns the HTML code for the slideshow
function slick_slideshow() { 
	
	$slideshow="";
	
	$slideshow .='
<div id="pageContainer">
  <!-- Slideshow HTML -->
  <div id="slideshow" style="display:none;">
    <div id="slidesContainer">';
     foreach(explode('<!--NEXTSLICKYTAB-->',get_option('slick_slideshow_content')) as $slicky) {
     			if($slicky!="") {
     				$slickycontents = explode('<!--SLICKYTYPE-->',$slicky);
			     	$slideshow .='
			     	<div class="slide">';
			     		 
			     		$actual_content = atf_deslashit($slickycontents[0]);
			     		
			     		if($slickycontents[1]=="image") {
			     			$slideshow.='<img src="'.$actual_content.'" alt="" />';
			     		} else if($slickycontents[1]=="HTML") {
			     			$slideshow .= $actual_content;
			     		} 
			     	$slideshow.='</div><!-- slide div -->';
     
     			}	
     		}
   $slideshow.='
    </div>
  </div>
  <!-- Slideshow HTML -->
</div>';
   
   return $slideshow;
} 

// returns the javascript for the slideshow
function slick_slideshow_javascript() { 
	
	// Maybe JQuery is already embedded, so there is a admin deactivate
	if(get_option('slick_slideshow_embed_jquery')=='true') { ?>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
	<?php } ?>
	<script type="text/javascript">
	/* <![CDATA[ */
	// Enhancements
	var slideshow_start_mode  = false;
    var autostart_slideshow = <?php echo get_option('slick_slideshow_autostart_slideshow'); ?>;
    var rewind_slideshow = <?php echo get_option('slick_slideshow_rewind_slideshow'); ?>;
    var display_slideshow_control_panel = <?php echo get_option('slick_slideshow_display_slideshow_control_panel'); ?>;
	 
    var slide_transition_time = <?php echo get_option('slick_slideshow_transition_time'); ?>;
    var slide_viewing_time = <?php echo get_option('slick_slideshow_viewing_time'); ?>;
       
    var slideshow_control_icons = new Array();  
	// Note: you can download the .png locally and edit the urls below to reflect your path
    slideshow_control_icons['play']  	= '<?php bloginfo('url'); ?>/wp-content/plugins/slick-slideshow/images/Control_play.png'; 
    slideshow_control_icons['pause'] 	= '<?php bloginfo('url'); ?>/wp-content/plugins/slick-slideshow/images/Control_pause.png';    
    
	// Enhancements
	function start_slideshow() {  
	   slideshow_start_mode = true;  
	   interval = setInterval(show_next_slide, slide_viewing_time );  
	}  
	 
	function show_next_slide(){  
	   $('#rightControl').click();  
	}  
	 
	function pause_slideshow() {  
	   slideshow_start_mode = false;  
		   clearInterval(interval);  
	} 	
	
	function handle_control_panel_click()
	{
		if(slideshow_start_mode == true)
		{  
			$(this).attr('src',slideshow_control_icons['play']);  
			pause_slideshow();  
		}
		else
		{  
			$(this).attr('src',slideshow_control_icons['pause']);  
			start_slideshow();  
		}  
	}
	
	function setup_control_panel() 
	{
		$('#slidesContainer').prepend('<img id="slideshow_control_panel" src="" alt="Navigation diaporama" />');  
		var control_panel = $('#slideshow_control_panel');

		if(autostart_slideshow == true) {
			control_panel.attr('src',slideshow_control_icons['pause']);  
		} else {
			control_panel.attr('src',slideshow_control_icons['play']);    
		}
			
		control_panel.bind('click', handle_control_panel_click);
	}
	
$(document).ready(function(){
  var currentPosition = 0;
  var slideWidth = <?php echo get_option('slick_slideshow_width'); ?>;

  var slides = $('.slide');
  var numberOfSlides = slides.length;
  var interval;  

	
  // Remove scrollbar in JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Wrap all .slides with #slideInner div
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Set #slideInner width equal to total width of all slides
  $('#slideInner').css('width', slideWidth * numberOfSlides);

  // Insert controls in the DOM
  $('#slideshow')
    .prepend('<span class="control" id="leftControl">Clicking moves left</span>')
    .append('<span class="control" id="rightControl">Clicking moves right</span>');

  // Hide left arrow control on first load
  manageControls(currentPosition,numberOfSlides);

	// Conditionally setup the control panel if needed
    if(display_slideshow_control_panel == true)
		setup_control_panel();

  // Create event listeners for .controls clicks
  $('.control')
    .bind('click', function(){
    // Determine new position
	currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
    
		// If auto rewind is off pause the slideshow 
		// when on the last slide
		if(currentPosition == numberOfSlides && rewind_slideshow == false )
		{  
             currentPosition--;  
             pause_slideshow();  
        } 
	
	// Hide / show controls
    manageControls(currentPosition);
	
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    },slide_transition_time);
	
  });

	// If auto start is on kick off the slideshow
	if(autostart_slideshow == true){  
		start_slideshow();  
	}
	
	try {
		document.getElementById("slick_slideshow").style.display="block";
	} catch(e) {
		// ID not found or something
	}

    // manageControls: Hides and Shows controls depending on currentPosition
  function manageControls(position){
    // Hide left arrow if position is first slide
	if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }

		// Hide right arrow if position is last slide
		// and if auto rewind is not on
		if(position==numberOfSlides-1 && rewind_slideshow == false)
		{
			$('#rightControl').hide();
		} else {
			$('#rightControl').show();
		}
		
		// Go back to the first slide if we're on the last slide
		// and if auto rewind is on
		if(position == numberOfSlides && rewind_slideshow == true){
			currentPosition = 0;
			 $('#leftControl').hide();
		}
  }	
	
});
/* ]]> */
	</script>

<?php }

/* This function adds the jQuery for the footer.
It looks better to show the slider, when the page is loaded */
function slick_slideshow_activate() { ?>
	<script type="text/javascript">
	/* <![CDATA[ */
		jQuery(document).ready(function(){
		try {
			$("#slideshow").show();
		} catch(e) {
			// do nothing
		}
	});
	/* ]]> */
	</script>
<?php }

// registers the settings for the slideshow on first activation
function slick_slideshow_init(){
    if(function_exists('register_setting')){
        register_setting('slick_slideshow_options', 'slick_slideshow_width');
        register_setting('slick_slideshow_options', 'slick_slideshow_height');
        register_setting('slick_slideshow_options', 'slick_slideshow_background');
        register_setting('slick_slideshow_options', 'slick_slideshow_color');
        register_setting('slick_slideshow_options', 'slick_slideshow_previous');
        register_setting('slick_slideshow_options', 'slick_slideshow_next');
        register_setting('slick_slideshow_options', 'slick_slideshow_transition_time');
        register_setting('slick_slideshow_options', 'slick_slideshow_viewing_time');
        register_setting('slick_slideshow_options', 'slick_slideshow_embed_jquery');
        register_setting('slick_slideshow_options', 'slick_slideshow_content');
        register_setting('slick_slideshow_options', 'slick_slideshow_css');
        register_setting('slick_slideshow_options', 'slick_slideshow_autostart_slideshow');
        register_setting('slick_slideshow_options', 'slick_slideshow_display_slideshow_control_panel');
        register_setting('slick_slideshow_options', 'slick_slideshow_rewind_slideshow');      
    }
}

// add the option page for the Slideshow to the admin edit screen
function slick_slideshow_options() {
	add_options_page('Slick Slideshow', 'Slick Slideshow', 'administrator', basename(__FILE__), 'slick_slideshow_options_page');
}

// the whole html for the slideshow on the admin edit screen
function slick_slideshow_options_page() { 
	// internationalize the admin page
	handle_load_domain();
	?>
<div class="metabox-holder has-right-sidebar" id="poststuff">

	<div class="inner-sidebar">
		<div style="position: absolute;" class="meta-box-sortabless ui-sortable" id="side-sortables">
			<div class="postbox" id="sm_pnres" style="margin-top:135px;">
				<h3 class="hndle"><span><?php _e('About this Plugin:','slick_slideshow'); ?></span></h3>
				<div class="inside" style="padding:10px;">
					<p><a href="http://www.blackbam.at/blackbams-blog/slick-slideshow/"><?php _e('Plugin Homepage','slick_slideshow'); ?></a></p>
					<p><a href="http://www.blackbam.at/blackbams-blog/slick-slideshow/"><?php _e('Report a Bug','slick_slideshow'); ?></a></p>
					<p><a href="http://www.blackbam.at/blackbams-blog/slick-slideshow/"><?php _e('Suggest a Feature','slick_slideshow'); ?></a></p>
					<p><a href="http://www.blackbam.at/blackbams-blog/"><?php _e('Author Homepage','slick_slideshow'); ?></a></p>
					<p>&nbsp;</p>
					<p><?php _e('Donations are fair and welcome!','slick_slideshow'); ?></p>
					<p style="text-align:center;">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="4VQ5UKRSJAUAW">
						<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
						<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/de_DE/i/scr/pixel.gif" width="1" height="1">
						</form>
					</p>
				</div>
			</div><!--  slick_slideshow_donate div -->
		</div><!--  side-sortables div -->
	</div><!--  inner-sidebar div -->

	<div class="has-sidebar sm-padded">
		<div class="has-sidebar-content" id="post-body-content">
		<div class="wrap">
		    <div class="icon32" id="icon-options-general"></div><h2>Slick Slideshow Settings</h2>
		    <?php if(isset($_POST['slick_slideshow_update'])) { ?> <div class="updated"><?php _e('The Options have been updated successfully.','slick_slideshow') ?></div><?php } ?>
		    
			<p><?php _e('This Plugin is for the administration and styling of the Slick Slideshow.','slick_slideshow'); ?></p>
			<div class="postbox">
			
			<form method="post">
			<table class="form-table">
				<tr>
					<th><h4><?php _e('Slideshow Contents','slick_slideshow'); ?></h4></th>
				</tr>
				<?php 
				$count = 1;
				foreach(explode('<!--NEXTSLICKYTAB-->',get_option('slick_slideshow_content')) as $slicky) { 
					if($slicky != "") {
						$slickycontents = explode('<!--SLICKYTYPE-->',$slicky);
					?>
					<tr id="slicky_slide_holder_<?php echo $count; ?>">
						<td>
							<p>Slide <?php echo $count; ?></p>
							<p>&nbsp;</p>
							<p>
								<select name="slide_type_<?php echo $count; ?>">
									<option value="image" <?php if($slickycontents[1] != "HTML") { echo 'selected="selected"'; }?>><?php _e('Image URL','slick_slideshow'); ?></option>
									<option value="HTML" <?php if($slickycontents[1] == "HTML") { echo 'selected="selected"'; }?>><?php _e('Custom HTML','slick_slideshow'); ?></option>
								</select>
							</p>
						</td>
						<td><textarea style="width:100%;" rows="6" id="slicky_<?php echo $count; ?>" name="slicky_<?php echo $count; ?>"><?php echo atf_deslashit($slickycontents[0]); ?></textarea></td>
						<td><p><a style="cursor:pointer;" onclick="document.getElementById('slicky_delete_<?php echo $count; ?>').style.display='block';"><?php _e('Delete this slide','slick_slideshow'); ?></a></p>
							<p id="slicky_delete_<?php echo $count; ?>" style="display:none;">Are you sure?<br/>
							<a style="cursor:pointer;" onclick="document.getElementById('slicky_<?php echo $count; ?>').innerHTML=''; document.getElementById('slicky_slide_holder_<?php echo $count; ?>').style.display='none';"><?php _e('Yes','slick_slideshow'); ?></a>&nbsp;&nbsp;&nbsp;
							<a style="cursor:pointer;" onclick="document.getElementById('slicky_delete_<?php echo $count; ?>').style.display='none';"><?php _e('No','slick_slideshow'); ?></a></p>
						</td>
					</tr>
				<?php 
					$count++;
					}	
				}?>
				
				<tr id="new_slides">
					<td>
						<input type="hidden" name="slick_slideshow_tabs_number" id="slick_slideshow_tabs_number" value="<?php echo $count; ?>" />
						<a style="cursor:pointer; font-size:16px; font-weight:bold;" onclick="addNewSlide();">Add Slide</a>
						<script type="text/javascript">
						/* <![CDATA[ */
							function addNewSlide() {
								var slideNumber = parseInt(document.getElementById("slick_slideshow_tabs_number").value);
								var child = '<td>Slide '+slideNumber+'</td><td><textarea rows="5" cols="100" name="slicky_'+slideNumber+'"></textarea></td>';
								
									var newElement = document.createElement("tr");
									newElement.setAttribute("id","slicky_slide_holder_"+slideNumber);

									newElement.appendChild(document.createElement("td"));
									newElement.appendChild(document.createElement("td"));

									newElement.firstChild.innerHTML="<p>Slide "+slideNumber+"</p><p>&nbsp</p><p><select name='slide_type_"+slideNumber+"'><option value='image'><?php _e('Image URL','slick_slideshow'); ?></option><option value='image'><?php _e('Custom HTML','slick_slideshow'); ?></option></select></p>";
									
									var textar = document.createElement("textarea");
									textar.setAttribute("rows","6");
									textar.setAttribute("style","width:100%;");
									textar.setAttribute("name","slicky_"+slideNumber);

									newElement.lastChild.appendChild(textar);
									
									newElement.appendChild(document.createElement("td"));

									var beforenode=document.getElementById("new_slides");

									beforenode.parentNode.insertBefore(newElement,beforenode);
									
									document.getElementById("slick_slideshow_tabs_number").value=slideNumber+1;
							}
							/* ]]> */
						</script>
					</td>
				</tr>
			
				<tr>
					<th><h4><?php _e('General Settings','slick_slideshow'); ?></h4></th>
				</tr>
				<tr>
					<th><?php _e('Embed JQuery?','slick_slideshow'); ?></th>
					<td><select name="slick_slideshow_embed_jquery">
							<option <?php if (get_option('slick_slideshow_embed_jquery') == 'true') echo 'selected="selected"'; ?>>true</option>
							<option <?php if (get_option('slick_slideshow_embed_jquery') == 'false') echo 'selected="selected"'; ?>>false</option>
						</select>
						<span class="description"><?php _e('Only set this to false, if you are sure, that JQuery is already embedded by you or another Plugin!','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Transition Time of Slide Animation in Milliseconds','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_transition_time'); ?>" name="slick_slideshow_transition_time" id="slick_slideshow_transition_time" />
					<span class="description"><?php _e('The time for a transition in the animation.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Viewing Time of Slide Animation in Milliseconds','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_viewing_time'); ?>" name="slick_slideshow_viewing_time" id="slick_slideshow_viewing_time" />
					<span class="description"><?php _e('The time for a slide not to move.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Autostart','slick_slideshow'); ?></th>
					<td><select name="slick_slideshow_autostart_slideshow">
							<option <?php if (get_option('slick_slideshow_autostart_slideshow') == 'true') echo 'selected="selected"'; ?>>true</option>
							<option <?php if (get_option('slick_slideshow_autostart_slideshow') == 'false') echo 'selected="selected"'; ?>>false</option>
						</select>
						<span class="description"><?php _e('If true, the slideshow will automatically start once the page is loaded.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Slideshow Control Panel','slick_slideshow'); ?></th>
					<td><select name="slick_slideshow_display_slideshow_control_panel">
							<option <?php if (get_option('slick_slideshow_display_slideshow_control_panel') == 'true') echo 'selected="selected"'; ?>>true</option>
							<option <?php if (get_option('slick_slideshow_display_slideshow_control_panel') == 'false') echo 'selected="selected"'; ?>>false</option>
						</select>
						<span class="description"><?php _e('Wether to display the slideshow control panel or not.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Rewind Slideshow?','slick_slideshow'); ?></th>
					<td><select name="slick_slideshow_rewind_slideshow">
							<option <?php if (get_option('slick_slideshow_rewind_slideshow') == 'true') echo 'selected="selected"'; ?>>true</option>
							<option <?php if (get_option('slick_slideshow_rewind_slideshow') == 'false') echo 'selected="selected"'; ?>>false</option>
						</select>
						<span class="description"><?php _e('If true, the slideshow will automatically rewind by going back to the first slide after the last slide was displayed.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><h4><?php _e('Style Options','slick_slideshow'); ?></h4></th>
				</tr>
				<tr>
					<th><?php _e('Use custom styles?','slick_slideshow'); ?></th>
					<td><select name="slick_slideshow_css">
							<option <?php if (get_option('slick_slideshow_css') == 'auto') echo 'selected="selected"'; ?>>auto</option>
							<option <?php if (get_option('slick_slideshow_css') == 'custom') echo 'selected="selected"'; ?>>custom</option>
						</select>
						<span class="description"><?php _e('If you are not happy with the style options of the backend, you can use custom css.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Width','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_width'); ?>" name="slick_slideshow_width" id="slick_slideshow_width" />
					<span class="description"><?php _e('The width of your Slider in pixels.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Height','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_height'); ?>" name="slick_slideshow_height" id="slick_slideshow_height" />
					<span class="description"><?php _e('The height of your Slider in pixels.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Background Color','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_background'); ?>" name="slick_slideshow_background" id="slick_slideshow_background" />
					<span class="description"><?php _e('The background color of your slider. Hexadecimal value with hash key (like #333) or none.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Font Color','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_color'); ?>" name="slick_slideshow_color" id="slick_slideshow_color" />
					<span class="description"><?php _e('The font color of your Slider. Hexadecimal value.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Previous Button URL','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_previous'); ?>" name="slick_slideshow_previous" id="slick_slideshow_previous" />
					<span class="description"><?php _e('The image URL for the previous button.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php _e('Next Button URL','slick_slideshow'); ?></th>
					<td><input type="text" value="<?php echo get_option('slick_slideshow_next'); ?>" name="slick_slideshow_next" id="slick_slideshow_next" />
					<span class="description"><?php _e('The image URL for the next button.','slick_slideshow'); ?></span>
					</td>
				</tr>
				<tr>
					<td>
					<input type="hidden" name="slick_slideshow_update" value="yes" /><input type="submit" value="Save Settings" style="cursor:pointer;" /></td>
				</tr>
			</table>
			</div><!-- postbox div -->
			</form><!-- options form -->
			
			<?php if(get_option('slick_slideshow_css')=='custom') {?>
			<div class="postbox">
				<p><span style="color:#990000;">&nbsp;&nbsp;Note: </span><?php _e('Changes in your Slick Slideshow Options might require changes in the CSS.','slick_slideshow'); ?><br/><br/>
				&nbsp;&nbsp;<?php _e('Insert these lines of code into your stylesheet and modify carefully:','slick_slideshow'); ?></p>
					<pre style="padding:10px;">
					<?php get_slick_slideshow_css(); ?>
					</pre>
			</div>
			<?php } ?>
		    
		    </div><!-- wrap div -->
		 </div><!-- post-body-content div -->
	  </div><!--  has_sidebar div -->
    
</div><!--  has-right-sidebar div -->
<?php }

// Ouputs the required css for the slideshow
function get_slick_slideshow_css() { ?>

/***** Slick Slideshow ******/

#slideshow {
	margin:0 auto;
	width:<?php echo get_option('slick_slideshow_width'); ?>px;
	height:<?php echo get_option('slick_slideshow_height'); ?>px;
	position:relative;
}

#slideshow #slidesContainer {
	margin:0 auto;
	width:<?php echo get_option('slick_slideshow_width'); ?>px;
	height:<?php get_option('slick_slideshow_height'); ?>px;
	overflow:auto; /* allow scrollbar */
	position:relative;
	background-color:<?php echo get_option('slick_slideshow_background'); ?>;
}

#slideshow #slidesContainer .slide {
	margin:0 auto;
	width:<?php echo (get_option('slick_slideshow_width')-20); ?>px; /* reduce by 20 pixels of #slidesContainer to avoid horizontal scroll */
	height:<?php echo get_option('slick_slideshow_height'); ?>px;
}

/* Slick Slideshow controls */
 
.control {
	display:block;
	width:39px;
	height:<?php echo get_option('slick_slideshow_height'); ?>px;
	text-indent:-10000px;
	position:absolute;
	cursor: pointer;
}

#leftControl {
	top:0;
	left:0;
	background:<?php if(get_option('slick_slideshow_previous')!= ""){?>url("<?php echo get_option('slick_slideshow_previous'); ?>") right center no-repeat<?php } else { echo "none"; } ?>;
	margin-left:-40px;
	height:<?php echo get_option('slick_slideshow_height'); ?>px;
	width:40px;
}

#rightControl {
	top:0;
	right:0;
	background:<?php if(get_option('slick_slideshow_next')!= ""){?>url("<?php echo get_option('slick_slideshow_next'); ?>") left center no-repeat<?php } else { echo "none"; } ?>;
	margin-right:-40px;
	height:<?php echo get_option('slick_slideshow_height'); ?>px;
	width:40px;
}

#slideshow_control_panel{  
	display:block;  
	width:20px;  
	height:20px;  
	position:absolute;  
	right:25px;  
	bottom:10px;  
	cursor:pointer;  
}

div.slide {
	color:#<?php echo(get_option('slick_slideshow_color')); ?>;
}

div.slide a {
	color:#<?php echo(get_option('slick_slideshow_color')); ?>;
}

/***** End Slick Slideshow *****/

<?php }

// adds the css for the slideshow to the theme automatically, if option is set to auto
function slick_slideshow_css_all() {
	if(get_option('slick_slideshow_css') != "" && get_option('slick_slideshow_css') !="custom") { ?>
	<style type="text/css">
		<?php get_slick_slideshow_css(); ?>
	</style>	
<?php 
	}
}

// Adds the initial options to WordPress on first slideshow activation
function slick_slideshow_activation () {
	/* CSS */
	add_option('slick_slideshow_width','643');
	add_option('slick_slideshow_height','258');
	add_option('slick_slideshow_background','none');
	add_option('slick_slideshow_color','000');
	add_option('slick_slideshow_previous',get_bloginfo('url').'/wp-content/plugins/slick-slideshow/images/previous.png');
	add_option('slick_slideshow_next', get_bloginfo('url').'/wp-content/plugins/slick-slideshow/images/next.png');
	add_option('slick_slideshow_css','auto');
	
	/* Javascript */
	add_option('slick_slideshow_embed_jquery','true');
	add_option('slick_slideshow_transition_time','1000');
	add_option('slick_slideshow_viewing_time','6000');
	add_option('slick_slideshow_rewind_slideshow','true');
	add_option('slick_slideshow_autostart_slideshow','true');
	add_option('slick_slideshow_display_slideshow_control_panel','true');
	
	/* content */
	add_option('slick_slideshow_content','<h1>First</h1><!--SLICKYTYPE-->HTML<!--NEXTSLICKYTAB--><h1>Second</h1><!--SLICKYTYPE-->HTML');
}


load_plugin_textdomain('slick_slideshow', 'wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/languages');

// register the admin menus for wordpress
if(is_admin()){
    add_action('admin_menu', 'slick_slideshow_options'); 
    add_action('admin_init', 'slick_slideshow_init');
}


add_action('slick_slideshow','slick_slideshow'); // register the function for use in theme
add_action('wp_head','slick_slideshow_javascript',90); // add script to the header
add_action('wp_head','slick_slideshow_css_all',89); // add css to the aheader
add_action('wp_footer','slick_slideshow_activate'); // add script to the footer

register_activation_hook( __FILE__, 'slick_slideshow_activation');
add_shortcode( 'slick_slideshow', 'slick_slideshow' );

?>