<?php

namespace Deployer;

task('typo3:regenerate_env_cache', function(){

    // Set active_dir so the task can be used before or after "symlink" task or standalone.
    if (run('if [ -L {{deploy_path}}/release ] ; then echo true; fi')->toBool()) {
        set('active_dir', get('deploy_path') . '/release');
    } else {
        set('active_dir', get('deploy_path') . '/current');
    }

    run("rm -f {{active_dir}}/typo3temp/var/logs/dotenv-cache-*");
    run("cd {{active_dir}} && {{bin/php}} {{bin/typo3cms}} 2>&1");

})->setPrivate()->desc('Regenerate .env cache after adding .env from shared.');