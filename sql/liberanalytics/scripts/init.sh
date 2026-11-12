#!/bin/bash

set -uo pipefail

DB_HOST="${DB_HOST:?DB_HOST must be set}"
DB_USER="${DB_USER:?DB_USER must be set}"
DB_NAME="${DB_NAME:?DB_NAME must be set}"
DB_FILE="${DB_FILE:-init.sql}"

if psql \
    --host="$DB_HOST" \
    --username="$DB_USER" \
    --dbname="$DB_NAME" \
    --file="$DB_FILE" \
    --set=ON_ERROR_STOP=1
then
    echo "Deployment completed successfully."
else
    PSQL_EXIT_CODE=$?
    echo "Deployment failed. psql exit code: $PSQL_EXIT_CODE"
    exit "$PSQL_EXIT_CODE"
fi