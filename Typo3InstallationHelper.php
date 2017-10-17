<?php
/*
 * A small script to call several commands for managing typo3 installations through php, if you don't have access to a shell.
 * Typical commands used to install a new typo3 website and some other stuff.
 *
 * Tabulator width: 2
 *
 * @author: <sunixzs@gmail.com>
 * @version: 1.0.0
 * @license: no license - do what you want but in your own risk (this script is possible insecure - best is to remove it from server after work!)
 */

/**************************************************************************************
 * (1) Generate a file called ENABLE_TYPO3_INSTALLATION_HELPER to enable this script.	*
 * (2) Check the "CONFIGURATION"-part below!																					*
 * (3) setPassword()																																	*
 * (4) setEncryptionKey()																															*
 * (5) Call this script in your browser																								*
 * (6) HAVE A BACKUP OF YOUR SITE																											*
 * 		 WITH THIS SCRIPT YOU CAN KILL YOUR SITE WITH JUST TWO CLICKS										*
 * (7) KNOW WHAT YOU ARE DOING																												*
 **************************************************************************************/

// make instance
$Typo3Commands = new Typo3Commands ();

/***********************
 * CONFIGURATION START *
 ***********************/

/**
 * This password is used as an additional security mechanism.
 * 
 * !!! Keep in mind, that everyone with access to this script has full access to your document root and maybe to your complete webserver !!!
 * 
 * if $Typo3Commands->setUseLoginSession( false ):
 * The password will be submitted automatically (un-encrypted) on every call in the form data.
 * 
 * With $Typo3Commands->setUseLoginSession( true ) you can use a login session instead of submitting the password all the time.
 * Then you also have to set an encryption key, which will be used to hash the password in session.
 * @todo think about a better method.
 */
$Typo3Commands->setPassword ( "" );
$Typo3Commands->setEncryptionKey ( "" );
$Typo3Commands->setUseLoginSession ( true );

/**
 * Typo3-Version working with and to get from sourceforge.net
 */
$Typo3Commands->setTypo3Version ( "8.7.8" );

/**
 * Host where your typo3 package with a website in could be found.
 */
$Typo3Commands->setHttpSourceDomain ( "http://user:password@dev.example.org/" );

/**
 * The name with your typo3 website package on the host above - a tar.gz-file.
 * 
 * Example of the inner of the ball:
 * typo3site.de/
 * typo3site.de/fileadmin/ (and deeper)
 * typo3site.de/typo3conf/ (and deeper)
 * typo3site.de/uploads/ (and deeper)
 * typo3site.de/.htaccess
 */
$Typo3Commands->setHttpSourcePackage ( "typo3site.de.tar.gz" );

/**
 * The directory where your typo3 website package is in after extracting.
 */
$Typo3Commands->setLocalTarDirectory ( "typo3site.de/" );

/**
 * Path to the Typo3 configuration file for database access.
 */
$Typo3Commands->setTypo3ConfigurationFile ( 'typo3conf/LocalConfiguration.php' );

/**
 * The path to the mysqldump for import. 
 */
$Typo3Commands->setMysqlImportDumpFile ( 'mysqldump_Typo3-X.X.X_20170327-130451_db290431_201.sql' );

/**
 * The user and password which will be set in the .htpasswd file.
 * Use an online generator to generate a line.
 */
$Typo3Commands->setHtpasswdLine ( "lbr:\$1\$Xlq{i[^z\$Ka2bw0De9s7fftTvqZ3fs0" );

/**
 * If you have a very old installation without utf8 support
 * and if you have luck
 * you maybe can convert some database fields from latin1 to utf8.
 * Set the tablenames and fieldnames which should be converted.
 * 
 * ONLY RUN FIELDS ONCE WHILE CONVERTING!
 * RUN MORE THAN ONCE BREAKS THE CHARS AGAIN!
 * 
 * @var array
 */
$convertLatin1ToUtf8Configuration = array (
		"pages" => array (
				"title",
				"subtitle",
				"keywords",
				"description",
				"nav_title" 
		),
		"tt_content" => array (
				"bodytext",
				"header",
				"altText",
				"titleText",
				"longdescURL",
				"pi_flexform",
				"lbr_contentlayout_ff_pi1",
				"lbr_contentlayout_ff_pi2",
				"lbr_contentlayout_ff_pi3",
				"lbr_contentlayout_ff_pi4",
				"lbr_contentlayout_ff_pi5" 
		),
		"sys_file_reference" => array (
				"title",
				"description",
				"alternative" 
		),
		"sys_note" => array (
				"author",
				"subject",
				"message" 
		),
		"sys_template" => array (
				"title",
				"sitetitle",
				"constants",
				"config" 
		),
		"tx_lbrdate_domain_model_category" => array (
				"title" 
		),
		"tx_lbrdate_domain_model_entry" => array (
				"title",
				"shorttitle",
				"subtitle",
				"titletag",
				"teaser",
				"teaserhtml",
				"text1",
				"text2",
				"imagescaptions" 
		),
		"tx_lbrform_domain_model_field" => array (
				"flexform" 
		),
		"tx_lbrform_domain_model_sent" => array (
				"data" 
		),
		"tx_lbrguestbook_domain_model_entry" => array (
				"firstname",
				"lastname",
				"place",
				"message",
				"comment" 
		) 
);
unset ( $convertLatin1ToUtf8Configuration[ 'pages' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'tt_content' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'sys_file_reference' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'sys_note' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'sys_template' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'tx_lbrdate_domain_model_category' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'tx_lbrdate_domain_model_entry' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'tx_lbrform_domain_model_field' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'tx_lbrform_domain_model_sent' ] );
unset ( $convertLatin1ToUtf8Configuration[ 'tx_lbrguestbook_domain_model_entry' ] );
$Typo3Commands->setConvertLatin1ToUtf8Configuration ( $convertLatin1ToUtf8Configuration );

/**********************
 * CONFIGURATION STOP *
 **********************/

if ($Typo3Commands->getUseLoginSession ()) {
	session_start ();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Typo3 Installation Helper</title>
<style type="text/css">
* {
	margin: 0;
	padding: 0;
}

html {
	font-size: 12px;
	line-height: 160%;
	font-family: sans-serif;
	color: black;
	background-color: lightgrey;
	padding: 1em;
}

form {
	z-index: 0;
}

form[name=logout_form] {
	position: absolute;
	top: 1em;
	right: 1em;
}

select {
	width: 90%;
	max-width: 60em;
	min-width: 30em;
	height: 35em;
	padding: .5em;
	z-index: 0;
}

optgroup {
	font-size: inherit;
	text-transform: uppercase;
	padding-top: .5em;
}

option {
	font-size: inherit;
	text-transform: none;
}

input[type=text], input[type=password] {
	display: block;
	clear: left;
	margin: .5em 0;
	width: 50%;
	max-width: 40em;
	min-width: 30em;
	padding: .5em;
	font-size: inherit;
	color: black;
	background-color: white;
	border: 1px solid black;
}

button {
	display: block;
	clear: left;
	padding: .5em 1em;
	text-transform: uppercase;
	font-size: inherit;
	color: black;
	background-color: white;
	border: 1px solid black;
	cursor: pointer;
}

hr {
	margin: 1em 0;
}

ul, ol {
	margin-left: 2em;
}

ul.command-list {
	list-style: none;
	margin: 0;
	padding: 0;
}

span.do_echo, span.do_error_echo {
	display: block;
	padding: .5em;
	color: white;
	font-weight: 700;
	text-transform: uppercase;
	font-size: larger;
	background-color: darkgrey;
	box-shadow: 3px 3px 8px rgba(0, 0, 0, .5);
	text-shadow: 1px 1px 3px rgba(0, 0, 0, .5);
}

span.do_error_echo {
	background-color: red;
}

span.do_exec, pre {
	display: block;
	background-color: white;
	box-shadow: 3px 3px 8px rgba(0, 0, 0, .5) inset;
	padding: .5em;
}
</style>
<link rel="shortcut icon" href="http://typo3.org/typo3conf/ext/t3org_template/icons/favicon.ico" type="image/x-ico; charset=binary" />
<link rel="icon" href="http://typo3.org/typo3conf/ext/t3org_template/icons/favicon.ico" type="image/x-ico; charset=binary" />
</head>
<body>
<?php
// call the main method to do the magick
try {
	$Typo3Commands->main ();
} catch ( Exception $e ) {
	$Typo3Commands->do_error_echo ( $e->getMessage () );
}

/**
 * The all-in-one class...
 */
class Typo3Commands {
	/**
	 * Password to check for.
	 * If you have not set setUseLoginSession( true ), you have to call this script with an additional parameter:
	 * Typo3InstallationHelper.php?password=String from $password
	 * @var string
	 */
	protected $password = "";
	
	/**
	 * @param string $password
	 * @return void
	 */
	public function setPassword($password) {
		$this->password = $password;
	}
	
	/**
	 * Key used to hash the password, if a LoginSession is used.
	 */
	protected $encryptionKey = "";
	
	/**
	 * @param string $encryptionKey
	 * @return void
	 */
	public function setEncryptionKey($encryptionKey) {
		$this->encryptionKey = $encryptionKey;
	}
	
	/**
	 * Password from $_GET or $_POST to compare and set in form on every submit
	 * @var string
	 */
	protected $submittedPassword = "";
	
	/**
	 * @var boolean
	 */
	protected $useLoginSession = false;
	
	/**
	 * @param boolean $useLoginSession
	 * @return void
	 */
	public function setUseLoginSession($useLoginSession) {
		$this->useLoginSession = $useLoginSession;
	}
	
	/**
	 * @return boolean
	 */
	public function getUseLoginSession() {
		return $this->useLoginSession;
	}
	
	/**
	 * The Typo3-Version working with.
	 * @var string
	 */
	protected $typo_version = '7.4.0';
	
	/**
	 * @param string $typo_version
	 * @return void
	 */
	public function setTypo3Version($typo_version) {
		$this->typo_version = $typo_version;
	}
	
	/**
	 * The domain to fetch the website package from.
	 * (with trailing /)
	 * @var string
	 */
	protected $http_source_domain = 'http://user:password@dev.example.tld/';
	
	/**
	 * @param string $http_source_domain
	 * @return void
	 */
	public function setHttpSourceDomain($http_source_domain) {
		$this->http_source_domain = $http_source_domain;
	}
	
	/**
	 * A tar.gz with a typo3-website in it on the domain above to wget and to extract for local use.
	 * @var string
	 */
	protected $http_source_package = 'websitepackage.tar.gz';
	
	/**
	 * @param string $http_source_package
	 * @return void
	 */
	public function setHttpSourcePackage($http_source_package) {
		$this->http_source_package = $http_source_package;
	}
	
	/**
	 * The directory-name of the extracted source-package.
	 * (with trailing /)
	 * @var string
	 */
	protected $local_tar_directory = 'websitepackage/';
	
	/**
	 * @param string $local_tar_directory
	 * @return void
	 */
	public function setLocalTarDirectory($local_tar_directory) {
		$this->local_tar_directory = $local_tar_directory;
	}
	
	/**
	 * Path to the local typo3 configuration file used for mysqldump and mysql insert.
	 * Its for reading the database connection.
	 * @var string
	 */
	protected $typo3_configuration_file = 'typo3conf/LocalConfiguration.php';
	
	/**
	 * @param string $typo3_configuration_file
	 * @return void
	 */
	public function setTypo3ConfigurationFile($typo3_configuration_file) {
		$this->typo3_configuration_file = $typo3_configuration_file;
	}
	
	/**
	 * Path of the mysqldump file for a mysql insert.
	 * @var string
	 */
	protected $mysql_import_dump_file = 'mysqldump_of_websitepackage.sql';
	
	/**
	 * @param string $mysql_import_dump_file
	 * @return void
	 */
	public function setMysqlImportDumpFile($mysql_import_dump_file) {
		$this->mysql_import_dump_file = $mysql_import_dump_file;
	}
	
	/**
	 * User and password to set in .htpasswd file
	 * @var string
	 */
	protected $htpasswd_line = "theuser:\$1\$Xlq{i[^z\$Ka2bw0De9s7fftTvqZ3fs0";
	
	/**
	 * @param string $htpasswd_line
	 * @return void
	 */
	public function setHtpasswdLine($htpasswd_line) {
		$this->htpasswd_line = $htpasswd_line;
	}
	
	/**
	 * @var array
	 */
	protected $convertLatin1ToUtf8Configuration = array ();
	
	/**
	 * @param array $convertLatin1ToUtf8Configuration
	 */
	public function setConvertLatin1ToUtf8Configuration($convertLatin1ToUtf8Configuration) {
		$this->convertLatin1ToUtf8Configuration = $convertLatin1ToUtf8Configuration;
	}
	
	/**
	 * @var array
	 */
	protected $typo3Configuration = array ();
	
	/**
	 * @var array
	 */
	protected $databaseConfiguration = array ();
	
	/**
	 * @var string
	 */
	protected $mysqldumpCmd = "/usr/bin/mysqldump";
	
	/**
	 * @var string
	 */
	protected $mysqlCmd = "/usr/bin/mysql";
	
	/**
	 * @var string
	 */
	protected $mysqldumpFilename = "";
	
	/**
	 * @var array
	 */
	protected $commandsByKey = array ();
	
	/**
	 * @var array
	 */
	protected $commandTitleReplacements = array ();
	
	/**
	 * @var string
	 */
	protected $dateTimeStr = "19700101_000000";
	
	/**
	 * The commands which could be selected in the form.
	 * @var array
	 */
	protected $commands = array (
			array (
					"label" => 'get typo3 source',
					'commands' => array (
							array (
									"label" => "get 'typo3_src-{TYPO3_VERSION}.tar.gz' from get.typo3.org",
									"command" => 'get_typo3_from_get_typo3_org' 
							),
							array (
									"label" => "get 'typo3_src-{TYPO3_VERSION}.tar.gz' from sourceforge",
									"command" => 'get_typo3_from_sourceforge' 
							),
							array (
									"label" => "extract 'typo3-src-{TYPO3_VERSION}.tar.gz' to working directory",
									"command" => 'extract_typo3-src_tar_gz' 
							) 
					) 
			),
			array (
					"label" => 'get website package',
					'commands' => array (
							array (
									"label" => "get website package '{HTTP_SOURCE_DOMAIN}{HTTP_SOURCE_PACKAGE}'",
									"command" => 'get_website_package' 
							),
							array (
									"label" => "extract website package '{HTTP_SOURCE_PACKAGE}' to '{LOCAL_TAR_DIRECTORY}'",
									"command" => 'extract_website_package' 
							) 
					) 
			),
			array (
					"label" => 'build local structure',
					'commands' => array (
							array (
									"label" => "move files from '{LOCAL_TAR_DIRECTORY}' into working directory",
									"command" => 'move_files_from_package_into_working_directory' 
							),
							array (
									"label" => "remove '{LOCAL_TAR_DIRECTORY}' directory",
									"command" => "remove_package_directory" 
							),
							array (
									"label" => "check and create symlinks",
									"command" => "check_and_create_symlinks" 
							),
							array (
									"label" => "copy 'index.php' from 'typo3_src'",
									"command" => "copy_index_php_from_typo3_src" 
							),
							array (
									"label" => "fix file permissions",
									"command" => "fix_file_permissions" 
							),
							array (
									"label" => "fix folder permissions",
									"command" => "fix_folder_permissions" 
							) 
					) 
			),
			'database' => array (
					"label" => 'database',
					'commands' => array (
							array (
									"label" => "make mysqldump: 'mysqldump_Typo3-current_typo3_version_{DATE_TIME}_name_of_database.sql'",
									"command" => 'make_mysqldump',
									"title" => "works only with newer Typo3 versions with LocalConfiguration-file" 
							),
							array (
									"label" => "import mysqldump: '{MYSQL_IMPORT_DUMP_FILE}'",
									"command" => 'import_mysqldump',
									"title" => "works only with newer Typo3 versions with LocalConfiguration-file" 
							),
							array (
									"label" => "convert latin1 characters on a utf8 table to utf8",
									"command" => 'convert_latin1_on_utf8_to_utf8',
									"title" => "works only with newer Typo3 versions with LocalConfiguration-file" 
							),
							array (
									"label" => "convert all tables to utf8_general_ci",
									"command" => 'convert_all_tables_to_utf8_general_ci',
									"title" => "works only with newer Typo3 versions with LocalConfiguration-file" 
							) 
					) 
			),
			array (
					"label" => '.htaccess / .htpasswd',
					'commands' => array (
							array (
									"label" => "create '.htpasswd'",
									"command" => 'create_htpasswd',
									"title" => "use setHtpasswdLine() to set user and password" 
							),
							array (
									"label" => "create authentification part in '.htaccess'",
									"command" => 'create_authentification_part_in_htaccess',
									"title" => "also create a .htpasswd file in the same directory" 
							) 
					) 
			),
			array (
					"label" => 'remove files / folders',
					'commands' => array (
							array (
									"label" => "remove Typo3 source package: 'typo3-src-{TYPO3_VERSION}.tar.gz'",
									"command" => 'remove_typo3-src_tar_gz' 
							),
							array (
									"label" => "remove Typo3 source directory: 'typo3-src-{TYPO3_VERSION}'",
									"command" => 'remove_typo3-src_directory' 
							),
							array (
									"label" => 'remove 1000 files from typo3temp/compressor',
									"command" => 'remove_files_from_typo3temp_compressor' 
							) 
					) 
			),
			array (
					"label" => 'make tar-balls',
					'commands' => array (
							array (
									"label" => 'tar -cpvlzf fileadmin_{DATE_TIME}.tar.gz fileadmin',
									"command" => 'tar_fileadmin' 
							),
							array (
									"label" => 'tar -cpvlzf uploads_{DATE_TIME}.tar.gz uploads',
									"command" => 'tar_uploads' 
							),
							array (
									"label" => 'tar -cpvlzf typo3conf_{DATE_TIME}.tar.gz typo3conf',
									"command" => 'tar_typo3conf' 
							) 
					) 
			),
			array (
					"label" => "Typo3 Administration Files",
					"commands" => array (
							array (
									"label" => "create 'typo3conf/ENABLE_INSTALL_TOOL'",
									"command" => "create_typo3conf_ENABLE_INSTALL_TOOL" 
							),
							array (
									"label" => "create 'typo3conf/LOCK_BACKEND'",
									"command" => 'create_typo3conf_LOCK_BACKEND' 
							),
							array (
									"label" => "remove 'typo3conf/LOCK_BACKEND'",
									"command" => 'remove_typo3conf_LOCK_BACKEND' 
							),
							array (
									"label" => "create 'FIRST_INSTALL'",
									"command" => 'create_FIRST_INSTALL' 
							),
							array (
									"label" => "remove 'FIRST_INSTALL'",
									"command" => 'remove_FIRST_INSTALL' 
							),
							array (
									"label" => "remake typo3temp' directory",
									"command" => 'remake_typo3temp',
									"title" => "don't forget to check permissions and files in typo3temp with the install tool" 
							) 
					) 
			),
			array (
					"label" => 'very special stuff',
					'commands' => array (
							array (
									"label" => 'exec()',
									"command" => 'exec',
									"title" => "use the additional parameter-field below" 
							) 
					) 
			),
			array (
					"label" => 'general',
					'commands' => array (
							array (
									"label" => 'list working directory',
									"command" => 'list_working_directory' 
							) 
					) 
			) 
	);
	
	/**
	 *
	 */
	public function __construct() {
	}
	
	/**
	 * Here all starts...
	 * @throws Exception
	 * @return boolean
	 */
	public function main() {
		// check security-file
		$this->checkEnableFile ();
		
		// check the configuration values
		$this->checkConfigurationVariables ();
		
		// run the password wall
		if (! $this->checkLogin ()) {
			return false;
		}
		
		// set the current date and time
		$this->dateTimeStr = date ( "Ymd-His" );
		
		// replacements for the command labels in frontend
		$this->commandTitleReplacements = array (
				"{TYPO3_VERSION}" => $this->typo_version,
				"{DATE_TIME}" => $this->dateTimeStr,
				"{HTTP_SOURCE_DOMAIN}" => $this->http_source_domain,
				"{HTTP_SOURCE_PACKAGE}" => $this->http_source_package,
				"{LOCAL_TAR_DIRECTORY}" => $this->local_tar_directory,
				"{MYSQL_IMPORT_DUMP_FILE}" => $this->mysql_import_dump_file 
		);
		
		// show Form
		echo $this->showCommandForm ();
		
		// check for commands to be executed
		if (isset ( $_POST[ 'commands' ] ) && is_array ( $_POST[ 'commands' ] )) {
			$commandsToExecute = array ();
			foreach ( $_POST[ 'commands' ] as $command ) {
				if (is_string ( $command )) {
					$commandsToExecute[] = $command;
				}
			}
			
			// execute
			if (count ( $commandsToExecute )) {
				$this->do_commands ( $commandsToExecute );
			}
		}
		
		return true;
	}
	
	/**
	 * @throws Exception
	 * @return boolean;
	 */
	protected function checkConfigurationVariables() {
		// check if password was changed in configuration
		if (! trim ( $this->password )) {
			throw new Exception ( "Configuration-Error: Please do not use the default password!" );
		} else if (strlen ( trim ( $this->password ) ) < 20) {
			throw new Exception ( "Configuration-Error: Please set a password longer or equal than 20 characters!" );
		}
		
		if ($this->useLoginSession) {
			if (! trim ( $this->encryptionKey )) {
				throw new Exception ( "Configuration-Error: If you want to use the session to store login status you have to set an encryption key!" );
			}
		}
		
		// check some other configuration values
		if (! trim ( $this->typo_version )) {
			throw new Exception ( "Configuration-Error: : typo3_version is undefined!" );
		}
		
		if (! trim ( $this->http_source_domain )) {
			throw new Exception ( "Configuration-Error: : http_source_domain is undefined!" );
		}
		
		if (! trim ( $this->http_source_package )) {
			throw new Exception ( "Configuration-Error: : http_source_package is undefined!" );
		}
		
		if (! trim ( $this->local_tar_directory )) {
			throw new Exception ( "Configuration-Error: : local_tar_directory is undefined!" );
		}
		
		if (substr ( $this->local_tar_directory, - 1 ) != '/') {
			throw new Exception ( "Configuration-Error: : Add a trailing / to local_tar_directory!" );
		}
		return true;
	}
	
	/**
	 * @return boolean
	 */
	protected function checkLogin() {
		if ($this->useLoginSession) {
			if (isset ( $_POST[ 'login_mode' ] ) && $_POST[ 'login_mode' ] === "log_off") {
				unset ( $_SESSION[ 'typo3_commands_hash' ] );
				echo $this->showPasswordForm ();
				return false;
			}
			
			if (isset ( $_SESSION[ 'typo3_commands_hash' ] ) && $_SESSION[ 'typo3_commands_hash' ] === hash_hmac ( "sha1", $this->password, $this->encyptionKey )) {
				return true;
			}
		}
		
		if (isset ( $_POST[ 'password' ] )) {
			$this->submittedPassword = trim ( ( string ) $_POST[ 'password' ] );
		} else {
			echo $this->showPasswordForm ();
			return false;
		}
		
		if ($this->submittedPassword == "") {
			echo $this->showPasswordForm ( "You have to submit a password - but not an empty one!" );
			return false;
		}
		
		if ($this->submittedPassword !== $this->password) {
			echo $this->showPasswordForm ( "The password you submitted did not match!" );
			return false;
		}
		
		// all ok
		if ($this->useLoginSession) {
			$_SESSION[ 'typo3_commands_hash' ] = hash_hmac ( "sha1", $this->submittedPassword, $this->encyptionKey );
		}
		return true;
	}
	
	/**
	 * Checks, if a file called ENABLE_TYPO3_INSTALLATION_HELPER exists.
	 * Checks, if the file is not too old.
	 * This is to automatically disable this script after some time.
	 * @throws Exception
	 */
	protected function checkEnableFile() {
		$enableFilePath = $this->getPathSite () . "ENABLE_TYPO3_INSTALLATION_HELPER";
		if (! is_file ( $enableFilePath )) { // check for file
			throw new Exception ( "This script is not enabled: make a file named 'ENABLE_TYPO3_INSTALLATION_HELPER' in the same directory as this script!" );
		} else if (filemtime ( $enableFilePath ) < time () - (60 * 60)) { // check the time window
			unlink ( $enableFilePath );
			if (isset ( $_SESSION[ 'typo3_commands_hash' ] )) {
				unset ( $_SESSION[ 'typo3_commands_hash' ] );
			}
			throw new Exception ( "The file 'ENABLE_TYPO3_INSTALLATION_HELPER' was too old (60 minutes). Generate a new one!" );
		} else { // extend the time window
			touch ( $enableFilePath );
		}
	}
	
	/**
	 * @return string The absolute path to the directory this script is in.
	 */
	protected function getPathSite() {
		return dirname ( __FILE__ ) . "/";
	}
	
	/**
	 * @return string The public URL to this script.
	 */
	protected function getSelfUrl() {
		return strtolower ( substr ( $_SERVER[ "SERVER_PROTOCOL" ], 0, strpos ( $_SERVER[ "SERVER_PROTOCOL" ], '/' ) ) ) . '://' . $_SERVER[ 'SERVER_NAME' ] . $_SERVER[ 'PHP_SELF' ];
	}
	
	/**
	 * Iterate over some commands...
	 * @param array $commands
	 */
	public function do_commands($commands) {
		foreach ( $commands as $command ) {
			try {
				$this->do_command ( $command );
			} catch ( Exception $e ) {
				$this->do_error_echo ( $e->getMessage () );
			}
		}
	}
	
	/**
	 * Central method to execute the commands.
	 * @param string $command
	 */
	public function do_command($command) {
		if (! trim ( $command )) {
			return false;
		}
		
		// show the command in frontend
		if (isset ( $this->commandsByKey[ $command ] )) {
			$this->do_echo ( str_replace ( array_keys ( $this->commandTitleReplacements ), $this->commandTitleReplacements, $this->commandsByKey[ $command ] ) );
		} else {
			$this->do_echo ( $command );
		}
		
		// decide between the commands...
		switch ($command) {
			case "get_typo3_from_sourceforge" :
				$this->do_exec ( 'wget http://prdownloads.sourceforge.net/typo3/typo3_src-' . $this->typo_version . '.tar.gz' );
				break;
			case "get_typo3_from_get_typo3_org" :
				$this->do_exec ( 'wget --content-disposition get.typo3.org/' . $this->typo_version );
				break;
			case "extract_typo3-src_tar_gz" :
				if (is_dir ( 'typo3_src-' . $this->typo_version )) {
					throw new Exception ( "ERROR on command 'extract typo3-src.tar.gz': 'typo3_src-" . $this->typo_version . "' allready exist" );
				}
				$this->do_exec_tar_extract ( "typo3_src-" . $this->typo_version . ".tar.gz" );
				break;
			case "get_website_package" :
				$this->do_exec ( 'wget ' . $this->http_source_domain . $this->http_source_package );
				break;
			
			case "extract_website_package" :
				if (is_dir ( $this->local_tar_directory )) {
					throw new Exception ( "ERROR on command 'extract website package': '" . $this->local_tar_directory . "' allready exist" );
				}
				$this->do_exec_tar_extract ( $this->http_source_package );
				break;
			case "tar_fileadmin" :
				$dirname = 'fileadmin';
				if (! is_dir ( $dirname )) {
					throw new Exception ( "ERROR on command 'tar -cpvlzf[...]': Directory '" . $dirname . "' does not exist!" );
				}
				$this->do_exec_make_tar_gz ( $dirname . '_' . $this->dateTimeStr . '.tar.gz', $dirname, "-cpvlzf" );
				break;
			case "tar_typo3conf" :
				$dirname = 'typo3conf';
				if (! is_dir ( $dirname )) {
					throw new Exception ( "ERROR on command 'tar -cpvlzf[...]': Directory '" . $dirname . "' does not exist!" );
				}
				$this->do_exec_make_tar_gz ( $dirname . '_' . $this->dateTimeStr . '.tar.gz', $dirname, "-cpvlzf" );
				break;
			case "tar_uploads" :
				$dirname = 'uploads';
				if (! is_dir ( $dirname )) {
					throw new Exception ( "ERROR on command 'tar -cpvlzf[...]': Directory '" . $dirname . "' does not exist!" );
				}
				$this->do_exec_make_tar_gz ( $dirname . '_' . $this->dateTimeStr . '.tar.gz', $dirname, "-cpvlzf" );
				break;
			case "move_files_from_package_into_working_directory" :
				if (! is_dir ( $this->local_tar_directory )) {
					throw new Exception ( "ERROR on command 'move files from package into working directory': Directory '" . $this->local_tar_directory . "' does not exist!" );
				}
				$this->do_exec ( 'mv ' . $this->local_tar_directory . '* .' );
				if (is_file ( $this->local_tar_directory . '.htaccess' )) {
					$this->do_exec ( 'mv ' . $this->local_tar_directory . '.htaccess .' );
				}
				break;
			case "remove_package_directory" :
				if ($this->local_tar_directory && is_dir ( $this->local_tar_directory ) && count ( scandir ( $this->local_tar_directory ) ) == 2) {
					$this->do_php ( 'rmdir( "' . $this->local_tar_directory . '" )' );
				}
				break;
			case "check_and_create_symlinks" :
				// typo3_src
				if (is_link ( 'typo3_src' )) {
					$error = false;
					try {
						$this->do_php_unlink ( "typo3_src" );
					} catch ( Exception $e ) {
						$this->do_error_echo ( $e->getMessage () );
						$error = true;
					}
					
					if (! $error) {
						$this->do_php ( 'symlink( "typo3_src-' . $this->typo_version . '", "typo3_src" )' );
					}
				} else {
					$this->do_php ( 'symlink( "typo3_src-' . $this->typo_version . '", "typo3_src" )' );
				}
				
				// typo3
				if (is_link ( 'typo3' )) {
					$error = false;
					try {
						$this->do_php_unlink ( "typo3" );
					} catch ( Exception $e ) {
						$this->do_error_echo ( $e->getMessage () );
						$error = true;
					}
					
					if (! $error) {
						$this->do_php ( 'symlink( "typo3_src/typo3", "typo3" )' );
					}
				} else {
					$this->do_php ( 'symlink( "typo3_src/typo3", "typo3" )' );
				}
				break;
			case "copy_index_php_from_typo3_src" :
				if (is_link ( 'index.php' )) {
					$this->do_php ( 'rename( "index.php", "index.php-symlink" )' );
				} else if (is_file ( 'index.php' )) {
					$this->do_php ( 'rename( "index.php", "index.php-' . $this->dateTimeStr . '" )' );
				}
				
				$this->do_php ( 'copy( "typo3_src/index.php", "index.php" )' );
				break;
			case "make_mysqldump" :
				$this->do_mysqldump ();
				break;
			case "import_mysqldump" :
				$this->do_mysql_import ();
				break;
			case "convert_latin1_on_utf8_to_utf8" :
				$this->do_convert_latin1_on_utf8_to_utf8 ();
				break;
			case "convert_all_tables_to_utf8_general_ci" :
				$this->do_convert_all_tables_to_utf8_general_ci ();
				break;
			case "create_htpasswd" :
				$htpasswdFile = $this->getPathSite () . ".htpasswd";
				
				if (is_file ( $htpasswdFile )) {
					$fileContents = file_get_contents ( $htpasswdFile );
					if (strpos ( $fileContents, $this->htpasswd_line ) === false) {
						file_put_contents ( $htpasswdFile, "\n" . $this->htpasswd_line, FILE_APPEND );
					}
				} else {
					file_put_contents ( $htpasswdFile, $this->htpasswd_line );
				}
				break;
			case "create_authentification_part_in_htaccess" :
				$htaccessFile = $this->getPathSite () . ".htaccess";
				$htpasswdFile = $this->getPathSite () . ".htpasswd";
				
				$htaccessAuthPart = 'AuthType Basic';
				$htaccessAuthPart .= "\n" . 'AuthName "Login"';
				$htaccessAuthPart .= "\n" . 'AuthUserFile ' . $htpasswdFile;
				$htaccessAuthPart .= "\n" . 'AuthGroupFile /dev/null';
				$htaccessAuthPart .= "\n" . 'Require valid-user';
				$htaccessAuthPart .= "\n";
				
				if (is_file ( $htaccessFile )) {
					$fileContents = file_get_contents ( $htaccessFile );
					file_put_contents ( $htaccessFile, $htaccessAuthPart . $fileContents );
				} else {
					file_put_contents ( $htaccessFile, $htaccessAuthPart );
				}
				break;
			case "list_working_directory" :
				$this->do_exec ( 'ls -lah' );
				break;
			case "remove_typo3-src_tar_gz" :
				try {
					$this->do_php_unlink ( "typo3_src-" . $this->typo_version . ".tar.gz" );
				} catch ( Exception $e ) {
					$this->do_error_echo ( $e->getMessage () );
				}
				break;
			case "remove_typo3-src_directory" :
				if (! is_dir ( "typo3_src-" . $this->typo_version )) {
					throw new Exception ( "ERROR on command 'remove typo3_src-" . $this->typo_version . " directory: directory does not exist!" );
				} else {
					try {
						$this->do_rmdir_recursive ( "typo3_src-" . $this->typo_version );
					} catch ( Exception $e ) {
						$this->do_error_echo ( $e->getMessage () );
					}
				}
				break;
			case "create_typo3conf_ENABLE_INSTALL_TOOL" :
				$this->do_exec ( 'touch typo3conf/ENABLE_INSTALL_TOOL' );
				break;
			case "create_typo3conf_LOCK_BACKEND" :
				$this->do_exec ( 'touch typo3conf/LOCK_BACKEND' );
				break;
			case "remove_typo3conf_LOCK_BACKEND" :
				if (is_file ( "typo3conf/LOCK_BACKEND" )) {
					$this->do_php_unlink ( "typo3conf/LOCK_BACKEND" );
				}
				break;
			case "create_FIRST_INSTALL" :
				$this->do_exec ( 'touch FIRST_INSTALL' );
				break;
			case "remove_FIRST_INSTALL" :
				if (is_file ( "FIRST_INSTALL" )) {
					$this->do_php_unlink ( "FIRST_INSTALL" );
				}
				break;
			case "remake_typo3temp" :
				if (! is_dir ( "typo3temp" )) {
					throw new Exception ( "ERROR on command 'remake typo3temp': 'typo3temp' does not exist!" );
				}
				$this->do_exec ( 'rm -Rf typo3temp && mkdir typo3temp && chmod 2770 typo3temp' );
				break;
			case "fix_file_permissions" :
				$this->do_exec ( 'find . -type f -exec chmod 0664 \'{}\' \;' );
				break;
			case "fix_folder_permissions" :
				$this->do_exec ( 'find . -type d -exec chmod 2775 \'{}\' \;' );
				break;
			case "remove_files_from_typo3temp_compressor" :
				$dir = $this->getPathSite () . "typo3temp" . DIRECTORY_SEPARATOR . "compressor";
				$i = 0;
				if ($h = opendir ( $dir )) {
					echo "<ol>";
					while ( ($file = readdir ( $h )) !== false ) {
						if (($file != ".") && ($file != "..")) {
							$i ++;
							if (@unlink ( $dir . DIRECTORY_SEPARATOR . $file )) {
								echo "<li>REMOVED: ";
							} else {
								echo "<li>COULD NOT BE REMOVED: ";
							}
							echo $file . "</li>";
						}
						
						if ($i == 1000) {
							break;
						}
					}
					echo "</ol>";
					
					if ($i == 0) {
						echo "Nothing to do";
						$_POST[ 'auto_re_execute' ] = 0;
					}
				} else {
					throw new Exception ( "ERROR on command 'remove_files_from_typo3temp_compressor: directory typo3temp/compressor does not exist!" );
				}
				break;
			case "exec" :
				if (! trim ( $_POST[ 'exec_parameter' ] )) {
					throw new Exception ( "The additional parameter is not set!" );
				}
				$this->do_exec ( trim ( $_POST[ 'exec_parameter' ] ) );
				break;
			default :
				throw new Exception ( "ERROR: unknown command:" . $command . "!" );
				break;
		}
	}
	
	/**
	 * Use php to unlink a file
	 * @param string $file
	 * @throws Exception
	 */
	protected function do_php_unlink($file) {
		if (is_file ( $file ) === false && is_dir ( $file ) === false && is_link ( $file ) === false) {
			throw new Exception ( "ERROR on do_php_unlink: The file, directory or link '" . $file . "' does not exist!" );
		}
		$this->do_php ( 'unlink( "' . $file . '" )' );
	}
	
	/**
	 * Extract a tar.gz
	 * @param string $tarFile
	 * @param string $parameter
	 * @throws Exception
	 */
	protected function do_exec_tar_extract($tarFile, $parameter = "-xzvf") {
		if (! is_file ( $tarFile )) {
			throw new Exception ( "ERROR on do_exec_tar_extract: The tar-ball '" . $tarFile . "' does not exist!" );
		}
		$this->do_exec ( 'tar ' . $parameter . ' ' . $tarFile );
	}
	
	/**
	 * make a tarball
	 * @param string $tarFile
	 * @param string $source
	 * @param string $parameter
	 * @throws Exception
	 */
	protected function do_exec_make_tar_gz($tarFile, $source, $parameter = "-cpvlzf") {
		if (is_file ( $tarFile )) {
			throw new Exception ( "ERROR on do_exec_make_tar_gz: The tar-ball '" . $tarFile . "' does allready exist!" );
		}
		$this->do_exec ( 'tar ' . $parameter . ' ' . $tarFile . ' ' . $source );
	}
	
	/**
	 * Remove a complete folder-tree.
	 * @param string $path
	 * @param boolean $topLevel
	 */
	protected function do_rmdir_recursive($path, $topLevel = false) {
		// remove trailing slash
		$path = rtrim ( $path, DIRECTORY_SEPARATOR );
		
		// never remove current directory or an upper directory or full pathes
		if (trim ( $path ) == "" || $path == "." || strpos ( $path, ".." ) || 0 === strpos ( $path, "/" )) {
			throw new Exception ( "ERROR on do_rmdir_recursive: path '" . $path . "' is empty or not allowed!" );
		}
		
		// check, if the given path is in allowed pathes
		if (! $topLevel) {
			$allowedPathes = array (
					"typo3_src-" 
			);
			$isInallowedPathes = false;
			foreach ( $allowedPathes as $allowedPath ) {
				if (0 === strpos ( $path, $allowedPath )) {
					$isInallowedPathes = true;
					break;
				}
			}
			if (! $isInallowedPathes) {
				throw new Exception ( "ERROR on do_rmdir_recursive: path '" . $path . "' is not in the allowed pathes!" );
			}
		}
		
		$files = array_merge ( glob ( $path . DIRECTORY_SEPARATOR . '*' ), glob ( $path . DIRECTORY_SEPARATOR . '.ht*' ), glob ( $path . DIRECTORY_SEPARATOR . '.git*' ) );
		foreach ( $files as $file ) {
			is_dir ( $file ) ? $this->do_rmdir_recursive ( $file, true ) : unlink ( $file );
		}
		rmdir ( $path );
	}
	
	/**
	 * use php's exec command.
	 * Tries to check safe_mode to use shell_exec() or exec().
	 * @param string $s
	 */
	protected function do_exec($s) {
		if (strtolower ( ini_get ( 'safe_mode' ) ) == 1) {
			echo '<span class="do_exec">' . $s . '</span><br /><pre>';
			$output = "ERROR: Can't execute because of safe_mode is on!";
		} else {
			$disabled_functions = array_map ( 'trim', explode ( ',', ini_get ( 'disable_functions' ) ) );
			
			if (function_exists ( "shell_exec" ) && in_array ( "shell_exec", $disabled_functions ) === false) {
				echo '<span class="do_exec">shell_exec(' . $s . ');</span><br /><pre>';
				$output = shell_exec ( $s );
			} else {
				if (function_exists ( "exec" ) && in_array ( "exec", $disabled_functions ) === false) {
					echo '<span class="do_exec">exec(' . $s . ');</span><br /><pre>';
					$output = exec ( $s );
				}
			}
		}
		
		echo str_replace ( array (
				'<',
				'>' 
		), array (
				'&lt;',
				'&gt;' 
		), $output );
		echo '</pre><hr />';
	}
	
	/**
	 * Evals (or evils?) php-code.
	 * @param string $cmd
	 */
	protected function do_php($cmd) {
		echo '<span class="do_php">' . $cmd . '</span><br /><pre>';
		var_dump ( eval ( 'return(' . $cmd . ');' ) );
		echo '</pre><hr />';
	}
	
	/**
	 * Simple echo output.
	 * @param string $content
	 */
	public function do_echo($content) {
		echo '<span class="do_echo">' . $content . '</span><hr />';
	}
	
	/**
	 * Simple error echo output
	 * @param string $content
	 */
	public function do_error_echo($content) {
		echo '<span class="do_error_echo">' . $content . '</span><hr />';
	}
	
	/**
	 * Basic method for a mysqldump.
	 */
	protected function do_mysqldump() {
		// load the typo3Configuration
		$this->loadTypo3Configuration ();
		
		// print_r($this->typo3Configuration);
		
		// check DB-Configuration
		$this->checkDatabasePartInTypo3Configuration ();
		
		// set the database-parameters for local use
		$this->getDatabaseConfiguration ();
		
		// build command and execute
		$this->do_exec ( $this->buildMysqldumpCommand () );
	}
	
	/**
	 * Basic method for importing a mysqldump.
	 */
	protected function do_mysql_import() {
		// load the typo3Configuration
		$this->loadTypo3Configuration ();
		
		// check DB-Configuration
		$this->checkDatabasePartInTypo3Configuration ();
		
		// set the database-parameters for local use
		$this->getDatabaseConfiguration ();
		
		// build command and execute
		$this->do_exec ( $this->buildMysqlImportCommand () );
	}
	
	/**
	 * Method to convert latin1 chars in an utf8 database to utf8.
	 */
	protected function do_convert_latin1_on_utf8_to_utf8() {
		// load the typo3Configuration
		$this->loadTypo3Configuration ();
		
		// check DB-Configuration
		$this->checkDatabasePartInTypo3Configuration ();
		
		// set the database-parameters for local use
		$this->getDatabaseConfiguration ();
		
		// connect to database
		$mysqli = new mysqli ( $this->databaseConfiguration[ 'host' ], $this->databaseConfiguration[ 'username' ], $this->databaseConfiguration[ 'password' ], $this->databaseConfiguration[ 'database' ], $this->databaseConfiguration[ 'port' ] );
		if ($mysqli->connect_errno) {
			throw new Exception ( "Failed to connect to MySQL: " . $mysqli->connect_error );
		}
		
		if (! count ( $this->convertLatin1ToUtf8Configuration )) {
			throw new Exception ( "Command 'convert latin1 characters on a utf8 table to utf8: There are no tables and fields configured." );
		}
		
		foreach ( $this->convertLatin1ToUtf8Configuration as $table => $fields ) {
			// find the tables
			$query = "SHOW COLUMNS FROM " . $table;
			$result = $mysqli->query ( $query );
			
			$existingFields = array ();
			while ( $row = $result->fetch_assoc () ) {
				$existingFields[] = $row[ 'Field' ];
			}
			
			foreach ( $fields as $field ) {
				$this->do_echo ( "Converting table '" . $table . "' field '" . $field . "' from latin1 to utf8 ..." );
				if (! in_array ( $field, $existingFields )) {
					$this->do_error_echo ( "Failed to run query: Field '" . $field . "' does not exists in table  '" . $table . "'!" );
				} else {
					$query = "UPDATE " . $table . " SET " . $field . " = CONVERT( CAST( CONVERT( " . $field . " USING latin1 ) AS BINARY ) USING utf8 ) WHERE " . $field . " <> ''";
					$result = $mysqli->query ( $query );
					if (! $result) {
						$this->do_error_echo ( "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error );
					} else {
						$this->do_echo ( "... done." );
					}
				}
			}
		}
	}
	
	/**
	 * Method to convert latin1 chars in an utf8 database to utf8.
	 */
	protected function do_convert_all_tables_to_utf8_general_ci() {
		// load the typo3Configuration
		$this->loadTypo3Configuration ();
		
		// check DB-Configuration
		$this->checkDatabasePartInTypo3Configuration ();
		
		// set the database-parameters for local use
		$this->getDatabaseConfiguration ();
		
		// connect to database
		$mysqli = new mysqli ( $this->databaseConfiguration[ 'host' ], $this->databaseConfiguration[ 'username' ], $this->databaseConfiguration[ 'password' ], $this->databaseConfiguration[ 'database' ], $this->databaseConfiguration[ 'port' ] );
		if ($mysqli->connect_errno) {
			throw new Exception ( "Failed to connect to MySQL: " . $mysqli->connect_error );
		}
		// find the tables
		$query = "SELECT CONCAT('ALTER TABLE `', tbl.`TABLE_SCHEMA`, '`.`', tbl.`TABLE_NAME`, '` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;') FROM `information_schema`.`TABLES` tbl WHERE tbl.`TABLE_SCHEMA` = '" . $this->databaseConfiguration[ 'database' ] . "' ";
		$result = $mysqli->query ( $query );
		if (! $result) {
			$this->do_error_echo ( "Failed to run query: (" . $mysqli->errno . ") " . $mysqli->error );
			return null;
		}
		
		while ( $row = $result->fetch_assoc () ) {
			$subQuery = reset ( $row );
			$subResult = $mysqli->query ( $subQuery );
			
			if (! $subResult) {
				$this->do_error_echo ( $subQuery . " ... failed to run query: (" . $mysqli->errno . ") " . $mysqli->error );
			} else {
				$this->do_echo ( $subQuery . "... done." );
			}
		}
	}
	
	/**
	 * Loads the array of the typo3 configuration file.
	 * @throws Exception
	 */
	protected function loadTypo3Configuration() {
		if (is_file ( $this->typo3_configuration_file )) {
			$this->typo3Configuration = include $this->typo3_configuration_file;
		} else {
			throw new Exception ( "Could not find the typo3Configuration-file: '" . $this->typo3_configuration_file . "'" );
		}
	}
	
	/**
	 * Checks, if the database stuff is set in typo3 configuration.
	 * @see Typo3Commands::loadTypo3Configuration() which should be called before.
	 * @throws Exception
	 */
	protected function checkDatabasePartInTypo3Configuration() {
		if (isset ( $this->typo3Configuration[ 'DB' ] ) == false || is_array ( $this->typo3Configuration[ 'DB' ] ) == false) {
			throw new Exception ( "Could not find the typo3Configuration for the database." );
		}
		
		if (isset ( $this->typo3Configuration[ 'DB' ][ 'database' ] ) == false || isset ( $this->typo3Configuration[ 'DB' ][ 'username' ] ) == false || isset ( 
				$this->typo3Configuration[ 'DB' ][ 'password' ] ) == false) {
			throw new Exception ( "Not all data is set in typo3Configuration for database-access (database, username, password)." );
		}
	}
	
	/**
	 * Build the configuration array used to connect to the database.
	 * @see Typo3Commands::loadTypo3Configuration() which should be called before.
	 * @see Typo3Commands::checkDatabasePartInTypo3Configuration() which should be called before.
	 */
	protected function getDatabaseConfiguration() {
		$this->databaseConfiguration = array (
				'database' => $this->typo3Configuration[ 'DB' ][ 'database' ],
				'username' => $this->typo3Configuration[ 'DB' ][ 'username' ],
				'password' => $this->typo3Configuration[ 'DB' ][ 'password' ],
				'host' => ($this->typo3Configuration[ 'DB' ][ 'host' ]) ? $this->typo3Configuration[ 'DB' ][ 'host' ] : 'localhost',
				'port' => ($this->typo3Configuration[ 'DB' ][ 'port' ]) ? $this->typo3Configuration[ 'DB' ][ 'port' ] : '3306' 
		);
		$this->databaseConfiguration[ 'server' ] = $this->databaseConfiguration[ 'host' ] . ":" . $this->databaseConfiguration[ 'port' ];
	}
	
	/**
	 * @return string The mysqldump command used in command line.
	 */
	protected function buildMysqldumpCommand() {
		$typo3Version = (isset ( $this->typo3Configuration[ 'SYS' ][ 'compat_version' ] )) ? $this->typo3Configuration[ 'SYS' ][ 'compat_version' ] : "X.X.X";
		$file = "mysqldump_Typo3-" . $typo3Version . "_" . $this->dateTimeStr . "_" . $this->databaseConfiguration[ 'database' ] . ".sql";
		if (getcwd ()) {
			$file = getcwd () . "/" . $file;
		}
		return $this->mysqldumpCmd . " -u " . $this->databaseConfiguration[ 'username' ] . " -p" . escapeshellarg ( $this->databaseConfiguration[ 'password' ] ) . " -h " . $this->databaseConfiguration[ 'host' ] . " --port " . $this->databaseConfiguration[ 'port' ] . " " . $this->databaseConfiguration[ 'database' ] . " -c -e --default-character-set=utf8 > " . $file;
	}
	
	/**
	 * @return string The mysql command to import a mysqldump used in command line.
	 */
	protected function buildMysqlImportCommand() {
		return $this->mysqlCmd . " -u " . $this->databaseConfiguration[ 'username' ] . " -p" . escapeshellarg ( $this->databaseConfiguration[ 'password' ] ) . " -h " . $this->databaseConfiguration[ 'host' ] . " --port " . $this->databaseConfiguration[ 'port' ] . " " . $this->databaseConfiguration[ 'database' ] . " --default-character-set=utf8 < " . $this->mysql_import_dump_file;
	}
	
	/**
	 * The form to select the commands to execute.
	 * @return string HTML
	 */
	protected function showCommandForm() {
		// try to get database information to show them and to prevent doing stuff on the wrong database
		try {
			$this->loadTypo3Configuration ();
			$this->checkDatabasePartInTypo3Configuration ();
			$this->getDatabaseConfiguration ();
			foreach ( $this->commands[ 'database' ][ 'commands' ] as &$databaseCommand ) {
				$databaseCommand[ 'label' ] = "DB " . $this->databaseConfiguration[ 'database' ] . ": " . $databaseCommand[ 'label' ];
			}
		} catch ( Exception $e ) {
			// remove database stuff from commands
			unset ( $this->commands[ 'database' ] );
		}
		
		$html = '';
		
		$html .= '<form action="' . $this->getSelfUrl () . '" method="post" name="commands_form">';
		if ($this->useLoginSession === false) {
			$html .= '<input type="hidden" name="password" value="' . $this->submittedPassword . '" />';
		}
		$html .= '<select name="commands[]" multiple="multiple">';
		
		$i = 0;
		
		$this->commandsByKey = array ();
		
		foreach ( $this->commands as $group ) {
			if (is_array ( $group ) && isset ( $group[ "label" ] ) && isset ( $group[ 'commands' ] ) && is_array ( $group[ 'commands' ] )) {
				$html .= '<optgroup label="' . htmlentities ( $group[ "label" ] ) . '">';
				
				foreach ( $group[ 'commands' ] as $command ) {
					if (is_array ( $command ) && isset ( $command[ "label" ] ) && isset ( $command[ "command" ] )) {
						$i ++;
						$html .= '<option value="' . $command[ "command" ] . '"';
						$html .= (isset ( $command[ "title" ] )) ? ' title="' . htmlentities ( $command[ "title" ] ) . '"' : '';
						$html .= (isset ( $_POST[ 'commands' ] ) && in_array ( $command[ "command" ], $_POST[ 'commands' ] )) ? ' selected="selected"' : '';
						$html .= '>[' . str_pad ( $i, 2, "0", STR_PAD_LEFT ) . '] ' . htmlentities ( 
								str_replace ( array_keys ( $this->commandTitleReplacements ), $this->commandTitleReplacements, $command[ "label" ] ) ) . '</option>';
						
						$this->commandsByKey[ $command[ "command" ] ] = '[' . str_pad ( $i, 2, "0", STR_PAD_LEFT ) . '] ' . htmlentities ( $command[ "label" ] );
					}
				}
				
				$html .= '</optgroup>';
			}
		}
		
		$html .= '</select>';
		
		$html .= '<br /><input type="text" name="exec_parameter" value="' . $_POST[ 'exec_parameter' ] . '" placeholder="additional parameter for exec()" />';
		
		$html .= '<br /><input type="checkbox" name="auto_re_execute" id="auto_re_execute" value="1" ' . (isset ( $_POST[ 'auto_re_execute' ] ) && $_POST[ 'auto_re_execute' ] === "1" ? 'checked="checked"' : '') . '" /> <label for="auto_re_execute">Execute commands automatically again in rotation?</label><br />(The script reloads itself after 10 seconds without asking, if commands should really be executed. It is useful if you, for example, want to delete typo3temp/compressor files.)';
		
		$html .= '<br /><br /><button type="submit">do it, but in your own risk!</button>';
		
		$html .= '<hr />';
		
		$html .= '</form>';
		
		$html .= <<<SCRIPT
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
(function() {
	var formObj = $("form[name=commands_form]");
	var selectObj = $("select", formObj).first();

	/**
	 * Method to show a confirmation dialog, if commands are selected in form.
	 */
	var confirmCommands = function() {
		var activeOptions = selectObj.val();
		if (!activeOptions || activeOptions.length == 0) {
			$('<div title="Nothing to do!">Select command(s) and then submit the form...</div>').dialog({
				modal : true,
				buttons : {
					"Ok" : function() {
						$(this).dialog("close");
					}
				},
				open : function() {
					// prevent showing the select scrollbar in front of the dialog
					$('select').css('overflow', 'hidden');
				},
				close : function() {
					// reset the scrollbar in select
					$('select').css('overflow', 'auto');
				}
			}).dialog("open");
			return false;
		}

		var confirmDialogObj = $('<div title="Execute these commands?"></div>');
		confirmDialogObj.append(getActiveOptionsTitleList());

		confirmDialogObj.dialog({
			modal : true,
			width : 'auto',
			buttons : {
				"Confirm" : function() {
					document.forms.commands_form.submit();
					$("body").html("... please wait for submit ...");
				},
				"Cancel" : function() {
					$(this).dialog("close");
				}
			},
			open : function() {
				// prevent showing the select scrollbar in front of the dialog
				$('select').css('overflow', 'hidden');
			},
			close : function() {
				// reset the scrollbar in select
				$('select').css('overflow', 'auto');
			}
		});
		confirmDialogObj.dialog("open");
	};

	var getActiveOptionsTitleList = function() {
		var activeOptions = selectObj.val();
		var ulObj = $('<ul class="command-list"></ul>');
		for (var v = 0; v < activeOptions.length; v++) {
			var liObj = $('<li></li>').html($("option[value=" + activeOptions[v] + "]", selectObj).html());

			if (activeOptions[v] == "exec") {
				liObj.append(": " + $("input[name=exec_parameter]").val());
			}

			ulObj.append(liObj);
		}
		return ulObj;
	};

	var autoReExecuteHelper = function() {
		var activeOptions = selectObj.val();
		if (!activeOptions || activeOptions.length == 0) {
			return;
		}
		var counter = 10;
		var t = null;
		var counterDialogObj = $('<div title="Automatic execution of commands."></div>');
		var ulObj = getActiveOptionsTitleList();
		var countdown = function() {
			counter--;
			counterDialogObj.html(counter + " seconds remaining:").append(ulObj);
			if (counter > 0) {
				t = setTimeout(countdown, 1000);
			} else {
				document.forms.commands_form.submit();
				$("body").html("... please wait for submit ...");
			}
		}

		counterDialogObj.dialog({
			modal : true,
			width : 'auto',
			buttons : {
				"Cancel" : function() {
					clearTimeout(t)
					$(this).dialog("close");
				}
			},
			open : function() {
				// prevent showing the select scrollbar in front of the dialog
				$('select').css('overflow', 'hidden');
			},
			close : function() {
				// reset the scrollbar in select
				$('select').css('overflow', 'auto');
			}
		});
		counterDialogObj.dialog("open");
		countdown();
	};

	if ($("#auto_re_execute:checked").length) {
		autoReExecuteHelper();
	}

	// bind form submit for confirmation
	formObj.submit(function(e) {
		e.preventDefault();
		confirmCommands();
	});

})($);
</script>
SCRIPT;
		
		if ($this->useLoginSession === true) { // show logout form
			$html .= $this->showLogoutForm ();
		}
		
		return $html;
	}
	
	/**
	 * The log out form.
	 * @return string HTML
	 */
	protected function showLogoutForm() {
		$html .= '<form action="' . $this->getSelfUrl () . '" method="post" name="logout_form">';
		$html .= '<button type="submit">log off</button>';
		$html .= '<input type="hidden" name="login_mode" value="log_off" />';
		$html .= '</form>';
		return $html;
	}
	
	/**
	 * The form to submit the password.
	 * @param string $message
	 * @param string $errorMessageStyle
	 * @return string HTML
	 */
	protected function showPasswordForm($message = "", $errorMessageStyle = "error") {
		$html = '';
		if ($message && $errorMessageStyle === "error") {
			$html .= $this->do_error_echo ( $message );
		} else if ($message && $errorMessageStyle === "normal") {
			$html .= $this->do_echo ( $message );
		}
		$html .= '<form action="' . $this->getSelfUrl () . '" method="post" name="password form">';
		$html .= '<label for="password">Type in the password:</label>';
		$html .= '<input id="password" type="password" name="password" value="" autofocus />';
		$html .= '<button type="submit">submit</button>';
		$html .= '</form>';
		
		return $html;
	}
}

?>
</body>
</html>
