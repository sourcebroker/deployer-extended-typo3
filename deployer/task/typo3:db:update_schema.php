<?php

namespace Deployer;

task('typo3:db:update_schema', function(){

    run('cd {{deploy_path}}/current && {{bin/php}} {{bin/typo3cms}} database:updateschema field.add,field.change,table.add,table.change');

})->desc('Updates TYPO3 database schema: typo3cms database:updateschema field.add,field.change,table.add,table.change');