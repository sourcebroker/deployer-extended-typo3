
Changelog
---------

7.0.1
~~~~~

1) [BUGFIX] The "typo3cms database:updateschema" without additional parameters in not available in typo3_console
   that can be installed in TYPO3 6.2. Therefore separate task is needed for TYPO3 6.2.


7.0.0
~~~~~

1) [TASK][BREAKING] Modify default deploy task.
2) [TASK] Add "deploy-fast" task without database and buffer protections - good to deploy to staging instances.
3) [TASK][BREAKING] Increase verisons of sourcebroker/deployer-extended, sourcebroker/deployer-extended-media,
   sourcebroker/deployer-extended-database.

6.2.1
~~~~~

1) [BUGFIX] Fix database creation statement to allow all chars.

6.2.0
~~~~~

1) [TASK] Increase helhum/typo3-console dependency to 5.5.0
2) [TASK] Set helhum/dotenv-connector to ~2.1.0 for better stability.


6.1.0
~~~~~

1) [TASK] Make dependency to helhum/dotenv-connector more open.

6.0.1
~~~~~

1) [BUGFIX] Remove dependency from FileUtility.

6.0.0
~~~~~

1) [TASK] Add ssh_type and ssh_multiplexing (It was removed from package "deployer-extended" to higher level package
   like this one).
2) [FEATURE] .env file autocreate.
3) [DOCS] Docs cleanup.

5.2.0
~~~~~

1) [FEATURE] CMS and vendors to to .Build.

5.1.0
~~~~~

1) [FEATURE] Add support for typo3_console 5.0.0

5.0.1
~~~~~

1) [BUGFIX] Add missing binary to bulk_tasks.

5.0.0
~~~~~

1) [BREAKING] First version of unified implementation - one code to support all version of TYPO3.
2) [DOCS] Docs update.