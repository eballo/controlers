#!/bin/bash
# Valores de configuracion de frecuencia
#



seguir=false
    while ! $seguir
    do
    echo ""
    echo "Indica la Frecuencia con la que desea realizar los backups"
	echo -e "(0) Diaria\n (1) Semanal \n(2) Mensual\n"
	read frec
    
    test 2 -ge "$frec" 2>/dev/null
    if [ $? -eq 0 ]
    then
        seguir=true
    else
        echo "Introduce un valor numerico y dentro de los valores del menu"
        seguir=false
    fi
    done
    
    case "$frec" in
    0)
		echo "Indica la hora en formato hh:mm"
		read hora
		h=`echo $hora | grep "[^0-9:]" | wc -l`
		if [ $h -eq 0 ]
		then
			hora=`date -d $hora +%H:%M 2>/dev/null`
			if [ $? -eq 0 ]
			then
				echo $hora
			else
				echo "Hora erronea"
			fi
		else
			echo "Formato de hora Erroneo"
		fi
	;;
	esac
