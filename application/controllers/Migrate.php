<?php

/*
  http://avenir.ro/the-migrations-in-codeigniter-or-how-to-have-a-git-for-your-database/
*/

class Migrate extends CI_Controller
{

  public function index()
  {
    echo 'Hello';
  }

  public function do_migration($version = NULL)
  {
    $this->load->library('migration');

    if(isset($version) && ($this->migration->version($version) === FALSE))
    {
      show_error($this->migration->error_string());
    }

    elseif(is_null($version) && $this->migration->latest() === FALSE)
    {
      show_error($this->migration->error_string());
    }

    else
    {
      echo 'The migration has concluded successfully.';
    }
  }

  public function undo_migration($version = NULL)
  {
    $this->load->library('migration');
    $migrations = $this->migration->find_migrations();
    $migration_keys = array();
    foreach($migrations as $key => $migration)
    {
      $migration_keys[] = $key;
    }
    if(isset($version) && array_key_exists($version,$migrations) && $this->migration->version($version))
    {
      echo 'The migration was reset to the version: '.$version;
      exit;
    }
    elseif(isset($version) && !array_key_exists($version,$migrations))
    {
      echo 'The migration with version number '.$version.' doesn\'t exist.';
    }
    else
    {
      $penultimate = (sizeof($migration_keys)==1) ? 0 : $migration_keys[sizeof($migration_keys) - 2];
      if($this->migration->version($penultimate))
      {
        echo 'The migration has been rolled back successfully.';
        exit;
      }
      else
      {
        echo 'Couldn\'t roll back the migration.';
        exit;
      }
    }
  }

  public function reset_migration()
  {
    $this->load->library('migration');
    if($this->migration->current()!== FALSE)
    {
      echo 'The migration was reset to the version set in the config file.';
      return TRUE;
    }
    else
    {
      echo 'Couldn\'t reset migration.';
      show_error($this->migration->error_string());
      exit;
    }
  }
}