#!/usr/bin/env bash
set -eu -o pipefail

# These commands run after replit clones the repo.  They install the 'mbstring'
# and 'curl' extensions to PHP, sets PHPRC to an appropriate .ini file to
# enable them, and installs composer to ~/bin.

# After boot, use 'build/helper' to reset the PHPRC and PATH appropriately

ROOTDIR=$(realpath $(dirname "$0")/..)
echo Installing dependencies from $ROOTDIR
cd $ROOTDIR
chmod a+rx $ROOTDIR/build/helper

# Show configuration progress with a temporary directory we set up.
mkdir -p .INSTALLING_1_of_4

cd /home/runner
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
mkdir -p /home/runner/bin
php composer-setup.php --install-dir=/home/runner/bin --filename=composer
php -r "unlink('composer-setup.php');"
cd $ROOTDIR

mkdir -p .INSTALLING_2_of_4

install-pkg php-mbstring

mkdir -p .INSTALLING_3_of_4

install-pkg php-curl

mkdir -p .INSTALLING_4_of_4

export PHPRC=$ROOTDIR/build/replit.ini
export PATH=$PATH:/home/runner/bin:$ROOTDIR/vendor/bin

/home/runner/bin/composer update

rmdir .INSTALLING_1_of_4
rmdir .INSTALLING_2_of_4
rmdir .INSTALLING_3_of_4
rmdir .INSTALLING_4_of_4
