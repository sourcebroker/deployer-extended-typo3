<?php

namespace Deployer;

task('typo3:cache:warmup', function() {

    run('cd {{deploy_path}}/current && {{bin/typo3cms}} cache:warmup');

})->desc('TYPO3 console - warmup cache.');