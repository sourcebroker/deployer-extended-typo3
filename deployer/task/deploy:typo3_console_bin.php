<?php

namespace Deployer;

task('deploy:typo3_console_bin', function () {

    set('bin/typo3cms', function () {
        if (run("if [ -e {{release_path}}/vendor/bin/typo3cms ]; then echo 'true'; fi")->toBool()) {
            $typo3CmsBin = get('release_path') . '/vendor/bin/typo3cms';
        } else {
            $typo3CmsBin = get('release_path') . '/typo3cms';
        }
        return $typo3CmsBin;
    });

})->addAfter('deploy:vendors');
