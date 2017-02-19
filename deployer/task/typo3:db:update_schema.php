<?php

namespace Deployer;

// check if we use corapi vs typo3_console database upadate schema method
task('typo3:db:update_schema', function(){

    if (run("if [ -e {{release_path}}/vendor/bin/typo3cms ]; then echo 'true'; fi")->toBool()) {
        $typo3CmsBin = get('release_path') . '/vendor/bin/typo3cms';
    } else {
        $typo3CmsBin = get('release_path') . '/typo3cms';
    }
    if(strpos(run('cd {{deploy_path}}/current && {{bin/php}} ' . $typo3CmsBin . ' databaseapi:databasecompare 2,3,4')->toString(), 'No command could be found') !== FALSE) {
        run('cd {{deploy_path}}/current && {{bin/php}} ' . $typo3CmsBin . ' database:updateschema field.add,field.change,table.add,table.change');
    } else {
        run('cd {{deploy_path}}/current && {{bin/php}} ' . $typo3CmsBin . ' databaseapi:databasecompare 2,3,4');
    }

})->desc('Updates database schema.');