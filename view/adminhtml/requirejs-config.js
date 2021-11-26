var config = {
    shim: {
        jquery: {
            exports: '$'
        },
        'D2DSoft_DataMigration/js/jquery.migration':
        {
            deps: ['jquery']
        },
        'D2DSoft_DataMigration/js/jquery.validate.min':
        {
            deps: ['jquery']
        },
        'D2DSoft_DataMigration/js/jquery.extend':
        {
            deps: ['jquery', 'D2DSoft_DataMigration/js/jquery.validate.min']
        }
    }
};