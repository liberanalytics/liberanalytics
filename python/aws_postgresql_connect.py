# ----------------------------------------------------------------------------------------------------------------
# aws_postgresql_connect.py
# 
# Connects to an AWS EC2 hosted postgresql database by reading
# and decrypting credentials stored in a config file. Dependent
# on the aws_postgresql_config.py having been run first.
# ----------------------------------------------------------------------------------------------------------------

import pwd
import sys
import os
import base64
import json
import boto3
import psycopg2

def main():

    config_file = os.getenv('LA_CONFIG_FILE')
    with open(config_file, 'r') as file:
        config = json.load(file)

    # Instantiate an AWS KMS client instance.
    aws_kms = boto3.client('kms', config['aws']['account']['region']['default'])

    # KMS key id.
    the_key = config['aws']['kms']['key_desc']['liberanalytics_db']  

    db_config_dir = os.getenv(config['liberanalytics']['config']['env']['dir'])
    db_conn_file = db_config_dir + config['data']['database']['liberanalytics']['connection_file']      

    # Make db connection file readable.
    os.chmod(db_conn_file, 440)

    # Get the connection credentials from config file.
    with open(db_conn_file, 'rb') as file:
        credentials_bytes = file.read()

    # Reset config file perms.
    os.chmod(db_conn_file, 000)        
    
    # Decrypt the connection credentials.
    credentials_decrypt = aws_kms.decrypt(
        CiphertextBlob = credentials_bytes,
        KeyId = f'{the_key}',
        EncryptionAlgorithm='SYMMETRIC_DEFAULT'
    )

    # Decode the credentials and split by delimiter.
    delim = config['data']['construct']['delim']['config_delim']
    credentials_decode = base64.b64decode(credentials_decrypt['Plaintext'])
    credentials = bytes.decode(credentials_decode, 'utf-8')
    db_data = credentials.split(delim)
    db_host = db_data[0]
    db_name = db_data[1]
    db_user = db_data[2]
    db_auth = db_data[3]

    # Form a connection string & connect.
    connection_string = \
    " host=" + db_host + \
    " dbname=" + db_name + \
    " user=" + db_user + \
    " password=" + db_auth + \
    " port=5432"
    conn = psycopg2.connect(connection_string)

    # Example query.
    cur = conn.cursor()
    cur.execute('SELECT CURRENT_DATE;')
    rs = cur.fetchall()

    # Iterate through resultset.
    for row in rs:
        print(row)

    # Close cursor and connection.
    cur.close()
    conn.close()


if __name__ == '__main__':
    main()