<?php
/*
Plugin Name: Shortcodes Filter
Description: Filter certain shortcodes out of posts and pages on mobile or desktop display
Author URI: https://wordpress.org/plugins/wiziapp-create-your-own-native-iphone-app/
Version: 1.0.0
Author: Wiziapp
*/

function my_the_content_filter($content) {
	global $mobile_filters;
	global $desktop_filters;
	if( wp_is_mobile()) {
		
		foreach( $mobile_filters as $m ) {
/*			
if( preg_match('#\[.*?'.$m.'.*?\]#') ) {
	if ( preg_match( REGEX_2 ) ) {
		preg_replace( REGEX_3 , '', $content);
	} else {
		preg_replace('#\[.*?'.$m.'.*?\]#', '', $content);
	}
}
*/		
			//$content = preg_replace('#\[.*?'.$m.'.*?\]#', '', $content);
			$content = preg_replace('#\[[^]]*?'.$m.'[^]]*?\](?:[^[]*\[[^]]*?/[^]]*?'.$m.'[^]]*?\])?#', '', $content);
			
		}
		
		
	} else {
		
		if( $desktop_filters != null ) {
		
			foreach( $desktop_filters as $m ) {
				
				$content = preg_replace('#\[[^]]*?'.$m.'[^]]*?\](?:[^[]*\[[^]]*?/[^]]*?'.$m.'[^]]*?\])?#', '', $content);
				
			}
		}
		
	}
	return $content;
	/*if( wp_is_mobile() ) {  
		return preg_replace('#\[.*?SUB_STRING_TO_BE_SEARCHED.*?\]#', '', $content);
	} else {
		return $content;
	} */ 
}
add_filter( 'the_content', 'my_the_content_filter' );

$mobile_filters;
$desktop_filters;

function your_function_name() {
	global $shortcode_tags;	
	
	global $mobile_filters;
	global $desktop_filters;
	
	for( $i = 0 ; $i < 10 ; $i++ ) {
		$labdevs_option = get_option('labdevs_text_field_' . $i);
		if( $labdevs_option != '' ) {
			$labdevs_select = get_option('labdevs_select_field_' . $i);
			if($labdevs_select == 'Mobile') {
				$mobile_filters[] = $labdevs_option;
			} else {
				$desktop_filters[] = $labdevs_option;
			}
		}
	}
	//var_dump($mobile_filters);
	//var_dump($desktop_filters);
	
	
	if( wp_is_mobile()) {
		
		foreach( $mobile_filters as $m ) {
			
			foreach ($shortcode_tags as $j => $k) {
				//echo '$m: ' . $m . ' $j: ' . $j . ' $k: ' . $k . "<br>";
				if( strpos( $m , $j ) !== -1 ) {
					
					unset( $shortcode_tags[$j] );
				}
				
			}
			
		}
		
		
	} else {
		
		if( $desktop_filters != null ) {
		
			foreach( $desktop_filters as $m ) {
				
				foreach ($shortcode_tags as $j => $k) {
					// echo '$m: ' . $m . ' $j: ' . $j . ' $k: ' . $k . "<br>";
					if( strpos( $m , $j ) !== -1 ) {
						
						unset( $shortcode_tags[$j] );
					}
					
				}
				
			}
		}
		
	}
}
add_action( 'pre_get_posts', 'your_function_name' );


add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page(){
    add_menu_page( 'Shortcode Filter', 'Shortcode Filter', 'manage_options', 'shortcode-filter', 'my_custom_menu_page');    
}


add_action( 'wp_ajax_labdevs_settings_update', 'labdevs_settings_update_callback' );
function labdevs_settings_update_callback() {
	$params = array();
	parse_str($_POST['data'], $params);
	foreach( $params as $k => $v ) {

		update_option( $k , $v );
	}	
}

function my_custom_menu_page(){
    ?>
    <script>
function labdevs_show_settings_saved() {
   jQuery('.labdevs_small_success').fadeIn(500);
   setTimeout( function(){ jQuery('.labdevs_small_success').fadeOut(500); } , 5000 );
}
</script>
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
		<script>
			jQuery(document).ready(function($){
				jQuery('.labdevs_settings_button_save_changes').click(function() {					
						var key = jQuery(this).attr('id');
						var value = jQuery(this).val();
						$.ajax({			
							type: "POST",
							url: "<?php echo admin_url('admin-ajax.php'); ?>",
							data: { 
								action: 'labdevs_settings_update',
								data : jQuery('.the_settings').serialize()								
							}
						}).done(function( data ) {				
								console.log(data);
								labdevs_show_settings_saved();							
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
						<option <?php if( $select_field == 'Desktop' ) echo 'selected' ?>>
							Desktop
						</option>
						<option <?php if( $select_field == 'Mobile' ) echo 'selected' ?>>
							Mobile
						</option>
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
?>
