To Set up the extension:
-----------------------
1. In CiviCRM set up custom field on the contact (for type individual) to store the City Council District.
2. In the code Find and replace 59 with the id of the custom field you just created where needed (saveDistrict, deleteDistrict, and in validateForm functions in austincitylimits.php and Geo.php)
3. Set up Geos on the server (see instructions below)
4. enable the extension

### To install Geos

Install Geos see command line instructions below for more information see http://www.saintsjd.com/2014/06/05/howto-intsall-libgeos-with-php5-bindings-ubuntu-trusty-14.04.html

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

### To Run the Script to update pre-exsisting contacts

1. Go to Scheduled jobs
2. Run the Call Austincitylimits.Citycouncildistrict API (Always) job
