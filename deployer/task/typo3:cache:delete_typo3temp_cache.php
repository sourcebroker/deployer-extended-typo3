<?php

namespace Deployer;

task('typo3:cache:delete_typo3temp_cache', function() {
    // rename is atomic - so first rename and then delete
    run('cd {{deploy_path}}/current && mv typo3temp/Cache typo3temp/Cache{{random}}');
    run('cd {{deploy_path}}/current && rm -rf typo3temp/Cache{{random}}');

})->desc('Remove typo3temp/Cache');
