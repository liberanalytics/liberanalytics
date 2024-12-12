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
import psycopg2


def main():

    db_host = '3.144.105.190'
    db_name = 'demo'

    # Make config & key files readable.
    config_dir = os.environ['HOME'] + '/' + 'conn' + '/' + db_host
    config_file = config_dir + '/' + db_name + '.ini'
    key_file = config_dir + '/' + db_name + '.key'
    os.chmod(config_file, 440); os.chmod(key_file, 440)

    # Get the encryption key, reset perms on key file.
    with open(key_file, 'rb') as f:
        encrypt_key = f.read()
        os.chmod(key_file, 000)

    # Instantiate a fernet instance.
    finstance = fernet(encrypt_key)

    # Get the connection params from config file and decrypt the binary data with the key.
    with open(config_file, 'rb') as f:

        iter = 0
        for ln in f:          

            if iter == 0:
                db_user = bytes(finstance.decrypt(ln)).decode('utf-8')
                iter += 1

            else:
                db_auth = bytes(finstance.decrypt(ln)).decode('utf-8')
    
    # Reset key file perms.
    os.chmod(key_file, 000)

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

    for row in rs:
        print(row)

    cur.close()
    conn.close()


if __name__ == '__main__':
    main()