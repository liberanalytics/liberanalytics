# ----------------------------------------------------------------------------------------------------------------
# metl.py
# Orchestrates program flow of data pipeline instruction set retrieval
# from metl database and corresponding execution versus mdm database.
#
# (C) 2022 Nathan Rose, Dayton, Ohio
# Released under GNU Public License (GPL)
# email nwrose13@gmail.com
# ----------------------------------------------------------------------------------------------------------------

import classes.metlInstruction as metlInstr
import classes.metlMDM as metlMDM
import sys

def main():

    """"""

    # metl instr & metl mdm.
    metl_instr = metlInstr.metlInstruction()
    #metl_mdm = metlMDM.metlMDM()


if __name__ == '__main__':
    main()