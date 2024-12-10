# ----------------------------------------------------------------------------------------------------------------
# metl_ini.py
# Creates database initialization files and sets database connection properties
# based on initialization files.
#
# (C) 2022 Nathan Rose, Dayton, Ohio
# Released under GNU Public License (GPL)
# email nwrose13@gmail.com
# ----------------------------------------------------------------------------------------------------------------

from asyncore import write
from genericpath import isfile
import pwd
from subprocess import call
import sys
import os
from getpass import getpass
from cryptography.fernet import Fernet as fernet

class metlIni(object):

    def __init__(self, ini_type, write_new_config = False) -> None:

        """"""

        # Set initialization type, project directory, config file delimiter & line terminator.
        self.ini_type = ini_type
        self.project_dir = os.environ["HOME"] + "/" + "metl"
        self.file_delim = " : "
        self.line_term = "\n"

        # Set configuration file locations.
        self.config_file = "/" + self.ini_type + "_config" + "/" + self.ini_type + ".ini"
        self.fernet_file = "/" + self.ini_type + "_config" + "/" + self.ini_type + ".fernet"
        self.key_file = "/" + self.ini_type + "_config" + "/" + self.ini_type + ".key"

        # Set new connection properties; otherwise get the connection properties.
        if write_new_config:
            self.set_config_file_properties()
        else:
            self.get_config_file_properties()
      

    @property
    def ini_type(self):
        return self._ini_type

    @ini_type.setter
    def ini_type(self, value):
        self._ini_type = value

    @property
    def project_dir(self):
        return self._project_dir

    @project_dir.setter
    def project_dir(self, value):
        self._project_dir = value

    @property
    def config_file(self):
        return self._config_file

    @config_file.setter
    def config_file(self, value):
        self._config_file = self.project_dir + value

    @property
    def fernet_file(self):
        return self._fernet_file

    @fernet_file.setter
    def fernet_file(self, value):
        self._fernet_file = self.project_dir + value

    @property
    def key_file(self):
        return self._key_file

    @key_file.setter
    def key_file(self, value):
        self._key_file = self.project_dir + value

    @property
    def file_delim(self):
        return self._file_delim

    @file_delim.setter
    def file_delim(self, value):
        self._file_delim = value

    @property
    def line_term(self):
        return self._line_term

    @line_term.setter
    def line_term(self, value):
        self._line_term = value

    @property
    def db_host(self):
        return self._db_host

    @db_host.setter
    def db_host(self, value):
        self._db_host = value

    @property
    def db(self):
        return self._db

    @db.setter
    def db(self, value):
        self._db = value 

    @property
    def user(self):
        return self._user

    @db.setter
    def user(self, value):
        self._user = value

    @property
    def hostport(self):
        return self._hostport

    @hostport.setter
    def hostport(self, value):
        self._hostport = value

    @property
    def conn_string(self):
        return self._conn_string

    @conn_string.setter
    def conn_string(self, value):
        self._conn_string = value                               


    def set_config_file_properties(self):

        """"""

        # Make sure initialization type is metl or mdm - otherwise exit providing an error message.
        if self.ini_type not in ['metl', 'mdm']:
            sys.exit(self.ini_type + " is not one of: metl, mdm.")

        # Remove config file if it already exists.
        if os.path.isfile(self.config_file):
            os.remove(self.config_file) 

        # Create the project directory if does not exist.
        config_dir = os.path.dirname(self.config_file)
        if not os.path.isdir(config_dir):
            os.mkdir(config_dir, 0o700)   

        # Generate a key and instantiate fernet.
        key = fernet.generate_key()
        fkey = fernet(key)

        # Set of properties to write as dictionary w/ tup1les.
        props_key_map =  {
            0: ('H', 'a', input(self.ini_type + " Config: Please enter database host name : "), self.config_file),
            1: ('D', 'a', input(self.ini_type + " Config: Please enter database name : "), self.config_file),
            2: ('P', 'a', input(self.ini_type + " Config: Please enter database port : "), self.config_file),
            3: ('U', 'a', input(self.ini_type + " Config: Please enter database user : "), self.config_file),
            4: ('F', 'a', self.fernet_file, self.config_file),
            5: ('K', 'a', self.key_file, self.config_file),
            6: ('', 'wb', key, self.fernet_file),
            7: ('', 'wb', fkey.encrypt(bytes(getpass(self.ini_type + ' Config: Please enter password: '), 'utf-8')), self.key_file)
        }

        # Iterate through properties and write properties to file.
        for prop in props_key_map:

            # Individual properties.
            file_to_write = props_key_map[prop][3]
            prop_to_write = props_key_map[prop][2]
            mode_to_write = props_key_map[prop][1]
            propkey_to_write = props_key_map[prop][0]        
            
            # Open target file to write to.
            with open(file_to_write, mode_to_write) as f:

                # If mode is append write key value pairs.
                if mode_to_write == 'a':
                    f.write(propkey_to_write + self.file_delim + prop_to_write + "\n")

                # If mode is binary just write the property value.               
                elif mode_to_write == 'wb':
                    f.write(prop_to_write)
           

    def get_config_file_properties(self):

        """"""

        # Make sure config file exists - otherwise exit providing an error message.
        if not os.path.isfile(self.config_file):
            sys.exit(self.config_file + " does not exist.")

        # Encryption keys and binary auth.
        key = ''
        fkey = ''
        bin_auth = ''

        # Define metl db connection properties according to keys.
        metl_db_props = {
            "H": "db_host",
            "D": "db",
            "P": "hostport",
            "U": "user"
        }

        # Define binary files according to keys.
        metl_db_binary_files = {
            "F": "fernet",
            "K": "key"
        }

        # Open config file.
        with open(self.config_file, 'r') as config_file:

            # Extract each line from config file storing the keys & values that will be used
            # in connecting to metl instruction database.
            for ln in config_file:

                # Store the key value pairs from the config file.
                ln_delim_pos = ln.find(self.file_delim)
                ln_key = ln[:ln_delim_pos]
                ln_value_start_pos = len(ln_key) + len(self.file_delim)
                ln_value = ln[ln_value_start_pos:].replace(self.line_term, '')

                # If line key is a connection prop that isn't the password set the class attr.
                if ln_key in metl_db_props:

                    # Set connection prop class attr.
                    setattr(self, metl_db_props[ln_key], ln_value)

                # If line key is a binary file retrieve the data as binary from file.
                elif ln_key in metl_db_binary_files:

                    # Make sure file exists.
                    if isfile(ln_value):

                        # Open the binary file and read file contents.
                        with open(ln_value, 'rb') as binary_file:
                            binary_value = binary_file.read()

                        # Store respective key & auth values.
                        if ln_key == "F":
                            key = binary_value
                        elif ln_key == "K":
                            bin_auth = binary_value

            # Create fernet object, decrypt auth, then convert auth to readable utf8 encoded string.
            key = fernet(key)
            fkey = (key.decrypt(bin_auth))
            self.auth = bytes(fkey).decode('utf-8')

            # Create connection string.
            self.conn_string = "host=" + self.db_host + " dbname=" + self.db + " user=" + self.user + " password=" + self.auth + " port=" + self.hostport

        
        
        