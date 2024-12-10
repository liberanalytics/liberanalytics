import sys
from itertools import filterfalse
from os import path
import os

# Remove non dirs & non files.
sys.path = list(filter(lambda x: path.isdir(x) or path.isfile(x), sys.path))

# Add the project classes directory to the sys.path.
sys.path.append(os.environ["HOME"] + "/" + "metl/metl_py/classes")

# Force the order of sys.path to place ~ dirs first.
sys.path = sorted(sys.path, key=lambda x: x.startswith('/usr'), reverse=False)

