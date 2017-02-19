<?php

namespace Deployer;

task('typo3:db:truncate_table', function() {

    run('cd {{deploy_path}}/current && {{bin/php}} deployer.phar db:truncate_table ' . get('server')['name']);

})->desc('Truncate tables set in "caching_tables" var.');
