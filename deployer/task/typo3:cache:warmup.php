<?php

namespace Deployer;

task('typo3:cache:warmup', function() {

    if (run("if [ -e {{release_path}}/vendor/bin/typo3cms ]; then echo 'true'; fi")->toBool()) {
        $typo3CmsBin = get('release_path') . '/vendor/bin/typo3cms';
    } else {
        $typo3CmsBin = get('release_path') . '/typo3cms';
    }
    run('cd {{deploy_path}}/current && {{bin/php}} ' . $typo3CmsBin . ' cache:warmup');

})->desc('Warmup cache.');