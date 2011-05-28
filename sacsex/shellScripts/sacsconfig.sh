#!/bin/bash
# Programa: sacsconfig.sh
# Autores: Giorgio y Cristina
# Fecha de creación: durante credito de sintesis   
# Descripcion:
#  Script para configurar la aplicación crontab del usuario
#  Permite establecer la frecuencia con la se realizara la copia de Backups a Servidor
# 

function validarHora (){
    # Pide una hora en formato hh:mm para validar
    # retorna: 0 -> true
    #          1 -> false
    
    horaValida=1
    hora=`zenity --entry --text="Indica la hora en formato hh:mm" --title="Hora"`
    h=`echo $hora | grep "[^0-9:]" | wc -l`
    if [ $h -eq 0 ]
    then
        hora=`date -d $hora +%H:%M 2>/dev/null`
        if [ $? -eq 0 ]
        then
            horaValida=0
        else
            zenity --error --text="Hora erronea"
        fi
    else
        zenity --error --text="Formato de hora Erroneo"
    fi
    return $horaValida
}

function validarDia (){
    # Pide un dia de la semana [ 0 - 6 ] (Domingo = 0)
    # retorna: 0 -> true
    #          1 -> false
    
    diaValido=1
    dia=`zenity --list --title="Semama" --text="Indica dia de la semana " --column="Opc" --column="Dia" 0 Domingo 1 Lunes 2 Martes 3 Miércoles 4 Jueves 5 Viernes 6 Sábado`
    d=`echo $dia | grep "^[0-6]" | wc -l`

    if [ $d -eq 1 ]
    then
        test "$dia" -gt 6 -o "$dia" -lt 0 2>/dev/null
        if [ $? -eq 0 ]
        then
            zenity --error --text="Dia erronea"
        else
            diaValido=0
        fi
    else
        zenity --error --text="Formato de dia Erroneo"
    fi
    return $diaValido
}

function validarMes (){
    # Pide opcion: 0 -> para inicio de mes
    #              1 -> para final del mes
    # retorna: 0 -> true
    #          1 -> false
    
    mesValido=1
    echo "Indica opcion para el mes"
    echo -e "  (0) Inicio del mes\n  (1) Quincena del mes\n  (2) Final del mes"
    read mes

    m=`echo $mes | grep "^[0-2]" | wc -l`

    if [ $m -eq 1 ]
    then
        test "$mes" -gt 2 -o "$mes" -lt 0 2>/dev/null
        if [ $? -eq 0 ]
        then
            zenity --error --text="Opcion para mes es erroneo"
        else
            mesValido=0
            if [ "$mes" == 0 ]
            then
                mes=1
            elif [ "$mes" == 1 ]
            then
                mes=15
            else
                mes=28
            fi
        fi
    else
        zenity --error --text="Formato de mes erroneo"
    fi
    return $mesValido
}

# MAIN

# Ruta de fichero para editar crontab
cron="/tmp/cron_$USER"

# Ruta del directorio Backup de cliente
dirBackup="$HOME/Backup"

# Ruta directorio destino del usuario en Servidor
# Si fuese por SCP seria poner el comando a la hora de guardar en fichero
dirServidor="$HOME/Escritorio"

seguir=false

while ! $seguir
do
    echo ""
    echo "Indica la Frecuencia con la que desea realizar los backups"
    echo -e " (0) Diaria\n (1) Semanal \n (2) Mensual\n"
    read frec

    test "$frec" -gt 2 -o "$frec" -lt 0 2>/dev/null
    if [ $? -eq 0 ]
    then
        echo "Introduce un valor numerico y dentro de los valores del menu"
        seguir=false
    else
        seguir=true
    fi
done

    case "$frec" in
    0)
        if validarHora
        then
            h=`echo $hora | cut -d: -f1`
            m=`echo $hora | cut -d: -f2`
            
            # Guardado de orden crontab en fichero
            echo "# Copia de contenido Backup a Servidor a la(s) $hora todos los dias" > $cron
            echo "$m $h * * * cp -R $dirBackup $dirServidor" >> $cron
            # Guardado de contenido de fichero en crontab
            crontab $cron
        fi
    ;;
    1)
        if validarDia
        then
            if validarHora
            then
                h=`echo $hora | cut -d: -f1`
                m=`echo $hora | cut -d: -f2`
                
                # Guardado de orden crontab en fichero
                echo "# Copia de contenido Backup a Servidor a la(s) $hora en dia de la semana ($dia)" > $cron
                echo "$m $h * * $dia cp -R $dirBackup $dirServidor" >> $cron
                # Guardado de contenido de fichero en crontab
                crontab $cron
            fi
        fi
    ;;
    2)
        if validarMes
        then
            # Guardado de orden crontab en fichero
            if [ "$mes" -eq 0 ]
            then
                echo "# Copia de contenido Backup a Servidor (inicio de mes)" > $cron
                echo "* * 1 * * cp -R $dirBackup $dirServidor" >> $cron
            elif [ "$mes" -eq 1 ]
            then
                echo "# Copia de contenido Backup a Servidor (quincena de mes)" > $cron
                echo "* * 15 * * cp -R $dirBackup $dirServidor" >> $cron
            else
                echo "# Copia de contenido Backup a Servidor (fin de mes)" > $cron
                echo "* * 28 * * cp -R $dirBackup $dirServidor" >> $cron
            fi
            # Guardado de contenido de fichero en crontab
            crontab $cron
        fi
    ;;
    esac
