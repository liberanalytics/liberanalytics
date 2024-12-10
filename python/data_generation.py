# ----------------------------------------------------------------------------------------------------------------
# data_generation.py
# 
#
# (C) 2022 Nathan Rose, Dayton, Ohio
# Released under GNU Public License (GPL)
# email nwrose13@gmail.com
# ----------------------------------------------------------------------------------------------------------------

from calendar import calendar
import pandas
import random
import string
import numpy
import os
from datetime import datetime, timedelta
import boto3

def main():

    """"""

    #
    vars()

    #
    generate_dataset(100)

    #
    upload_to_s3()



def vars():

    #
    vars.data_file_name = "random_data.csv"
    vars.data_file =  os.environ["HOME"] + "/metl/sample_data/" + vars.data_file_name


def generate_dataset(num_rows) -> None:

    """"""

    # Generate a set of random ints.
    int_set = numpy.random.randint(0, 100, size=(num_rows, 1))

    # List placeholders.
    int_key = []
    str_list_lower = []
    str_list_mixed = []
    dates = []

    # Date data for generation of random dates.
    cur_date = datetime.date(datetime.now())
    boy_date = datetime.date(datetime.strptime(str(cur_date.year) + "-01-01", "%Y-%m-%d"))

    # Loop through number of rows appending dicts with random strings.
    for i in range(num_rows):

        # Surrogate key.
        int_key.append(i + 1)

        # Random strings.
        str_lower = create_random_string(string.ascii_lowercase, 8)
        str_mixed = create_random_string(string.ascii_letters, 16)
        str_list_lower.append(str_lower)
        str_list_mixed.append(str_mixed)

        # Calculate an arbitrary date in current year.
        day_diff = int(random.choice(numpy.arange(0, (cur_date - boy_date).days, 1)))
        dt = datetime.strftime(cur_date - timedelta(days=day_diff), "%Y-%m-%d")
        dates.append(dt)

    # Create series of the dicts.
    int_series = pandas.Series(int_key, name='pk')
    dt_series = pandas.Series(dates, name='r_dt')
    str_lower_series = pandas.Series(str_list_lower, name='r_lower')
    str_mixed_series = pandas.Series(str_list_mixed, name='r_mixed')

    # Concate the series along the same axis, i.e. axis 1 to correspond to columns.
    df_data = pandas.concat([int_series, dt_series, str_lower_series, str_mixed_series], axis=1)
    df = pandas.DataFrame(df_data)
    
    # Create a csv file of data frame.    
    df.to_csv(
        path_or_buf=vars.data_file,
        index=False,
        header=False        
    )


def upload_to_s3() -> None:
    
    """"""

    #
    aws_sess = boto3.Session(profile_name='default')
    s3_client = aws_sess.client('s3')
    s3_client.upload_file(vars.data_file, 'dataaretedata', vars.data_file_name)

def create_random_string(chars, str_len) -> str:

    """"""

    #
    return ''.join(random.choice(chars) for x in range(str_len))  

if __name__ == '__main__':
    main()