<?php

/*
 *  Mailer class
 *
 *  Version 0.0.1
 *
 * 	Created by: Gustavo Real
 * 	Date: 10/05/2014
 */

class Mailer {

	public static function send($params) {
		if (!is_array($params)) {
			return false;
		}

		$to_address = $params['to'];
        $from_address = isset($params['from']) ? trim($params['from']) : Yii::app()->params['mailer_senderEmail'];
		$from_name = isset($params['name']) ? $params['name'] : Yii::app()->params['mailer_senderName'];
		$subject = $params['subject'];
		$message = $params['body'];
		
		Yii::app()->mailer->ClearAddresses();
		Yii::app()->mailer->ClearCCs();
		Yii::app()->mailer->ClearBCCs();
		Yii::app()->mailer->ClearReplyTos();
		Yii::app()->mailer->ClearAllRecipients();
		Yii::app()->mailer->ClearAttachments();
		Yii::app()->mailer->ClearCustomHeaders();
		
		Yii::app()->mailer->Host = Yii::app()->params['mailer_host'];
		Yii::app()->mailer->IsSMTP();
		Yii::app()->mailer->Port=Yii::app()->params['mailer_port'];
		Yii::app()->mailer->SMTPAuth = Yii::app()->params['mailer_smtpauth'];     // turn on SMTP authentication
		Yii::app()->mailer->SMTPKeepAlive = true;
		Yii::app()->mailer->CharSet = 'utf-8';
		Yii::app()->mailer->SMTPDebug  = 0;
		Yii::app()->mailer->Mailer = "smtp";
		if(Yii::app()->params['mailer_smtpauth']){
			Yii::app()->mailer->SMTPSecure = Yii::app()->params['SMTPSecure'];
			Yii::app()->mailer->Username = Yii::app()->params['mailer_username'];  // SMTP username
			Yii::app()->mailer->Password = Yii::app()->params['mailer_password']; // SMTP password
		}
		Yii::app()->mailer->IsHTML(true);
		
		Yii::app()->mailer->From = $from_address;
		Yii::app()->mailer->FromName = $from_name;
		Yii::app()->mailer->Subject = $subject;
		Yii::app()->mailer->Body = $message;
		
		if(!Common::check_email_address($to_address)) {
			return false;
		}
		// Email correcto, enviar
		Yii::app()->mailer->AddAddress($to_address);
		Yii::app()->mailer->AddReplyTo(Yii::app()->params['mailer_replyToEmail'],Yii::app()->params['mailer_replyToName']);
		try{
			$ret_value = Yii::app()->mailer->Send();
		} catch (phpmailerException $e) {
			return false;
		} catch (Exception $e) {
			return false;
		}
		return $ret_value;
	}
}