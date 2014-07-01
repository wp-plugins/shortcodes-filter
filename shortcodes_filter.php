<?php
/*
Plugin Name: Shortcodes Filter
Description: Filter certain shortcodes out of posts and pages on mobile or desktop display
Author URI: https://wordpress.org/plugins/wiziapp-create-your-own-native-iphone-app/
Version: 1.0.1
Author: Wiziapp
*/

function shortcodes_filter_my_the_content_filter($content) {
	foreach( shortcodes_filter_get_filters() as $m ) {
		$content = preg_replace('#\[[^]]*?'.$m.'[^]]*?\](?:[^[]*\[[^]]*?/[^]]*?'.$m.'[^]]*?\])?#', '', $content);
	}
	return $content;
}
add_filter( 'the_content', 'shortcodes_filter_my_the_content_filter' );


function shortcodes_filter_your_function_name() {
	global $shortcode_tags;	

	foreach( shortcodes_filter_get_filters() as $m ) {
		
		foreach ($shortcode_tags as $j => $k) {
			// echo '$m: ' . $m . ' $j: ' . $j . ' $k: ' . $k . "<br>";
			if( strpos( $m , $j ) !== -1 ) {
				
				unset( $shortcode_tags[$j] );
			}
			
		}
		
	}
}
add_action( 'pre_get_posts', 'shortcodes_filter_your_function_name' );

function shortcodes_filter_get_filters()
{
	$filters = array();
	$val =  wp_is_mobile()?'Mobile':'Desktop';
	for( $i = 0 ; $i < 10 ; $i++ ) {
		$labdevs_option = get_option('labdevs_text_field_' . $i);
		if( $labdevs_option != '' ) {
			$labdevs_select = get_option('labdevs_select_field_' . $i);
			if($labdevs_select === $val) {
				$filters[] = $labdevs_option;
			}
		}
	}
	return $filters;
}


add_action( 'admin_menu', 'shortcodes_filter_register_my_custom_menu_page' );
function shortcodes_filter_register_my_custom_menu_page(){
    add_menu_page( 'Shortcode Filter', 'Shortcode Filter', 'manage_options', 'shortcode-filter', 'shortcodes_filter_my_custom_menu_page');    
}


add_action( 'wp_ajax_labdevs_settings_update', 'shortcodes_filter_labdevs_settings_update_callback' );
function shortcodes_filter_labdevs_settings_update_callback() {
	$params = array();
	parse_str($_POST['data'], $params);
	foreach( $params as $k => $v ) {
		if (preg_match('!^labdevs_(select|text)_field_[0-9]+$!', $k))
			update_option( $k , $v );
	}	
}

function shortcodes_filter_my_custom_menu_page(){
    ?>
<div class="labdevs_small_success" style="position: fixed; top: 40px; left: 0px; z-index: 100000; width: 100%; padding: 10px; text-align: center; height: auto; display: none;">
    <span style="
    color: white;
    background-color: #80C751;
    padding: 10px 30px;
    border-radius: 9px;
    font-family: monospace;
    line-height: 1em;
">Settings Saved!</span>
  </div>
    <div class="wrap">
		<h2>Shortcode filter</h2>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				jQuery('.labdevs_settings_button_save_changes').click(function() {					
						var key = jQuery(this).attr('id');
						var value = jQuery(this).val();
						$.ajax({			
							type: "POST",
							url: ajaxurl,
							data: { 
								action: 'labdevs_settings_update',
								data : jQuery('.the_settings').serialize()								
							}
						}).done(function( data ) {				
							jQuery('.labdevs_small_success').fadeIn(500);
							setTimeout( function(){ jQuery('.labdevs_small_success').fadeOut(500); } , 5000 );
						});
				});
				
			});
		</script>
		<form class="the_settings">
		<table>
			<thead>
				<tr>
					<th> Shortcodes</th>
					<th> Platform</th>
				</tr>
			</thead>
			<tbody>
			<?php for( $i = 0 ; $i < 10 ; $i++ ) { ?>
			
			<tr>
				<td>
					<input name="labdevs_text_field_<?php echo $i ?>" class="labdevs_text_field" type="text" value="<?php echo get_option('labdevs_text_field_' . $i); ?>">
				</td>
				<td>
					 <span>Filter Shortcodes From: </span>
					<?php $select_field = get_option('labdevs_select_field_' . $i); ?>
					<select style="width:200px;" name="labdevs_select_field_<?php echo $i ?>" class="labdevs_select_field" value="">
						<option <?php if( $select_field == 'Desktop' ) echo 'selected="selected"' ?>>Desktop</option>
						<option <?php if( $select_field == 'Mobile' ) echo 'selected="selected"' ?>>Mobile</option>
					</select>
				</td>
			</tr>			
			
			<?php } ?>
			<tr>
				<td>
					<input type="button" name="submit" class="labdevs_settings_button_save_changes button button-primary" value="Save Changes">
				</td>
				<td>
				
				</td>
			</tr>
			</tbody>
		</table>
		
		</form>
    </div>
    <?php	
}
