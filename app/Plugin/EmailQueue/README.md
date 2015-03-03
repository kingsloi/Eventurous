# CakePHP Email Queue plugin #

This plugin provides an interface for creating emails on the fly and
store them in a queue to be processed later by an offline worker using a
cakephp shell command.

## Requirements ##

* CakePHP 2.x

## Installation ##

There are a few ways you can choose for intalling this plugin:

_[Manual]_

* Download this: [https://github.com/nodesagency/cakephp-email-queue/zipball/master](https://github.com/nodesagency/cakephp-email-queue/zipball/master)
* Unzip that download.
* Copy the resulting folder to `app/Plugin`
* Rename the folder you just copied to `EmailQueue`

_[GIT Submodule]_

In your app directory type:

	git submodule add git@github.com:nodesagency/cakephp-email-queue.git app/Plugin/EmailQueue
	git submodule init
	git submodule update

_[GIT Clone]_

In your plugin directory type

	git clone git://github.com/nodesagency/cakephp-email-queue.git app/Plugin/EmailQueue

### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:

    CakePlugin::load('EmailQueue');

If you are already using `CakePlugin::loadAll();`, then this is not necessary.

### Load required database table

In order to use this plugin, you need to create a database table.
Required SQL is located at

	# Config/Schema/email_queue.sql

Just load it into your database.

## Usage

Whenever you need to send an email, use the EmailQueue model to create
and queue a new one by storing the correct data:

	ClassRegistry::init('EmailQueue.EmailQueue')->enqueue($to, $data, $options);

`enqueue` method receives 3 arguments:

- First argument is a string or array of email addresses that will be treated as recipients.
- Second arguments is an array of view variables to be passed to the
  email template
- Third arguments is an array of options, possible options are
 * `subject` : Email's subject
 * `send_at` : date time sting representing the time this email should be sent at (in UTC)
 * `template` :  the name of the element to use as template for the email message
 * `layout` : the name of the layout to be used to wrap email message
 * `format` : Type of template to use (html, text or both)
 * `config` : the name of the email config to be used for sending

### Sending emails

Emails should be sent using bundled Sender command, use `-h` modifier to
read available options

	# Console/cake EmailQueue.Sender -h

You can configure this command to be run under a cron or any other tool
you wish to use.
