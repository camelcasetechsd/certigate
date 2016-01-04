# Certigate Application

### Clone repo and provision with vagrant:

    git clone git@github.com:camelcasetechsd/certigate.git
    cd certigate
    vagrant up --provision
    vagrant plugin install vagrant-librarian-chef ## In case you do not have librarian chef installed "Unknown configuration section 'librarian_chef'" 

### Add the IP to your hosts file:

    10.10.10.49     local-certigate.com

### Update documentation after having code changes:
    bin/apigen
