To install:
===========

1. In CiviCRM set up custom field on contact for City Council District.
2. In the code Find and replace 7 with custom field number (can use api explorer to find custom field #) where needed (saveDistrict, deleteDistrict, and in validateForm functions in austincitylimits.php and Geo.php)
3. Install Geos see command line instructions below for more information see http://www.saintsjd.com/2014/06/05/howto-intsall-libgeos-with-php5-bindings-ubuntu-trusty-14.04.html

```
sudo apt-get install -y apache2 php5 libapache2-mod-php5 php5-dev phpunit

wget http://download.osgeo.org/geos/geos-3.4.2.tar.bz2
tar -xjvf geos-3.4.2.tar.bz2
cd geos-3.4.2/
./configure --enable-php
make
sudo make install

sudo -su root

cat > /etc/php5/mods-available/geos.ini << EOF
; configuration for php geos module
; priority=50
extension=geos.so
EOF

sudo php5enmod geos
sudo service apache2 restart

# note: you might need to run ldconfig here if you see permissions issues. I don't see it on my setup, but users in the comments have
ldconfig

```
4. Enable extension
