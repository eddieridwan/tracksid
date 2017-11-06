<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_users extends CI_Migration
{
  public function __construct()
	{
    parent::__construct();
		$this->load->dbforge();
    $this->load->database();
	}

	public function up()
	{
    $query = "
      CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `username` varchar(255) NOT NULL DEFAULT '',
        `email` varchar(255) NOT NULL DEFAULT '',
        `password` varchar(255) NOT NULL DEFAULT '',
        `avatar` varchar(255) DEFAULT 'default.jpg',
        `created_at` datetime NOT NULL,
        `updated_at` datetime DEFAULT NULL,
        `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
        `is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
        `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      );
    ";
    $this->db->query($query);
    $query = "
      CREATE TABLE IF NOT EXISTS `ci_sessions` (
        `id` varchar(40) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        PRIMARY KEY (id),
        KEY `ci_sessions_timestamp` (`timestamp`)
      );
    ";
    $this->db->query($query);
  }

	public function down()
	{
    $this->dbforge->drop_table('users',TRUE);
    $this->dbforge->drop_table('ci_sessions',TRUE);
  }

}
/* End of file '20171106060720_create_users' */
/* Location: .//Users/eddie/projects/personal/vagrant-php-box/sites/html/tracksid/application/migrations/20171106060720_create_users.php */
