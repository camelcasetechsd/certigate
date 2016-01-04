# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  
    config.vm.box = "ubuntu/trusty64"
    config.vm.box_check_update = false
    config.vm.network "private_network", ip: "10.10.10.49"
    config.vm.synced_folder "./", "/var/www/html", id: "web-root" , owner: "www-data", group: "www-data", mount_options: ["dmode=775,fmode=664"]
    
    ## virtualbox configuration
    config.vm.provider "virtualbox" do |vb|
        vb.customize ["modifyvm", :id, "--name", "certigate-pro"]
        vb.customize ["modifyvm", :id, "--memory", "1024"]
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
    end
    
    ## chef provision
    config.omnibus.chef_version = '12.6.0'
    config.librarian_chef.cheffile_dir = 'chef'
    config.vm.provision 'chef_solo' do |chef|
        chef.log_level = :info
        chef.cookbooks_path = ['chef/cookbooks', 'chef/site-cookbooks']
        chef.roles_path = "chef/roles"
        chef.add_role("web")
    end
end
