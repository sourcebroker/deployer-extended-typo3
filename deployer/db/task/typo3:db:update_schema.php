<?php

namespace Deployer;

task('typo3:db:update_schema', function(){

    if (run('if [ -L {{deploy_path}}/release ] ; then echo true; fi')->toBool()) {
        set('active_dir', get('deploy_path') . '/release');
    } else {
        set('active_dir', get('deploy_path') . '/current');
    }
    run('cd {{active_dir}} && {{bin/php}} {{bin/typo3cms}} database:updateschema field.add,field.change,table.add,table.change');

})->desc('Updates TYPO3 database schema: typo3cms database:updateschema field.add,field.change,table.add,table.change');