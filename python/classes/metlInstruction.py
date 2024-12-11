# ----------------------------------------------------------------------------------------------------------------
# metlInstruction.py
# Class for handling metl data pipeline instruction set retrieval from metl db.
#
# (C) 2022 Nathan Rose, Dayton, Ohio
# Released under GNU Public License (GPL)
# email nwrose13@gmail.com
# ----------------------------------------------------------------------------------------------------------------

import psycopg2 as psql
from classes.metlIni import metlIni

""" Class for connecting & executing commands retrieved from METL database. """
class metlInstruction(metlIni):

    def __init__(self) -> None:

        """"""

        # 
        super().__init__('metl', write_new_config=False)

        # Connect to metl database.
        metl = psql.connect(self.conn_string)
        