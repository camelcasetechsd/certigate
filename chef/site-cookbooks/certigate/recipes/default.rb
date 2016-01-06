include_recipe "certigate::php_extensions"

mysql2_chef_gem 'default' do
    client_version node['mysql']['version'] if node['mysql'] && node['mysql']['version']
    action :install
end

mysql_service "#{node.db.name}" do
    port '3306'
    version '5.5'
    initial_root_password "#{node.mysql.root_connection.password}"
    action [:create, :start]
end

mysql_database "certigate" do
    connection node['mysql']['root_connection']
    owner "#{node.db.username}"
    encoding  'utf8'
    collation  'utf8_general_ci'
    action :create
end

# Initialize web app
web_app "certigate" do
    template "default.conf.erb"
    server_name "#{node.site.host}"
    docroot "#{node.site.public_path}"
    environment "#{node.site.environment}"
end

# manage some php module 
apache_module "mpm_prefork" do
    enable true
end

apache_module "mpm_event" do
    enable false
end

hostsfile_entry '127.0.0.1' do
    hostname "#{node.site.host}"
    action :append
end

execute "bower" do
  command "sudo npm install -g bower"
end

execute "remove index.html" do
  command "sudo rm /var/www/html/index.html -f"
end