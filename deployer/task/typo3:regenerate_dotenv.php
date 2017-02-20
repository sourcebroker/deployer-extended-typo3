<?php

namespace Deployer;

task('typo3:regenerate_dotenv', function(){

    run("rm -f {{release_path}}/typo3temp/logs/dotenv-cache-*");
    run("{{bin/php}} {{release_path}}/vendor/bin/typo3cms");

})->setPrivate()->desc('Updates database schema.')->addAfter('deploy:vendors');
