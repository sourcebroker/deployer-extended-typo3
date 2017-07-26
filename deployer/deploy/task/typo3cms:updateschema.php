<?php

namespace Deployer;

task('typo3cms:database:updateschema', function () {
    if (test('[ -L {{deploy_path}}/release ]')) {
        $activeDir = get('deploy_path') . '/release';
    } else {
        $activeDir = get('deploy_path') . '/current';
    }
    run('cd ' . $activeDir . ' && {{bin/php}} typo3cms database:updateschema --schema-update-types=field.add,table.add,table.change,field.change');
})->desc('Update TYPO3 database schema.');