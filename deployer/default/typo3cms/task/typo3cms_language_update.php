<?php

namespace Deployer;

task('check_language_update', function () {
    if (get('typo3_language_update', false)) {
        invoke('typo3cms:language:update');
    }
})->shallow();

task('typo3cms:language:update', function () {
    $activeDir = test('[ -e {{deploy_path}}/release ]') ?
        get('deploy_path') . '/release' :
        get('deploy_path') . '/current';
    run('cd ' . $activeDir . ' &&  {{bin/php}} {{bin/typo3cms}} language:update');
});
