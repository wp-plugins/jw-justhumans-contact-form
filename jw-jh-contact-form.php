<?php
/*
Plugin Name: JustHumans Contact Form
Plugin URI: http://jacksonwhelan.com/
Description: Simple plugin for inserting JustHumans powered contact forms.
Author: Jackson Whelan
Version: 1.0
Author URI: http://jacksonwhelan.com/
*/

// create custom plugin settings menu
add_action('admin_menu', 'jw_create_menu');

function jw_create_menu() {
	add_management_page('JustHumans Settings', 'JustHumans Settings', 'administrator', __FILE__, 'jw_settings_page');
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	register_setting( 'jw-settings-group', 'jw-jh-button-code' );
}

function jw_settings_page() { ?>
<div class="wrap">
<h2>Just Humans Contact Form</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'jw-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">JustHumans src</th>
        <td><input type="text" name="jw-jh-button-code" value="<?php echo get_option('jw-jh-button-code'); ?>" /><br/>
		<pre>Ex: <strong>http://verify.yourdomain.com/verification.js?k=721b877b803ec027a0abf4ab8ae57</strong>
&lt;script language="JavaScript" src="<u>http://verify.yourdomain.com/verification.js?k=721b877b803ec027a0abf4ab8ae57</u>"&gt;&lt;/script&gt;</pre>
		<p>This is copied from the form code page at <a href="http://justhumans.com">JustHumans</a> for your specific form.</p>
		</td>
        </tr>
    </table>
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
<p>Use the following shortcode in your posts or pages where you'd like the form to appear, where the fields attribute is a comma separated list of your form fields. Fields titled "Message, Note or Comment" will be displayed as a textarea.</p>
<pre>[contactform fields="Name,Email,Phone,Message"]</pre>
<p>The form is marked up in a table for simplicity, and easily styled via CSS. Here's some simple styles to get you started.</p>
<pre>
&lt;style&gt;

table.jw-jh-table td {
	vertical-align: top;
	padding: 5px;
}
form.jw-jh-form label {
	display:block;
	width:100px;		
}
form.jw-jh-form input {
	width:200px;
}
form.jw-jh-form textarea {
	width:200px;
	height:150px;		
}

&lt;/style&gt;	
</pre>
</div>
<?php } function jw_jh_contact_form($atts) {
	extract(shortcode_atts(array(
		'fields' => ''
	), $atts));
	$jh_submit = '<script language="JavaScript" src="'.get_option('jw-jh-button-code').'"></script>';
	$out = '<!-- JW JustHumans Contact Form -->';
	$out.= '<form method="post" class="jw-jh-form"><table class="jw-jh-table" cellpadding="6">';
	$fields = explode(',', $fields);
	foreach($fields as $field) {
		$field = trim($field);
		if($field == 'Message' || $field == 'Note' || $field == 'Comment') {
			$out.= '<tr><td><label>'.$field.':</label></td><td><textarea name="'.$field.'" /></textarea></td></tr>';
		} else { 
			$out.= '<tr><td><label>'.$field.':</label></td><td><input type="text" name="'.$field.'" /></td></tr>';
		}
	}
	$out.= '<tr><td></td><td class="jw-jh-submit">'.$jh_submit.'</td></tr></table></form>';
	return $out;
}
add_shortcode('contactform', 'jw_jh_contact_form');
?>