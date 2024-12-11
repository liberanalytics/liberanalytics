# ----------------------------------------------------------------------------------------------------------------
# postgresql_connect.py
# 
#
# ----------------------------------------------------------------------------------------------------------------


import pwd
import sys
import os
from getpass import getpass
from cryptography.fernet import Fernet as fernet
import psycopg2 as psql


def main():
    
    # Get the database host.
    db_host = getpass('Please enter database host:')
    config_dir = os.environ['HOME'] + '/' + 'postgresql_' + db_host + '/'

    # Check if the directory exists for the host.
    if os.path.isdir(config_dir):
        print(os.environ['HOME'])

    # Check if user wants to change connection params.
    #change_params = input('Do you want to change any existing connection parameters?')
        
    # Configuration directory doesn't exist; create new connection config params.
    else:
        os.mkdir(config_dir)

        # Generate a key and instantiate fernet.
        key = fernet.generate_key()
        finstance = fernet(key)

        # Prompt for the connection parameters.
        print('Configuration parameters for database host ' + db_host + ' don''t exist. Please provide parameters:')

        # Store connection parameters as dictionary containing tuples.
        db_props =  {

            0: ( 'USER', 'wb',  input('Please enter database user name:') ),
            1: ( 'AUTH', 'a',  getpass('Please enter user''s password:') ),
            2: ( 'DB', 'a',  input('Please enter database name') ),
            
        }

        '''
        4: ('F', 'a', self.fernet_file, self.config_file),
        5: ('K', 'a', self.key_file, self.config_file),
        6: ('', 'wb', key, self.fernet_file),
        7: ('', 'wb', fkey.encrypt(bytes(getpass(self.ini_type + ' Config: Please enter password: '), 'utf-8')), self.key_file)
        '''

        # Create the connection configuration file.
        with open(config_dir + db_props[2][2]) as f:


if __name__ == '__main__':
    main()