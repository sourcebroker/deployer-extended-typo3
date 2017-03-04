<?php

namespace Deployer;

// check if we use corapi vs typo3_console database upadate schema method
task('typo3:db:update_schema', function(){

    run('cd {{deploy_path}}/current && {{bin/php}} {{bin/typo3cms}} database:updateschema field.add,field.change,table.add,table.change');

})->desc('Updates database schema.');