<?php
App::uses('AppModel', 'Model');

class PasswordReset extends AppModel {

	public $useTable = 'password_reset';

	public $displayField = 'hash';

}
