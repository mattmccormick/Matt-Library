# A simple Ruby method which checks if the current script
# is running as root, and if not, re-invokes itself by
# using the sudo command.

def sudome
  if is_root
    exec("sudo #{ENV['_']} #{ARGV.join(' ')}")
  end
end

def is_root
	return ENV['USER'] == "root"
end
