<?php

namespace Deployer;

task('typo3:cache:warmup', function () {

    // Set active_dir so the task can be used before or after "symlink" task or standalone.
    if (run('if [ -L {{deploy_path}}/release ] ; then echo true; fi')->toBool()) {
        set('active_dir', get('deploy_path') . '/release');
    } else {
        set('active_dir', get('deploy_path') . '/current');
    }

    run('cd {{deploy_path}}/current && {{bin/typo3cms}} cache:warmup');

})->desc('TYPO3 console - warmup cache.');