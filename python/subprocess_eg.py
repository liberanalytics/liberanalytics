# ----------------------------------------------------------------------------------------------------------------
# subprocess_eg.py
# 
#
# (C) 2022 Nathan Rose, Dayton, Ohio
# Released under GNU Public License (GPL)
# email nwrose13@gmail.com
# ----------------------------------------------------------------------------------------------------------------

import sys
import subprocess

def main():
    
    """"""

    # Open a child process with stdout piped.
    netstat = subprocess.Popen(("netstat", "-a"), stdout=subprocess.PIPE)

    # Pipe stdout of netstat to stdin of grep searching for default postgres service port. If check_output command
    # is successful the string returned will be print. Otherwise CalledProcessError exception is raised which
    # will contain the non-zero return code.
    try:
        grep = subprocess.check_output(("grep", "65535"), stdin=netstat.stdout)
        print(str(grep))
    except subprocess.CalledProcessError as pe:
        print("error code: ", pe.returncode, pe.output)

if __name__ == '__main__':
    main()