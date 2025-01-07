# ----------------------------------------------------------------------------------------------------------------
# aws_postgresql_config.py
#
# Creates a .ini file containing encrypted credentials used
# to connect to an AWS EC2 hosted postgresql database.
# ----------------------------------------------------------------------------------------------------------------

# Import required modules.
import pwd
import sys
import os
from getpass import getpass
import boto3
import logging
import watchtower
import traceback
import base64

def main():

    # Instantiate cloudwatch client.
    aws_cw = boto3.client('logs', region_name='us-east-2')

    # Create the logger.
    logger = logging.getLogger("TheLogger")

    # Set logging level - initially set to DEBUG while developing program. ^ to ERROR post successful testing.
    logger.setLevel(logging.DEBUG)

    # Create the cloudwatch log handler.
    cw_handler = watchtower.CloudWatchLogHandler(
        log_group="sdk",
        stream_name="python",
        create_log_group=True
    )

    # Attach cw handler to the logger.
    logger.addHandler(cw_handler)

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
                key_id = key_props['KeyMetadata']['KeyId']
                if (key_props['KeyMetadata']['KeyState'] != 'Enabled'):
                    aws_kms.enable_key(KeyId=key_id)

        '''
        # Prompt for the connection params.
        print('Please provide connection parameters for database:' + '\n')
        config_file = input('Please enter config file name:')
        db_host = getpass('Please enter database host:')
        db_name = getpass('Please enter database name:')
        db_user = getpass('Please enter database user name:')
        db_auth = getpass('Please enter user password:')
        '''

        # Config file.
        config_dir = os.environ['LA'] + '/config'
        config_file = config_dir + '/' + 'la_site_db_test.db.ini'

        str = 'demo' + chr(7) + 'demo'

        byte_str = str.encode('utf-8')
        encode_str = base64.b64encode(byte_str)

        encryption_data = aws_kms.encrypt(
            KeyId = f'{key_id}',
            Plaintext = encode_str,
            EncryptionAlgorithm = 'SYMMETRIC_DEFAULT'
        )

        ct = encryption_data['CiphertextBlob']

        with open(config_file, 'wb') as file:
            file.write(ct)

    except Exception as e:
        logger.error(traceback.format_exc())

    print('done')

    
if __name__ == '__main__':
    main()