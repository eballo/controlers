#!/bin/bash
#
# Script de Desinstalación de Sacsex
#
# Debe eliminar la carpeta de sacsexBkps (donde esta la configuracion en propierties)
# Notificar a la bd que ha sido desinstalado
# quitar la entrada en el crontab del usuario
#

linksOk=`whereis links | grep bin`
if [ ! "$linksOk" ]
then
	echo -e "Error: Es imprescindible tener instalado el paquete de links, para realizar las conexiones con el servidor. \n Para obtenerlo, utilice la orden:\n\n sudo apt-get install links"
	exit 1
else
	zenity 2>"/tmp/sacs.zenityck" 
	cat /tmp/sacs.zenityck | grep "open display" >/dev/null
	res=$?
	if [ $res -eq 1 ]
	then
		zenityOk=`whereis zenity | grep bin`
	fi
	
	if [ "$zenityOk" ]
	then
		zenity --question --title="Desinstalacion SACSEX" --text="Se va a proceder a la desinstalacion de la aplicacion\n\n Esta seguro de querer desinstalar?" 2>/dev/null
		uninstall=$?
	else
		echo "Se va a proceder a la desinstalacion de la aplicacion\n\n Esta seguro de querer desinstalar? (0 para aceptar)"
		read uninstall
	fi
	if [ "$uninstall" == "0" ]
	then
		SACSEXHOME="$HOME/.sacsexBckps"
		propiertiesFile="$SACSEXHOME/sacsex.properties"
		if [ -f $propiertiesFile ]
		then
			for elem in `cat $propiertiesFile`
			do
				if [ `echo $elem | grep "SACS_SVR_IP=" | wc -l` -eq 1 ]
				then
					SVR_CONN=`echo $elem | cut -d'=' -f2`
				elif [ `echo $elem | grep "SACS_USER=" | wc -l` -eq 1 ]
				then
					user=`echo $elem | cut -d'=' -f2`
				elif [ `echo $elem | grep "SACS_PASS=" | wc -l` -eq 1 ]
				then
					pwd5md=`echo $elem | cut -d'=' -f2`
				elif [ `echo $elem | grep "SACS_LOGIN=" | wc -l` -eq 1 ]
				then
					sshLogin=`echo $elem | cut -d'=' -f2`
				fi
			done
			rm -R "$SACSEXHOME"
			if [ $? -eq 0 ]
			then
				res=`links -dump "http://$SVR_CONN/sacsex/services/service.uninstall.php?user=$user&pass=$pwd5md"`
				newCron="/tmp/cron_$USER"
				if [ `echo $res | cut -d":" -f1` -eq 0 ]
				then
					crontab -l | grep -v sacsex > $newCron
					crontab "$newCron" 2>/dev/null
					if [ $? -eq 0 ]
					then
						if [ "$zenityOk" ] 
						then
							zenity --info --title="Desinstalacion Completa" --text="Se ha completado la desinstalacion con éxito" 2>/dev/null
						else
							echo "Se ha completado la desinstalacion con éxito"
						fi
					else
						if [ "$zenityOk" ] 
						then
							zenity --warning --title="Desinstalacion incompleta" --text="Fallo al eliminar la entrada en crontab.\n\n Consulte con el administrador para que lo haga manualmente" 2>/dev/null
						else
							echo -e "Fallo al eliminar la entrada en crontab.\n\n Consulte con el administrador para que lo haga manualmente"
						fi					
					fi
				else
					motivo=`echo $res | cut -d":" -f2`
					if [ "$zenityOk" ] 
					then
						zenity --warning --title="Desinstalacion no Completada" --text="No se ha podido completar la desinstalacion con éxito. \n $motivo" 2>/dev/null
					else
						echo -e "No se ha podido completar la desinstalacion con éxito. \n\n $motivo"
					fi
				fi
			else
				if [ "$zenityOk" ] 
				then
					zenity --warning --title="Desinstalacion no Completada" --text="No se ha podido completar la desinstalacion con éxito. \n Fallo al eliminar $SACSEXHOME" 2>/dev/null
				else
					echo -e "No se ha podido completar la desinstalacion con éxito. \n Fallo al eliminar $SACSEXHOME"
				fi
			fi
		else
			if [ "$zenityOk" ] 
			then
				zenity --warning --title="Desinstalacion no Completada" --text="No se ha podido completar la desinstalacion con éxito. \n No se encuentra la carpeta $SACSEXHOME" 2>/dev/null
			else
				echo -e "No se ha podido completar la desinstalacion con éxito. \n No se encuentra la carpeta $SACSEXHOME"
			fi
		fi
	else
		if [ "$zenityOk" ] 
		then
			zenity --warning --title="Desinstalacion Cancelada" --text="Ha sido cancelada la desinstalacion" 2>/dev/null
		else
			echo "Ha sido cancelada la desinstalacion"
		fi
	fi
fi
