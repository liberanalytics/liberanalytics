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

    # Get the encryption key.
    with open(os.environ['HOME'] + '/' + db_name + '.key', 'rb') as key_file:
        encrypt_key = key_file.read()

    # Instantiate a fernet instance.
    finstance = fernet(encrypt_key)


    # Get the connection params from config file and decrypt the binary data with the key.
    with open(os.environ['HOME'] + '/' + 'postgresql_' + db_host + '/' + db_name + '.ini', 'rb') as f:

        iter = 0
        for ln in f:          

            if iter == 0:
                db_user = bytes(finstance.decrypt(ln)).decode('utf-8')
                iter += 1

            else:
                db_auth = bytes(finstance.decrypt(ln)).decode('utf-8')

    connection_string = \
    " host=" + db_host + \
    " dbname=" + db_name + \
    " user=" + db_user + \
    " password=" + db_auth + \
    " port=5432"

    # print(connection_string)
    conn = psycopg2.connect(connection_string)

    cur = conn.cursor()
    cur.execute('SELECT CURRENT_DATE;')
    rs = cur.fetchall()

    for row in rs:
        print(row)

    cur.close()

    conn.close()


if __name__ == '__main__':
    main()