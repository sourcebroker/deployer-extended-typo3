<?php

namespace Deployer;

task('typo3:regenerate_env_cache', function(){

    run("rm -f {{release_path}}/typo3temp/logs/dotenv-cache-*");
    run("cd {{release_path}} && {{bin/php}} {{bin/typo3cms}}");

})->setPrivate()->desc('Regenerate .env cache after adding .env from shared.');