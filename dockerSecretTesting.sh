#!/bin/bash
# Secret rm & set for DEV (use dos2unix.exe dockerSecret.sh if needed)
# open a bash window first

mkdir /run/secrets
ls -al /run/secrets

printf Portal > /run/secrets/PORTAL_APP_NAME -
printf local > /run/secrets/PORTAL_APP_ENV -
printf true > /run/secrets/PORTAL_APP_DEBUG -
printf https://laravel-project.local > /run/secrets/PORTAL_APP_URL -
printf base64:cClpWF3mFC0t9xdbS/06L9ND3Fq5EL4J0ZroUefFlOE= > /run/secrets/PORTAL_APP_KEY -
printf https://adfs.uottawaqa.ca/sortie-exit > /run/secrets/PORTAL_ADFS_LOGOUT -
printf mysql > /run/secrets/PORTAL_DB_HOST -
printf Local > /run/secrets/PORTAL_IDP_NAME -
printf eVQKRErqbstRIywvHC0TQuglhM42yK18 > /run/secrets/PORTAL_REDIS_PASS -

printf template > /run/secrets/PORTAL_TEMPLATE_DB_DATABASE -
printf default > /run/secrets/PORTAL_TEMPLATE_DB_USERNAME -
printf secret > /run/secrets/PORTAL_TEMPLATE_DB_PASSWORD -
printf template > /run/secrets/PORTAL_TEMPLATE_DB_MIGRATION_USERNAME -
printf default > /run/secrets/PORTAL_TEMPLATE_DB_MIGRATION_PASSWORD -
printf template > /run/secrets/PORTAL_TEMPLATE_APP_DIR -
 
printf "your teams webhook" > /run/secrets/PORTAL_TEMPLATE_LOG_TEAMS -
printf "your slack webhook" > /run/secrets/PORTAL_LOG_SLACK_WEBHOOK_URL -

ls -al /run/secrets