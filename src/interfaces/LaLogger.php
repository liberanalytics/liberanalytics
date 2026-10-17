<?php

    namespace interfaces;

    interface LaLogger
    {
        public static function writeLog(
            string $logType,
            string $logLocation,
            string $errorClass,
            string $errorFunction,
            string $errorSeverity,
            string $errorMsg
        ): bool;

    }

?>