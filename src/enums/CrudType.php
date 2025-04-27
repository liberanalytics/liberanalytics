<?php
    namespace enums;

    enum CrudType: string
    {
        CASE CRUDCREATE = 'create';
        CASE CRUDREAD = 'read';
        CASE CRUDUPDATE = 'update';
        CASE CRUDDELETE = 'delete';
    }
?>