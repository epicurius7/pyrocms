<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Settings extends Module {

	public $version = '0.4';

	public function info()
	{
		return array(
			'name' => array(
				'si' => 'Nastavitve',
				'en' => 'Settings',
				'nl' => 'Instellingen',
				'es' => 'Configuraciones',
				'fr' => 'Paramètres',
				'de' => 'Einstellungen',
				'pl' => 'Ustawienia',
				'pt' => 'Configurações',
				'zh' => '網站設定',
				'it' => 'Impostazioni',
				'ru' => 'Настройки',
				'cs' => 'Nastavení',
				'ar' => 'الإعدادات',
				'fi' => 'Asetukset'
			),
			'description' => array(
				'si' => 'Dovoljuje administratorjem posodobitev nastavitev kot je Ime strani, sporočil, email naslova itd.',
				'en' => 'Allows administrators to update settings like Site Name, messages and email address, etc.',
				'nl' => 'Maakt het administratoren en medewerkers mogelijk om websiteinstellingen zoals naam en beschrijving te veranderen.',
				'es' => 'Permite a los administradores y al personal configurar los detalles del sitio como el nombre del sitio y la descripción del mismo.',
				'fr' => 'Permet aux admistrateurs et au personnel de modifier les paramètres du site : nom du site et description',
				'de' => 'Erlaubt es Administratoren die Einstellungen der Seite wie Name und Beschreibung zu ändern.',
				'pl' => 'Umożliwia administratorom zmianę ustawień strony jak nazwa strony, opis, e-mail administratora, itd.',
				'pt' => 'Permite com que administradores e a equipe consigam trocar as configurações do website incluindo o nome e descrição.',
				'zh' => '網站管理者可更新的重要網站設定。例如：網站名稱、訊息、電子郵件等。',
				'it' => 'Permette agli amministratori di aggiornare impostazioni quali Nome del Sito, messaggi e indirizzo email, etc.',
				'ru' => 'Управление настройками сайта - Имя сайта, сообщения, почтовые адреса и т.п.',
				'cs' => 'Umožňuje administrátorům měnit nastavení webu jako jeho jméno, zprávy a emailovou adresu apod.',
				'ar' => 'تمكن المدراء من تحديث الإعدادات كإسم الموقع، والرسائل وعناوين البريد الإلكتروني، .. إلخ.',
				'fi' => 'Mahdollistaa sivuston asetusten muokkaamisen, kuten sivuston nimen, viestit ja sähköpostiosoitteet yms.'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => FALSE
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('settings');

		$settings = "
			CREATE TABLE `settings` (
			  `slug` varchar(30) collate utf8_unicode_ci NOT NULL,
			  `title` varchar(100) collate utf8_unicode_ci NOT NULL,
			  `description` text collate utf8_unicode_ci NOT NULL,
			  `type` set('text','textarea','password','select','select-multiple','radio','checkbox') collate utf8_unicode_ci NOT NULL,
			  `default` varchar(255) collate utf8_unicode_ci NOT NULL,
			  `value` varchar(255) collate utf8_unicode_ci NOT NULL,
			  `options` varchar(255) collate utf8_unicode_ci NOT NULL,
			  `is_required` tinyint(1) NOT NULL,
			  `is_gui` tinyint(1) NOT NULL,
			  `module` varchar(50) collate utf8_unicode_ci NOT NULL,
			PRIMARY KEY  (`slug`),
			UNIQUE KEY `unique - slug` (`slug`),
			KEY `index - slug` (`slug`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores all sorts of settings for the admin to change';
		";

		$default_settings = "
			INSERT INTO `settings` (`slug`, `title`, `description`, `type`, `default`, `value`, `options`, `is_required`, `is_gui`, `module`) VALUES
			 ('site_name','Site Name','The name of the website for page titles and for use around the site.','text','Un-named Website','','','1','1',''),
			 ('site_slogan','Site Slogan','The slogan of the website for page titles and for use around the site.','text','Add your slogan here','','','0','1',''),
			 ('contact_email','Contact E-mail','All e-mails from users, guests and the site will go to this e-mail address.','text','".DEFAULT_EMAIL."','','','1','1',''),
			 ('server_email','Server E-mail','All e-mails to users will come from this e-mail address.','text','admin@localhost','','','1','1',''),
			 ('meta_topic','Meta Topic','Two or three words describing this type of company/website.','text','Content Management','','','0','1',''),
			 ('currency','Currency','The currency symbol for use on products, services, etc.','text','&pound;','','','1','1',''),
			 ('dashboard_rss', 'Dashboard RSS Feed', 'Link to an RSS feed that will be displayed on the dashboard.', 'text', 'http://feeds.feedburner.com/pyrocms-installed', '', '', 0, 1, ''),
			 ('date_format', 'Date Format', 'How should dates be displayed accross the website and control panel? Using PHP date format.', 'text', 'Y-m-d', '', '', 1, 1, ''),
			 ('dashboard_rss_count', 'Dashboard RSS Items', 'How many RSS items would you like to display on the dashboard ? ', 'text', '5', '5', '', 1, 1, ''),
			 ('frontend_enabled','Site Status','Use this option to the user-facing part of the site on or off. Useful when you want to take the site down for maintenence','radio','1','','1=Open|0=Closed','1','1',''),
			 ('unavailable_message','Unavailable Message','When the site is turned off or there is a major problem, this message will show to users.','textarea','Sorry, this website is currently unavailable.','','','0','1',''),
			 ('default_theme','Default Theme','Select the theme you want users to see by default.','','default','','get_themes','1','0',''),
			 ('activation_email','Activation Email','Send out an e-mail when a user signs up with an activation link. Disable this to let only admins activate accounts.','radio','1','','1=Enabled|0=Disabled','0','1',''),
			 ('records_per_page','Records Per Page','How many records should we show per page in the admin section?','select','25','','10=10|25=25|50=50|100=100','1','1',''),
			 ('rss_feed_items','Feed item count','How many items should we show in RSS/blog feeds?','select','25','','10=10|25=25|50=50|100=100','1','1',''),
			 ('require_lastname','Require last names?','For some situations, a last name may not be required. Do you want to force users to enter one or not?','radio','1','','1=Required|0=Optional','1','1',''),
			 ('enable_profiles','Enable profiles','Allow users to add and edit profiles.','radio','1','','1=Enabled|0=Disabled','1','1','users'),
			 ('ga_email','Google Analytic E-mail','E-mail address used for Google Analytics, we need this to show the graph on the dashboard.','text','','','','0','1','integration'),
			 ('ga_password','Google Analytic Password','Google Analytics password. This is also needed this to show the graph on the dashboard.','password','','','','0','1','integration'),
			 ('ga_profile','Google Analytic Profile ID','Profile ID for this website in Google Analytics.','text','','','','0','1','integration'),
			 ('ga_tracking','Google Tracking Code','Enter your Google Analytic Tracking Code to activate Google Analytics view data capturing. E.g: UA-19483569-6','text','','','','0','1','integration'),
			 ('twitter_username','Username','Twitter username.','text','','','','0','1','twitter'),
			 ('twitter_consumer_key','Consumer Key','Twitter consumer key.','text','','','','0','1','twitter'),
			 ('twitter_consumer_key_secret','Consumer Key Secret','Twitter consumer key secret.','text','','','','0','1','twitter'),
			 ('twitter_blog','Twitter &amp; Blog integration.','Would you like to post links to new blog articles on Twitter?','radio','0','','1=Enabled|0=Disabled','0','1','twitter'),
			 ('twitter_feed_count','Feed Count','How many tweets should be returned to the Twitter feed block?','text','5','','','0','1','twitter'),
			 ('twitter_cache', 'Cache time', 'How many minutes should your Tweets be stored?','text','300','','','0','1','twitter'),
			 ('akismet_api_key', 'Akismet API Key', 'Akismet is a spam-blocker from the WordPress team. It keeps spam under control without forcing users to get past human-checking CAPTCHA forms.', 'text', '', '', '', 0, '1', 'integration'),
			 ('moderate_comments', 'Moderate Comments', 'Force comments to be approved before they appear on the site.', 'select', '0', '', '1=Enabled|0=Disabled', '0', '1', 'comments'),
			 ('comment_order', 'Comment Order', 'Sort order in which to display comments.', 'select', 'ASC', 'ASC', 'ASC=Oldest First|DESC=Newest First', '1', '1', 'comments'),
			 ('enable_comments', 'Enable Comments', 'Enable comments.', 'radio', '1', '1', '1=Enabled|0=Disabled', '0', '1', 'comments'),
			 ('mail_protocol', 'Mail Protocol', 'Select desired email protocol.', 'select', 'mail', 'mail', 'mail=Mail|sendmail=Sendmail|smtp=SMTP', '1', '1', ''),
			 ('mail_sendmail_path', 'Sendmail Path', 'Path to server sendmail binary.', 'text', '', '', '', '0', '1', ''),
			 ('mail_smtp_host', 'SMTP Host Name', 'The host name of your smtp server.', 'text', '', '', '', '0', '1', ''),
			 ('mail_smtp_user', 'SMTP User Name', 'SMTP user name.', 'text', '', '', '', '0', '1', ''),
			 ('mail_smtp_pass', 'SMTP Password', 'SMTP password.', 'text', '', '', '', '0', '1', ''),
			 ('mail_smtp_port', 'SMTP Port', 'SMTP port number.', 'text', '', '', '', '0', '1', ''),
			 ('version', 'Version', '', 'text', '1.0', '".CMS_VERSION."', '', '0', '0', '');
		";

		if ($this->db->query($settings) && $this->db->query($default_settings))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		//it's a core module, lets keep it around
		return FALSE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.";
	}
}
/* End of file details.php */
