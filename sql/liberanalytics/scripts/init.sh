#!/bin/bash

set -uo pipefail

DB_HOST="3.135.113.112"
DB_USER="liberanalytics"
DB_NAME="postgres"
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