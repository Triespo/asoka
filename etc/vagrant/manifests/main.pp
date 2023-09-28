node www {

  exec { 'apt-update':
    command => '/usr/bin/apt-get update'
  }
  Exec["apt-update"] -> Package <| |>

  package {
    "curl":ensure => installed;
    "libssl-dev":ensure => installed;
    "git-core":ensure => installed;
    "build-essential":ensure => installed;
    "memcached":ensure => installed;
  }

  service { "memcached":
    ensure  => "running",
    require => Package["memcached"],
  }

  include mysql
  include nginx
  include php
}
