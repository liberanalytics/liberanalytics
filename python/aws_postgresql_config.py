# ----------------------------------------------------------------------------------------------------------------
# aws_postgresql_config.py
#
# Creates a binary .config file containing encrypted credentials used
# to connect to an AWS EC2 hosted postgresql database.
# ----------------------------------------------------------------------------------------------------------------

import pwd
import sys
import os
from getpass import getpass
import json
import traceback
import base64
import boto3

def main():

    config_file = os.getenv('LA_CONFIG_FILE')
    with open(config_file, 'r') as file:
        config = json.load(file)

    # AWS profile, region, KMS key id.
    aws_profile = 'default'
    aws_profile_user = config['aws']['account']['profile'][aws_profile]
    aws_region = config['aws']['account']['region']['default']
    the_key = config['aws']['kms']['key_desc'][aws_profile_user]         

    # Instantiate an AWS KMS client instance.
    # aws_kms = boto3.client('kms', config['aws']['account']['region']['default'])
    aws_kms_session = boto3.session.Session(profile_name=aws_profile_user)
    aws_kms = aws_kms_session.client('kms', region_name=aws_region)

    '''
    Wrap section in try / except logging any errors to Cloudwatch logs.

        1. Instantiate aws kms client.
        2. Find the kms key to use for encrypting the data.
        3. Get the connection params.
        4. Encrypt and write the params to the config file.
        5. Reset file perms on config file.
    '''

    try:
        
        # Prompt for the connection params.
        print('Please provide connection parameters for database:' + '\n')
        db_host = input('Please enter database host:')
        db_name = input('Please enter database name:')
        db_user = getpass('Please enter database user name:')
        db_auth = getpass('Please enter user password:')

        # Delete the config file if exists.
        db_config_dir = os.getenv(config['liberanalytics']['config']['env']['dir'])
        db_conn_file = db_config_dir + config['data']['database']['liberanalytics']['conn_file']        
        if os.path.isfile(db_conn_file):
            os.chmod(db_conn_file, 660)
            os.remove(db_conn_file)

        # Concat + delimit params, convert to bytes, then base64 encode.
        delim = config['data']['database']['liberanalytics']['delim']
        params = db_host + delim + db_name + delim + db_user + delim + db_auth
        byte_params= params.encode('utf-8')
        encode_params = base64.b64encode(byte_params)

        # Encrypt the params.
        encryption_data = aws_kms.encrypt(
            KeyId = the_key,
            Plaintext = encode_params,
            EncryptionAlgorithm = 'SYMMETRIC_DEFAULT'
        )

        # Write the encryption params as binary to the config file.
        with open(db_conn_file, 'wb') as file:
            file.write(encryption_data['CiphertextBlob'])

        # Reset file perms.
        os.chmod(db_conn_file, 000)      

    except Exception as e:
        print(e)
    
if __name__ == '__main__':
    main()