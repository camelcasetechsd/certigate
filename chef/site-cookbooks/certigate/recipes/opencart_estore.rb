# create open cart database
# mysql_database "#{node.estore.db.name}" do
#     connection node['mysql']['root_connection']
#     owner "#{node.estore.db.username}"
#     encoding  "#{node.estore.db.encoding}"
#     collation  "#{node.estore.db.collation}"
#     action :create
# end

# moving estore to the same path as certigate
# Initialize web app
# web_app "certigate_estore" do
#     template "default.conf.erb"
#     server_name "#{node.estore.host}"
#     docroot "#{node.estore.public_path}"
#     environment "#{node.estore.environment}"
# end

# add record to /etc/hosts file
# hostsfile_entry '127.0.0.1' do
#    hostname "#{node.estore.host}"
#    action :append
# end
