<?php

namespace Deployer;

task('deploy', [
    'deploy:check_lock',
    'deploy:composer_install_check',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'lock:overwrite_entry_point',
    'cache:clearstatcache',
    'lock:create_lock_files', // No frontend access possbile from now. Requests are buffered.
    'deploy:clear_paths', // Remove files and folders which are not needed for webiste to work.
    'deploy:symlink', // Switch old php code with new version (./release/ dir is removed and all is now in ./current/ folder)
    'deployer:download', // Download deployer if its not there yet becase its needed to clear database cache tables.
    'typo3:db:truncate_table', // Clear database cache tables.
    'typo3:cache:delete_typo3temp_cache', // Delete all typo3temp cache files.
    'typo3:db:update_schema', // Update db schema for TYPO3.
    'cache:frontendreset',
    'lock:delete_lock_files', // Frontend access possbile again from now.
    'cleanup',
])->desc('Deploy your TYPO3');

after('deploy', 'success');