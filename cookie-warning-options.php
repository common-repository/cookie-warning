<?php

add_action( 'admin_menu' , 'cookie_warning_plugin_menu');
add_action( 'admin_init' , 'register_cookie_options' );

function cookie_warning_plugin_menu() {
	add_options_page('Cookie warning options', 'Cookie warning', 'manage_options', 'cookie-warning-page', 'cookie_warning_options_html');
}

function cookie_warning_options_html(){
    if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    $save_value = __( 'Save Changes' );
    $html = '<div class="wrap">';
    $html .= '<div style="padding-top:20px;"><img style="float:left;margin-right:8px;" width="32" height="32" src="'.plugin_dir_url(__FILE__).'cookie.png" /> <h2>Cookie warning options</h2></div>';
    $html .= '<form method="post" action="options.php">';
    echo $html;
settings_fields( 'cookie_plugin_options' );
do_settings_sections( 'cookie_warn_options' );
    $html =<<<EOD
<p class="submit">
<input type="submit" class="button-primary" value="$save_value" />
</p>
</form>
</div>
EOD;
    echo $html;
    
}

//options registration
function register_cookie_options(){
    //register cookie warning options
    register_setting( 'cookie_plugin_options' , //options group
		      'cookiewarn_options' , //name of the option (all options are lumped into one array)
		      'cookie_options_validate'
		    );
    add_settings_section( 'cookie_option_main' , //settings section id
			  'Pop up details' , //title of the section
			  'cookie_warn_section_text' , //
			  'cookie_warn_options' //options page name
			 );
    //main text
    add_settings_field( 'cookie_warn_text' , //field id
		        'Message:' , //options label
		        'cookie_warn_text_html' , //function to output HTML
		        'cookie_warn_options' , //options page name
		        'cookie_option_main' //settings section handler
		       );
    //redirect link
    add_settings_field( 'redirect_link' , //field id
		        'URL to redirect to on rejection:' , //options label
		        'redirect_link_html' , //function to output HTML
		        'cookie_warn_options' , //options page name
		        'cookie_option_main' //settings section handler
		       );
    //ok text
    add_settings_field( 'ok_text_field' , //field id
		        'Approval button text:' , //options label
		        'ok_text_html' , //function to output HTML
		        'cookie_warn_options' , //options page name
		        'cookie_option_main' //settings section handler
		       );
    //no ok text
    add_settings_field( 'notok_text_field' , //error code
		        'Rejection link text:' , //options label
		        'notok_text_html' , //function to output HTML
		        'cookie_warn_options' , //options page name
		        'cookie_option_main' //settings section handler
		       );
}

function cookie_warn_section_text(){
    echo '';
}

function cookie_warn_text_html(){
    $options = get_option( 'cookiewarn_options' , get_cookie_defaults() );
    echo "<textarea id='cookie_warn_text' style=\"width:75%;\" rows=\"5\" name='cookiewarn_options[warn_text]'>".str_replace("<br />", "\n", $options['warn_text'])."</textarea>";
}

function redirect_link_html(){
    $options = get_option( 'cookiewarn_options' , get_cookie_defaults() );
    echo "<input type='text' id='redirect_link' name='cookiewarn_options[redirect]' value='{$options['redirect']}' />";
}

function ok_text_html(){
    $options = get_option( 'cookiewarn_options' , get_cookie_defaults() );
    echo "<input type='text' id='ok_text_field' name='cookiewarn_options[ok_text]' value='{$options['ok_text']}' />";
}

function notok_text_html(){
    $options = get_option( 'cookiewarn_options' , get_cookie_defaults() );
    echo "<input type='text' id='notok_text_field' name='cookiewarn_options[notok_text]' value='{$options['notok_text']}' />";
}

function cookie_options_validate($input){
    $input[ 'warn_text' ] = esc_attr( $input[ 'warn_text' ] );
    $input[ 'redirect' ] = esc_attr( $input[ 'redirect' ] );
    $input[ 'ok_text' ] = esc_attr( $input[ 'ok_text' ] );
    $input[ 'notok_text' ] = esc_attr( $input[ 'notok_text' ] );
    return $input;
}

?>