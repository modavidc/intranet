<?php
require_once('util.php');
require_once('config_sms.php');
require_once('smstype.php');
require_once('http_post.php');

function trendoo_get_new_SMS_MOs(){
	$post = new Trendoo_POST();
	return getAllSMS_MO($post->do_post(TRENDOO_NEW_SMS_MO_REQUEST));
}

function trendoo_get_sms_mo_history($fromdate, $todate) {
	$post = new Trendoo_POST();
	$post->add_param('from',$fromdate);
	$post->add_param('to',$todate);
	return getAllSMS_MO($post->do_post(TRENDOO_MO_HIST_REQUEST));
}

function trendoo_get_sms_mo_byid($message_id) {
	$post = new Trendoo_POST();
	$post->add_param('id',$message_id);
	return getAllSMS_MO($post->do_post(TRENDOO_MO_BYID_REQUEST));
}

function getAllSMS_MO($rp) {
	$res = $rp->get_result_array();
	$count = 0;
	if ($rp->isok) {
		while ($rp->go_next_line()) {
			$res[] = new Trendoo_SMS_MO($rp->next_long(),$rp->next_string(),$rp->next_string(),
					$rp->next_string(),$rp->next_string(),$rp->next_string());
			$count++;
		}
	}
	$res['count'] = $count;
	return $res;
}

class Trendoo_SMS_MO {
	var $id_message;
	var $send_date;
	var $message;
	var $keyword;
	var $sms_sender;
	var $sms_recipient;
	
	function Trendoo_SMS_MO($id_message, $sms_recipient, $sms_sender, $message, $send_date, $keyword) {
		$this->id_message = $id_message;
		$this->sms_recipient = $sms_recipient;
		$this->sms_sender = $sms_sender;
		$this->send_date = $send_date;
		$this->message = $message;
	}
}

?>
