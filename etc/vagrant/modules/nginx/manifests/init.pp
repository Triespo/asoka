class nginx {

  package {
    "nginx":ensure => installed;
  }

  file { '/var/www':
    ensure  => 'link',
    target  => '/vagrant',
    force   => true,     
    replace => true,
  }

  file { "/etc/nginx/sites-available/default":
    ensure  => 'link',
    force   => true,
    replace => true,
    source => "/vagrant/etc/vagrant/modules/nginx/files/default",
    require => Package["nginx"],
    notify  => Service["nginx"],
  }

  file { "/etc/nginx/nginx.conf":
    ensure  => 'link',
    force   => true,
    replace => true,
    source => "/vagrant/etc/vagrant/modules/nginx/files/nginx.conf",
    require => Package["nginx"],
    notify  => Service["nginx"],
  }

  service { "nginx":
    ensure  => "running",
    require => Package["nginx"],
  }
  
}