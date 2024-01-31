<?php

include_once 'xpertmailer/MAIL.php';

function fw24h_sendmail( $to, $subject, $messgae, $cc='', $fromEmail='') {
    global $_FW24H_MAIL_CONF;    
	$m = new MAIL; // initialize MAIL class
	if ($fromEmail == '') {
		$m->From( $_FW24H_MAIL_CONF['from']); // set from address
	}
	else {
		$m->From( $fromEmail);
	}
	$m->AddTo( $to); // add to address
	
	if (!is_array($cc)) {
		$cc = explode(',', $cc);
	}
	$n_cc = count($cc);	
	for ($i=0; $i<$n_cc; $i++) {
		if ($cc[$i] !='') {
			$m->AddBcc($cc[$i]);
		}
	}
	
	$m->Subject( $subject); // set subject
	$m->Html( $messgae); // set text message

	$return = false;

	if( $_FW24H_MAIL_CONF['method'] == 'smtp') {
		$c = @$m->Connect( $_FW24H_MAIL_CONF['mail_server'], 25, $_FW24H_MAIL_CONF['username'], $_FW24H_MAIL_CONF['password']);// or die(print_r($m->Result));
		$return = $m->Send( $c);
	}else {
		$return = $m->Send();
	}

	#print_r($m->History); // optional, for debugging

	$m->Disconnect(); // disconnect from server

	return $return;
}
