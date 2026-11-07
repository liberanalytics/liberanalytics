#!/bin/bash

set -uo pipefail

DB_HOST="YOUR_DB_HOST"
DB_USER="YOUR_DB_USER"
DB_NAME="YOUR_DB_NAME"
DB_FILE="init.sql"

if psql \
    --host="$DB_HOST" \
    --username="$DB_USER" \
    --dbname="$DB_NAME" \
    --file="$DB_FILE"
then
    echo "Deployment completed successfully."
else
    PSQL_EXIT_CODE=$?
    echo "Deployment failed. psql exit code: $PSQL_EXIT_CODE"
    exit "$PSQL_EXIT_CODE"
fi