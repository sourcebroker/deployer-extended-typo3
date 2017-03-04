<?php

namespace Deployer;

task('typo3:regenerate_dotenv', function(){

    run("rm -f {{release_path}}/typo3temp/logs/dotenv-cache-*");
    run("cd {{release_path}} && {{bin/php}} {{bin/typo3cms}}");

})->setPrivate()->desc('Updates database schema.');

after('deploy:vendors', 'typo3:regenerate_dotenv');