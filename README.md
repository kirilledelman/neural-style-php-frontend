

cd neural-style-frontend

git clone https://github.com/torch/distro.git ./torch --recursive
cd ./torch; bash install-deps;
./install.sh
source ~/.bashrc
cd ..
sudo chown -R ec2-user:apache ./torch

luarocks install loadcaffe
sudo yum install protobuf-compiler protobuf-devel

git clone https://github.com/jcjohnson/neural-style.git
sudo chown -R ec2-user:apache ./neural-style
cd neural-style
sh models/download_models.sh
cd ..


