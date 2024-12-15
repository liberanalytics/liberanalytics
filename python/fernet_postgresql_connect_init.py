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

    # Generate symmetric encryption key, save to file, & instantiate fernet.
    key = fernet.generate_key()
    finstance = fernet(key)    
    
    # Get the database host.
    db_host = input('Please enter database host:')
    config_dir = os.environ['HOME'] + '/' + 'conn' + '/' + db_host

    # Get the database name.
    db_name = input('Please enter database name:')

    # Configuration & key files.
    config_file = config_dir + '/' + db_name + '.ini'
    key_file = config_dir + '/' + db_name + '.key'        

    # Create the config dir if not yet created.
    if not os.path.isdir(config_dir):

        # Create the conn dir if not exists.
        config_parent_dir = os.path.split(config_dir)[0]
        if not os.path.isdir(config_parent_dir):
            os.mkdir(config_parent_dir)

        # Create config dir.
        os.mkdir(config_dir)

    # Write the fernet key.
    with open(key_file, 'wb') as f:
        f.write(key)

    # Prompt for the connection params.
    print('Please provide connection parameters for database host ' + db_host + ', database ' + db_name + ' : ')

    # Store connection params as dict containing tuples.
    db_props =  {

        0: ( 'USER', 'wb',  finstance.encrypt(bytes(getpass('Please enter database user name:'), 'utf-8')) ),
        1: ( 'AUTH', 'wb',  finstance.encrypt(bytes(getpass('Please enter user password:'), 'utf-8') ))
        
    }

    # Write connection params in sequence.
    with open(config_file, 'wb') as f:
        for prop in db_props:
            f.write(db_props[prop][2] + b'\n')

    # chmod on key and ini files to make unreadable/unwritable.
    os.chmod(config_file, 000); os.chmod(key_file, 000)


if __name__ == '__main__':
    main()