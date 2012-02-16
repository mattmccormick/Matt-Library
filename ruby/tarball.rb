#!/usr/bin/env ruby

dir = File.expand_path(ARGV[0])
parent = File.dirname(dir)
base = File.basename(dir)
system("tar -C #{parent} -zcvf #{dir}.tar.gz #{base}")
