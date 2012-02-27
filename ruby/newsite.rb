#!/usr/bin/env ruby

require 'fileutils'
require 'socket'
require_relative 'sudome.rb'

abort "Development URL and path must be provided" if ARGV.count != 2
abort "Must be run as root" if !is_root

host = Socket.gethostname

if host == 'server'
	CONFIG_DIR = "/home/matt/www/conf/"
	IP = "69.195.198.179"
else
	CONFIG_DIR = "/home/matt/www/config/"
	IP = '127.0.0.1'
end

APACHE_CONF_DIR = "/etc/apache2/sites-available"
HOSTS = '/etc/hosts'

url = ARGV[0]
path = File.expand_path(ARGV[1])

abort "Path not correct" if !Dir.exists?(path)

basename = url + '.conf'
outfile = CONFIG_DIR + basename

if !File.exists?(outfile)
	conf = File.open("#{CONFIG_DIR}template.conf", 'r') { |f| f.read }
	new = conf.gsub('URL', url).gsub('PATH', path)
	File.open(outfile, 'w') { |f| f.write(new) }
	FileUtils.chown('matt', 'matt', outfile)
else
	puts "#{outfile} already exists. Will use this one"
end

apache_conf = APACHE_CONF_DIR + '/' + basename
File.symlink(outfile, apache_conf)

system("a2ensite #{basename}")

File.open(HOSTS, 'a') { |f| f.puts "#{IP}\t#{url}" }

system("service apache2 restart")
