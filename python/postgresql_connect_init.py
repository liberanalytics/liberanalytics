# ----------------------------------------------------------------------------------------------------------------
# postgresql_connect_init.py
# 
#
# ----------------------------------------------------------------------------------------------------------------

import pwd
import sys
import os
from getpass import getpass
from cryptography.fernet import Fernet as fernet


def main():
    
    # Get the database host.
    db_host = input('Please enter database host:')
    config_dir = os.environ['HOME'] + '/' + 'postgresql_' + db_host + '/'

    # Get the database name.
    db_name = input('Please enter database name:')

    # Configuration & key files.
    config_file = config_dir + db_name + '.ini'
    key_file = os.environ['HOME'] + '/' + db_name + '.key'

    # Check if the database configureation file already exists...
    if os.path.isfile(config_file):

        # Check if user wants to change connection params.
        change_params = input('Do you want to change any existing connection parameters? yes/no')

        # ^ connection parameters if yes.
        
    # 
    else:

        # Create the config directory if not yet created.
        if not os.path.isdir(config_dir):
            os.mkdir(config_dir)

        # Generate symmetric encryption key, save to file, & instantiate fernet.
        key = fernet.generate_key()
        finstance = fernet(key)
        with open(key_file, 'wb') as f:
            f.write(key)

        # Prompt for the connection parameters.
        print('Configuration parameters for database host ' + db_host + ' don''t exist. Please provide parameters:')

        # Store connection parameters as dictionary containing tuples.
        db_props =  {

            0: ( 'USER', 'wb',  finstance.encrypt(bytes(getpass('Please enter database user name:'), 'utf-8')) ),
            1: ( 'AUTH', 'wb',  finstance.encrypt(bytes(getpass('Please enter user password:'), 'utf-8') ))
            
        }

        with open(config_file, 'wb') as f:
            for prop in db_props:
                f.write(db_props[prop][2] + b'\n')

        # ^ chmod on key and ini files to make unreadable/unwritable.



if __name__ == '__main__':
    main()