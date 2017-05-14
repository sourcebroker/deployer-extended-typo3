<?php

namespace Deployer;

task('typo3:cache:delete_typo3temp_cache', function () {

    // Cache dir to delete.
    set('delete_typo3temp_cache__cache_dir', 'typo3temp/var/Cache');

    // Set active_dir so the task can be used before or after "symlink" task or standalone.
    if (run('if [ -L {{deploy_path}}/release ] ; then echo true; fi')->toBool()) {
        set('active_dir', get('deploy_path') . '/release');
    } else {
        set('active_dir', get('deploy_path') . '/current');
    }

    // Rename is atomic - so first rename and then delete.
    if (run('if [ -D {{active_dir}}/{{delete_typo3temp_cache__cache_dir}} ] ; then echo true; fi')->toBool()) {
        run('cd {{active_dir}} && mv {{delete_typo3temp_cache__cache_dir}} {{delete_typo3temp_cache__cache_dir}}{{random}}');
        run('cd {{active_dir}} && rm -rf {{delete_typo3temp_cache__cache_dir}}{{random}}');
    }

})->desc('Remove typo3temp/var/Cache');
