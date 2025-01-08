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
import traceback
import base64
import boto3
from classes import AwsCWLogs

def main():

    # Instantiate an AWS CloudWatch instance to log any errors.
    try:
        aws_cw_logs = AwsCWLogs.AwsCWLogs('sdk', 'python', 10)
    except Exception as e:
        print(traceback.format_exc())
        exit

    '''
    Wrap section in try / except logging any errors to Cloudwatch logs.

        1. Instantiate aws kms client.
        2. Find the kms key to use for encrypting the data.
        3. Get the connection params.
        4. Encrypt and write the params to the config file.
        5. Reset file perms on config file.
    '''

    try:

        # Instantiate an AWS KMS client instance.
        aws_kms = boto3.client('kms', 'us-east-2')

        # Get the keys in the AWS account.
        keys = aws_kms.list_keys()

        # See if the desired kms key exists / is enabled; if not, create a new symmetric key / enable key.
        for key in keys['Keys']:

            # Get the key properties for the key.
            key_props = aws_kms.describe_key(KeyId=key['KeyId'])

            # Check if the correct key and that it is enabled.
            if (key_props['KeyMetadata']['Description'] == 'la_site_db_test'):
                the_key = key_props['KeyMetadata']['KeyId']
                if (key_props['KeyMetadata']['KeyState'] != 'Enabled'):
                    aws_kms.enable_key(KeyId=the_key)

        
        # Prompt for the connection params.
        print('Please provide connection parameters for database:' + '\n')
        config_file = input('Please enter config file name:')
        db_host = getpass('Please enter database host:')
        db_name = getpass('Please enter database name:')
        db_user = getpass('Please enter database user name:')
        db_auth = getpass('Please enter user password:')
        

        # Config file.
        config_dir = os.environ['LA'] + '/config'
        config_file = config_dir + '/' + config_file

        # Delete the config file if exists.
        if os.path.isfile(config_file):
            os.chmod(config_file, 660)
            os.remove(config_file)

        # Concat + delimit params, convert to bytes, then base64 encode.
        params = db_host + chr(7) + db_name + chr(7) + db_user + chr(7) + db_auth
        byte_params= params.encode('utf-8')
        encode_params = base64.b64encode(byte_params)

        # Encrypt the params.
        encryption_data = aws_kms.encrypt(
            KeyId = the_key,
            Plaintext = encode_params,
            EncryptionAlgorithm = 'SYMMETRIC_DEFAULT'
        )

        # Write the encryption params as binary to the config file.
        with open(config_file, 'wb') as file:
            file.write(encryption_data['CiphertextBlob'])

        # Reset file perms.
        os.chmod(config_file, 000)            

    except Exception as e:
        aws_cw_logs.logger.error(traceback.format_exc())

    print('done')

    
if __name__ == '__main__':
    main()