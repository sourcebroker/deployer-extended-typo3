<?php

namespace Deployer;

task('typo3:cache:delete_typo3temp_cache', function() {

    run('cd {{deploy_path}}/current && rm -rf typo3temp/Cache');

})->desc('Remove typo3temp/Cache at all');
