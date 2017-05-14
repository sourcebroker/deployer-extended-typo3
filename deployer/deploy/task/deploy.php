<?php

namespace Deployer;

task('deploy', [

    'deploy:check_lock',
    'deploy:composer_install_check',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths', // Remove files and folders which are not needed for webiste to work.
    'typo3:cache:regenerate_env_cache',
    'deployer:download', // Download deployer if its not there yet becase its needed to clear database cache tables.
    'lock:overwrite_entry_point',
    'lock:create_lock_files', // No frontend access possbile from now. Requests are buffered.
    'typo3:db:update_schema', // Update db schema for TYPO3.
    'cache:clearstatcache',
    'deploy:symlink', // Switch old php code with new version (./release/ dir is removed and all is now in ./current/ folder)
    'db:truncate', // Clear database cache tables.
    'typo3:cache:delete_typo3temp_cache', // Delete all typo3temp cache files.
    'cache:clearstatcache',
    'cache:frontendreset',
    'lock:delete_lock_files', // Frontend access possbile again from now.
    'deploy:unlock',
    'cleanup',

])->desc('Deploy your TYPO3');

after('deploy', 'success');
