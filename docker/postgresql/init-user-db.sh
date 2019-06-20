#!/bin/bash
set -e

# create temp file
echo "[i] Creating temp file: $tfile"
tfile=`mktemp`
if [ ! -f "$tfile" ]; then
    return 1
fi

# if [ -z $POSTGRES_DB ]; then # POSTGRES_DB is unset or set to an empty string
#     POSTGRES_DB="lumen_api"
#     echo "[i] Creating DB: $POSTGRES_DB"
#     echo "CREATE DATABASE $POSTGRES_DB;" >> $tfile
# fi

if [ "$POSTGRES_DB" != "" ] && [ "$POSTGRES_SCHEMA" != "" ] && [ "$POSTGRES_SCHEMA" != "public" ]; then
    echo "[i] Creating schema: $POSTGRES_SCHEMA for DB: $POSTGRES_DB"
    echo "\connect $POSTGRES_DB;" > $tfile
    echo "CREATE SCHEMA IF NOT EXISTS $POSTGRES_SCHEMA;" >> $tfile
    echo "SET search_path TO "$POSTGRES_SCHEMA";" >> $tfile
fi

echo "[i] Running tempfile: $tfile"
/usr/local/bin/psql -v ON_ERROR_STOP=1 --username postgres < $tfile

echo "[i] Removing tempfile: $tfile"
rm -f $tfile
