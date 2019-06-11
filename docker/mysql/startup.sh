#!/bin/sh

if [ ! -d /run/mysqld ]; then
    mkdir -p /run/mysqld
    chown -R mysql:mysql /run/mysqld
fi

if [ "$MYSQL_ROOT_PASSWORD" = "" ]; then
    MYSQL_ROOT_PASSWORD=root
    echo "[i] MySQL root Password: $MYSQL_ROOT_PASSWORD"
fi

# Only if these variables are provided as environment variables they will be created in the DB:
MYSQL_DATABASE=${MYSQL_DATABASE:-""}
MYSQL_USER=${MYSQL_USER:-""}
MYSQL_PASSWORD=${MYSQL_PASSWORD:-""}

if [ -d /var/lib/mysql/mysql ]; then
    echo '[i] MySQL directory already present, skipping creation'
else
    echo "[i] MySQL data directory not found, creating initial DBs"

    # init database
    echo 'Initializing database'
    mysql_install_db --defaults-file="/etc/my.cnf" --user=mysql > /dev/null
    echo 'Database initialized'

    # create temp file
    tfile=`mktemp`
    if [ ! -f "$tfile" ]; then
        return 1
    fi

    # save sql
    echo "[i] Create temp file: $tfile"
    cat << EOF > $tfile
        USE mysql;
        FLUSH PRIVILEGES;
        GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY "$MYSQL_ROOT_PASSWORD" WITH GRANT OPTION;
        GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
        UPDATE user SET password=PASSWORD("") WHERE user='root' AND host='localhost';
EOF

    # Create new database
    if [ "$MYSQL_DATABASE" != "" ]; then
        echo "[i] Creating database: $MYSQL_DATABASE"
        echo "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DATABASE\` CHARACTER SET utf8 COLLATE utf8_general_ci;" >> $tfile

        # set new User and Password
        if [ "$MYSQL_USER" != "" ] && [ "$MYSQL_PASSWORD" != "" ]; then
        echo "[i] Creating user: $MYSQL_USER with password $MYSQL_PASSWORD"
        echo "GRANT ALL ON \`$MYSQL_DATABASE\`.* to '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD';" >> $tfile
        fi
    else
        # don`t need to create new database,Set new User to control all database.
        if [ "$MYSQL_USER" != "" ] && [ "$MYSQL_PASSWORD" != "" ]; then
        echo "[i] Creating user: $MYSQL_USER with password $MYSQL_PASSWORD"
        echo "GRANT ALL ON *.* to '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD';" >> $tfile
        fi
    fi

    echo 'FLUSH PRIVILEGES;' >> $tfile

    # run sql in tempfile
    echo "[i] run tempfile: $tfile"
    /usr/bin/mysqld --user=mysql --bootstrap --verbose=0 < $tfile
    rm -f $tfile

fi

# echo "Starting mysqld service"
exec /usr/bin/mysqld --user=mysql --console
