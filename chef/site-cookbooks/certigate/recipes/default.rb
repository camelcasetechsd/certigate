include_recipe "certigate::php_extensions"

# install mysql chef gem
mysql2_chef_gem 'default' do
    client_version node['mysql']['version'] if node['mysql'] && node['mysql']['version']
    action :install
end

# install mysql server
mysql_service "default" do
    port "#{node.mysql.root_connection.port}"
    version '5.5'
    initial_root_password "#{node.mysql.root_connection.password}"
    socket "/var/run/mysqld/mysqld.sock"
    action [:create, :start]
end

# create default database
mysql_database "#{node.site.db.main.name}" do
    connection node['mysql']['root_connection']
    owner "#{node.site.db.main.username}"
    encoding  "#{node.site.db.main.encoding}"
    collation  "#{node.site.db.main.collation}"
    action :create
end

# create test database
mysql_database "#{node.site.db.test.name}" do
    connection node['mysql']['root_connection']
    owner "#{node.site.db.test.username}"
    encoding  "#{node.site.db.test.encoding}"
    collation  "#{node.site.db.test.collation}"
    action :create
end

# Initialize web app
web_app "certigate" do
    template "default.conf.erb"
    server_name "#{node.site.host}"
    docroot "#{node.site.public_path}"
    environment "#{node.site.environment}"
end

# Initialize test web app
web_app "certigateTest" do
    template "default.conf.erb"
    server_name "#{node.testSite.host}"
    docroot "#{node.testSite.public_path}"
    environment "#{node.testSite.environment}"
end

# manage some php module 
apache_module "mpm_prefork" do
    enable true
end

apache_module "mpm_event" do
    enable false
end

# add web app record to hosts file
hostsfile_entry '127.0.0.1' do
    hostname "#{node.site.host}"
    action :append
end

# add test web app record to hosts file
hostsfile_entry '127.0.0.1' do
    hostname "#{node.testSite.host}"
    action :append
end

# install bower
execute "bower" do
  command "sudo npm install -g bower"
end

# remove unneeded index.html
execute "remove index.html" do
  command "sudo rm /var/www/html/index.html -f"
end

# open cart estore
include_recipe "certigate::opencart_estore"