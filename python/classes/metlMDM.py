# ----------------------------------------------------------------------------------------------------------------
# metlMDM.py
# Class for handling metl data pipeline instruction set execution.
#
# (C) 2022 Nathan Rose, Dayton, Ohio
# Released under GNU Public License (GPL)
# email nwrose13@gmail.com
# ----------------------------------------------------------------------------------------------------------------

import psycopg2 as psql
from classes.metlIni import metlIni

""" Class for handling metl data pipeline instruction set execution. """
class metlMDM(metlIni):

    def __init__(self) -> None:

        """"""

        #
        super().__init__('mdm', True)
        