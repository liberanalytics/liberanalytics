# ----------------------------------------------------------------------------------------------------------------
# aws_postgresql_config.py
#
# Creates a .ini file containing encrypted credentials used
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
from classes import AwsCWLogs

def main():

    with open('config/aws_la_config.json', 'r') as file:
        aws_config = json.load(file)

    # Instantiate an AWS KMS client instance.
    aws_kms = boto3.client('kms', aws_config['account']['region'])

    # KMS key id.
    the_key = aws_config['kms']['key desc']['demo']    


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
        db_host = getpass('Please enter database host:')
        db_name = getpass('Please enter database name:')
        db_user = getpass('Please enter database user name:')
        db_auth = getpass('Please enter user password:')

        # Delete the config file if exists.
        if os.path.isfile(aws_config['database']['config file']['demo']):
            os.chmod(aws_config['database']['config file']['demo'], 660)
            os.remove(aws_config['database']['config file']['demo'])

        # Concat + delimit params, convert to bytes, then base64 encode.
        delim = aws_config['construct']['config delim']
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
        with open(aws_config['database']['config file']['demo'], 'wb') as file:
            file.write(encryption_data['CiphertextBlob'])

        # Reset file perms.
        os.chmod(aws_config['database']['config file']['demo'], 000)            


    except Exception as e:
        print(e)
    
if __name__ == '__main__':
    main()