# ----------------------------------------------------------------------------------------------------------------
# AwsCWLogs.py
#
# 
# 
# ----------------------------------------------------------------------------------------------------------------

import boto3
import logging
import watchtower

class AwsCWLogs:

    def __init__(self, log_group, log_stream, logging_level):

        self.log_group = log_group
        self.log_stream = log_stream
        self.logging_level = logging_level

        # Create the logger.
        self.logger = logging.getLogger("TheLogger")

        # Instantiate cloudwatch client.
        aws_cw = boto3.client('logs', region_name='us-east-2')

        # Set logging level - initially set to DEBUG while developing program. ^ to ERROR post successful testing.
        self.logger.setLevel(self.logging_level)

        # Create the cloudwatch log handler.
        cw_handler = watchtower.CloudWatchLogHandler(
            log_group=self.log_group,
            stream_name=self.log_stream,
            create_log_group=True
        )

        # Attach cw handler to the logger.
        self.logger.addHandler(cw_handler)

    @property
    def log_group(self):
        return self._log_group

    @log_group.setter
    def log_group(self, value):
        self._log_group = value

    @property
    def log_stream(self):
        return self._log_stream

    @log_stream.setter
    def log_stream(self, value):
        self._log_stream = value

    @property
    def logging_level(self):
        return self._logging_level

    @logging_level.setter
    def logging_level(self, value):
        if value not in [0, 10, 20, 30, 40, 50]:
            raise ValueError('Logging level must be in: 0, 10, 20, 30, 40, 50')
        else:
            self._logging_level = value          

    @property
    def logger(self):
        return self._logger

    @logger.setter
    def logger(self, value):
        self._logger = value