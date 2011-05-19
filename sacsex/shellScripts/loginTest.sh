#!/bin/bash

echo -e "\nUser:"
read user

echo -e "\nPassword:"
read pass

passMd5=`links -source "http://172.20.1.67:8888/sacsex/services/service.md5convert.php?text=$pass"`

return=`links -source "http://172.20.1.67:8888/sacsex/services/service.auth.php?user=$user&pass=$passMd5&install=true"`

id=`echo $return | cut -f2 -d"/"`
vreturn=`echo $return | cut -f1 -d"/"`

if [ "$vreturn" -eq 3 ]
then
        echo "Autenticacion erronea!!!"
else
        echo "Welcome usuario $user con $id "
fi
