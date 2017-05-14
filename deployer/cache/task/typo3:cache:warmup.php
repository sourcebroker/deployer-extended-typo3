<?php

namespace Deployer;

task('typo3:cache:warmup', function() {

    run('cd {{deploy_path}}/current && {{bin/typo3cms}} cache:warmup');

})->setPrivate()->desc('TYPO3 console - warmup cache.');