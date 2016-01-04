# Certigate Application

### Installation
```
$ git clone git@github.com:camelcasetechsd/certigate.git
$ cd certigate 
$ vagrant up # this will take some time 
$ vagrant ssh 
$ cd /vagrant 
$ ./deploy.sh
```
Add this line `10.10.10.49 local-certigate.com` to your /etc/hosts file then open http://local-certigate.com

### Update documentation after having code changes:
    bin/apigen
