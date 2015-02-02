<?php

/**
 * Captcha of Recaptcha_XH.
 *
 * Copyright (c) 2011 Christoph M. Becker (see license.txt)
 */
 

// utf-8-marker: äöüß


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Returns the (x)html block element displaying the captcha,
 * the input field for the captcha code and all other elements,
 * that are related directly to the captcha,
 * such as an reload and an audio button.
 *
 * @return string
 */
function recaptcha_captcha_display() {
    global $pth, $plugin_cf, $plugin_tx, $hjs, $sl;
    
    $pcf =& $plugin_cf['recaptcha'];
    $ptx =& $plugin_tx['recaptcha'];
    
    require_once $pth['folder']['plugins'].'recaptcha/recaptcha/recaptchalib.php';
    $hjs .= '<script type="text/javascript">'."\n".'/* <![CDATA[ */'."\n"
	    .'var RecaptchaOptions = {'."\n"
	    .'    custom_translations: {'."\n";
    $first = TRUE;
    foreach ($ptx as $key => $val) {
	$keys = explode('_', $key, 2);
	if ($keys[0] == 'captcha' && $val != '') {
	    if ($first) {
		$hjs .= '        ';
		$first = FALSE;
	    } else {
		$hjs .= ','."\n".'        ';
	    }
	    $hjs .= $keys[1].': \''.addslashes($val).'\'';
	}
    }
    $hjs .= "\n".'    },'."\n"
	    .'    theme: \''.$pcf['theme'].'\','."\n"
	    .'    lang: \''.$sl.'\''."\n"
	    .'}'."\n"
	    .'/* ]]> */'."\n".'</script>'."\n";
    return recaptcha_get_html($pcf['key_public']);
}


/**
 * Returns wether the correct captcha code was entered
 * after the form containing the captcha was posted.
 *
 * @return bool
 */
function recaptcha_captcha_check() {
    global $pth, $plugin_cf;
    
    require_once $pth['folder']['plugins'].'recaptcha/recaptcha/recaptchalib.php';
    $resp = recaptcha_check_answer ($plugin_cf['recaptcha']['key_private'],
	    $_SERVER['REMOTE_ADDR'],
	    $_POST['recaptcha_challenge_field'],
	    $_POST['recaptcha_response_field']);
    return $resp->is_valid;
}

?>
