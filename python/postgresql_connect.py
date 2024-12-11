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

    db_host = 'test'
    db_name = 'test'

    # Get the encryption key.
    with open(os.environ['HOME'] + '/' + db_name + '.key', 'rb') as key_file:
        encrypt_key = key_file.read()

    finstance = fernet(encrypt_key)

    with open(os.environ['HOME'] + '/' + 'postgresql_' + db_host + '/' + db_name + '.ini', 'rb') as f:

        iter = 0
        for ln in f:          

            if iter == 0:
                db_user = bytes(finstance.decrypt(ln)).decode('utf-8')
                iter += 1

            else:
                db_auth = bytes(finstance.decrypt(ln)).decode('utf-8')


if __name__ == '__main__':
    main()