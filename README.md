# Neural Style PHP Front End

This project aims to provide a simple way to tweak the parameters of `jcjohnson/neural-style` 
via a front-end written in PHP.  

This project was developed and tested on MacOS, as well as
Amazon EC2 CentOS instance, using Apache with PHP 7.

## Installation:

To get this running on a Mac as localhost, first enable the webserver.
[Set up localhost on macOS High Sierra - Apache, MySQL, and PHP 7](https://websitebeaver.com/set-up-localhost-on-macos-high-sierra-apache-mysql-and-php-7-with-sslhttps) (no need for MySQL or https)

Install this project into your web server's directory:

```
# on MacOS
cd ~/Sites
 

# on CentOS
cd /var/www/html
 

git clone https://github.com/kirilledelman/neural-style-php-frontend.git
 
cd neural-style-php-frontend

mkdir output; mkdir sources; mkdir styles; mkdir saved;

sudo chmod -R ugo+rwx .
```

**Torch**, should be installed into the same directory as this project, otherwise `apache` or `_www` user may not be
able to start the neural style calculation. If you already have it installed elsewhere, you need to make sure
it's possible for `apache` or `_www` user to run **`th`** command.
  
Installing torch - [Torch project](https://github.com/torch/distro):
  
```  
git clone https://github.com/torch/distro.git ./torch --recursive
cd ./torch; bash install-deps;
./install.sh
source ~/.bashrc
cd ..
 
# MacOS   
sudo chown -R _www:wheel ./torch
 
# CentOS   
sudo chown -R ec2-user:apache ./torch
sudo yum install protobuf-compiler protobuf-devel
  
luarocks install loadcaffe
```

Same goes for neural-style itself - it's easiest to install into the same directory as the front end, but
if you already have it installed, you should be able to just update `sys/config.php` with path to
your installation.

```
git clone https://github.com/jcjohnson/neural-style.git
  
cd neural-style
sh models/download_models.sh
cd ..
```

## Usage

After installation, browse to your [http://localhost/neural-style-php-frontend](http://localhost/neural-style-php-frontend). You should see 
the web interface.

## Troubleshooting

If you can't get it to work, a way to debug it would be to look at `log.txt` created in project directory after each run.
The log file will contain two sections at the beginning:
 * `<COMMAND>` - contains the actual shell command that PHP uses to start **th** with neural script, and
 * `<SETTINGS>` - contains JSON object of all the parameters received from the front end
 
These are followed by the output of running the command. If errors occur, they'll be here.

General points:  
 
* Make sure that `/output`, `/saved`, `/sources`, `/styles` subdirectories are writable.
* Make sure Apache can invoke **`th`** with neural_style.lua, if not, try installing torch and neural-style repositories inside this project's folder. 
The hardcoded paths to `torch` and to `neural_style` are in `config.php`.  
* Try running the command (from `<COMMAND>` block ) on the console yourself, see what errors you get.  

 
