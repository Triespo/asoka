class php {

  package {
    "php5-fpm":ensure => installed;
    "php5-common":ensure => installed;
    "php5-curl":ensure => installed;
    "php5-cli":ensure => installed;
    "php5-mcrypt":ensure => installed;
    "php5-memcache":ensure => installed;
    "php5-gd":ensure => installed;
    "php-apc":
      ensure  => installed,
      notify  => Service["php5-fpm"];
    "php5-mysql":
      ensure  => installed,
      notify  => Service["php5-fpm"];
  }

  file { "/var/log/php":
    ensure => "directory",
  }  

  service { "php5-fpm":
    ensure  => "running",
    require => Package["php5-fpm"],
  }

}