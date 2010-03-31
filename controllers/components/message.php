<?php 
class MessageComponent extends Object {
	
	/**
	 * We are using the SwiftEmail component which is a drop-in replacement for Cake's Email component, 
	 * which provides a 'type' key in the smtpOptions array, letting us use 'ssl', 'tls' or 'open', but 
	 * otherwise behaves pretty much the same as cake's Email component.
	 * 
	 * Not sure we need this component, might be simpler to do everything in the controller?
	 * 
	 */
	
	var $components = array('SwiftEmail');
	var $uses = array();
	
	function startup(&$controller) {
		$this->controller =& $controller;
		
		/**
		 * The following smtpOptions are specific to my Gmail account, these need to be customisable on a user-basis.
		 * Perhaps with a company default host, port and type that each user inherits by default?
		 */
		
		$this->SwiftEmail->smtpOptions = array(
			'port'=>'465', // gmail requires 465 or 587
			'type'=>'tls', // added support for "type" key by SwiftEmail (ssl, tls or open (default)) - gmail requires tls.
			'timeout'=>'30', // this should be global
		);
		
		$this->robotSmtpOptions = array(
			'port'=>Configure::read('Robot.SMTP.port'),
			'type'=>Configure::read('Robot.SMTP.type'), // TODO: why this and the below?
			'security'=>Configure::read('Robot.SMTP.type'),// TODO: why this and the above?
			'host'=>Configure::read('Robot.SMTP.host'),
			'username'=>Configure::read('Robot.SMTP.username'),
			'password'=>Configure::read('Robot.SMTP.password'),
			'timeout'=>'30'
		);
		
		$this->SwiftEmail->delivery = 'smtp';
		$this->SwiftEmail->sendAs = 'both';
	}

	/**
	* Includes verify email link, but also functions as welcome email.
	* Can be used for:
	* 	- automatic email confirmation request for sites where anyyone can sign up so long as they verify their email with this (not implemented in controller yet)
	* 	- administrator-triggered welcome/activation email when someone's account has been approved
	* 	- sending a combined welcome/activate/here's your password for situations where user account has been created on the user's behalf and they don't know their temporary password yet
	* 		- for this ensure 'password' is set in the options array to their unencrypted password
	*/

	function sendActivationEmail($data, $options=array()) {
		$defaults = array(
			'password'=>false
		);
		$settings = am($defaults,$options);

		$this->SwiftEmail->smtpOptions = $this->robotSmtpOptions;
		
		$this->SwiftEmail->to = $data['User']['email'];
		$this->SwiftEmail->from = Configure::read('Robot.fromAddress');
		$this->SwiftEmail->subject = Configure::read('Site.title').' Account Activation';
		$this->SwiftEmail->template = 'activation';
		
		$this->controller->set('data',$data);
		$this->controller->set('password',$settings['password']);
		if ($this->SwiftEmail->send()) {
			return true;
		}
		//trigger_error('Error sending email:'.$this->SwiftEmail->smtpError);
		$this->log('Error sending email:'.$this->SwiftEmail->smtpError,LOG_ERROR);
		return false;
	}

	function sendAdministratorActivationEmail($options) {
		
		$this->SwiftEmail->smtpOptions = $this->robotSmtpOptions;
		
		$this->SwiftEmail->to = Configure::read('Site.emailsTo');
		$this->SwiftEmail->from = Configure::read('Robot.fromAddress');
		$this->SwiftEmail->subject = Configure::read('Site.title').' : New Account for Approval';
		$this->SwiftEmail->template = 'administrator_activation';
		
		$this->controller->set('data',$options);
		if ($this->SwiftEmail->send()) {
			return true;
		}
		//trigger_error('Error sending email:'.$this->SwiftEmail->smtpError);
		$this->log('Error sending email:'.$this->SwiftEmail->smtpError,LOG_ERROR);
		return false;
	}

	function sendPasswordResetEmail($options) {
		
		$this->SwiftEmail->smtpOptions = $this->robotSmtpOptions;
		
		$this->SwiftEmail->to = $options['to'];
		$this->SwiftEmail->from = Configure::read('Robot.fromAddress');
		$this->SwiftEmail->subject = Configure::read('Site.title').' Password Reset';
		$this->SwiftEmail->template = 'password_reset';
		
		$this->controller->set('data',$options);
		if ($this->SwiftEmail->send()) {
			return true;
		}
		//trigger_error('Error sending email:'.$this->SwiftEmail->smtpError);
		$this->log('Error sending email:'.$this->SwiftEmail->smtpError,LOG_ERROR);
		return false;
	}
}
?>
