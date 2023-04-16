
Changelog
---------

master
~~~~~~

1) [TASK][BREAKING] Drop support for TYPO3 7.
2) [TASK] Make Typo3CmsDriver to use ``local/bin/php`` and ``local/bin/typo3cms`` vars.
3) [TASK] Merge the same configs for TYPO3 10-12.
4) [BUGFIX] Fix typo3-console binary.
5) [TASK] Drop support for helhum/dotenv-connector 1, helhum/typo3-console 4. Add dev-master for all for easier testing.
6) [TASK] Add "typo" alias "dpeloy" for "deploy".
7) [TASK] Add "phpcs.xml" to "clear_paths".
8) [TASK] Update dpeloyer-extended-database to 17.

20.0.0
~~~~~~

1) [TASK][BREAKING] Refactor for Deployer 7 compatibility. See UPGRADE.rst for more info.

2) [TASK][BREAKING] Drop support for TYPO3 6.2.

3) [TASK] Bring back 'keep_releases' set to 5 as it was in Deployer 6 (in Deployer 7 it is set to 10).


19.0.0
~~~~~~

1) [TASK][BREAKING] Set ``set('web_path', 'public/');`` and ``set('composer_channel', 2);`` by default for TYPO3 10+.
2) [TASK][BREAKING] Extend the ``clear_paths`` list.
3) [TASK] Allow ``driver_typo3cms`` to be used with v9.
4) [TASK][BREAKING] Update dependency to ``sourcebroker/deployer-loader`` which introduce load folder/files alphabetically.
5) [TASK][BREAKING] Update dependency to ``sourcebroker/deployer-extended-database``, ``sourcebroker/deployer-extended-media``
   due to ``sourcebroker/deployer-loader`` update.

18.1.0
~~~~~~

1) [FEATURE] Add path ``vendor/typo3/cms-core/Documentation/Changelog-*.rst`` for TYPO3 version detection to
   have compatibility with ``typo3/cms-composer-installers`` 4+.

2) [TASK] Optimise method projectRootAbsolutePath.

18.0.0
~~~~~~

1) [TASK][BREAKING] Possible breaking because it changes the way it detects TYPO3 version.
   Now its doing ``glob($rootDir . '/typo3/sysext/core/Documentation/Changelog-*.rst');`` and
   ``glob($rootDir . '/*/typo3/sysext/core/Documentation/Changelog-*.rst');`` and choose the
   highest number from the Changelogs files. The possible fail can be when you store second TYPO3
   sources with different version in the same root.

17.0.0
~~~~~~

1) [TASK][BREAKING] Possible breaking change because extends ``typo3_console`` to 7.0.
   Can break flows that depends on ``typo3cms install:generatepackagestates``.

16.1.0
~~~~~~

1) [TASK] Add support for TYPO3 11
2) [TASK] Cleanup on clear_paths and add ``.php-cs-fixer.php``.
3) [FEATURE] Add support for alternative way of reading database access data from TYPO3 to be used in deployer. The new
   Typo3CmsDriver driver is using command ``./vendor/bin/typo3cms configuration:showactive DB --json`` to read database d
   data so its independent on the way how you pass the database data for TYPO3. You need to activate it with
   ``set('driver_typo3cms', true);`` in your ``deploy.php`` file.
4) [FEATURE] Add ``typo3cms:language:update`` task for those who update language files on each deploy.

16.0.0
~~~~~~

1) [TASK][BREAKING] Update to ``sourcebroker/deployer-extended`` which overwrites ``bin/composer``. It allows to set
   ``composer_channel`` or ``composer_version``.

15.0.0
~~~~~~

1) [BUGFIX] Fix compatibility with symfony/dotenv 5.0 which do not use getenv() by default.
2) [TASK] Add ddev config.
3) [TASK][BREAKING] Remove auto creation of .env and database. Use ``https://ddev.readthedocs.io/en/stable/`` or similar
    tool instead.
4) [TASK][BREAKING] Change public method getDatabaseConfig() in class Typo3EnvDriver. The second parameter
   ``$absolutePathWithConfig`` was directory folder with .env file. Now the second parameter is path to file itself.
5) [TASK][BREAKING] Update ``sourcebroker/deployer-extended``, ``sourcebroker/deployer-extended-media``,
   ``sourcebroker/deployer-extended-database``.

14.1.0
~~~~~~

1) [TASK] Increase ``helhum/dotenv-connector`` version.

14.0.0
~~~~~~

1) [TASK] Add ``.ddev`` to ``clear_paths``.
2) [TASK] Increase ``deployer/dist`` version.
3) [BREAKING] Increase dependency to breaking version of ``sourcebroker/deployer-extended-database``

13.1.0
~~~~~~

1) [TASK] Support for ddev database settings.

13.0.0
~~~~~~

1) [TASK] Increase dependency for TYPO3 10.
2) [TASK] Refactor TYPO3 version detection.
3) [TASK][BREAKING] Refactor placement of configuration per TYPO3 version and add ``var/transient`` to shared dirs.
4) [TASK][BREAKING] Increase deployer version, increase typo3_console version, increase deployer-extended version.

12.1.0
~~~~~~

1) [TASK] Add ``web_path`` support to allow DocumentRoot in different folder.
2) [TASK] Anonymous function for ``web_path`` parts to allow to set ``web_path`` later.
3) [BUGFIX] Fix wrong share dir for logs folder.

12.0.0
~~~~~~

1) [TASK][BREAKING] Add auto unlock after deploy:failed
2) [TASK][BREAKING] Add task deploy:check_branch_local to deploy path.
3) [TASK] Increase default_timeout from 300s to 900s.
4) [TASK][BREAKING] Refactor config:createadmin to support new var naming from deployer-instance
5) [TASK][BREAKING] Deny pushing, copying, pulling media and database to top instance live.
6) [TASK][BREAKING] Update deployer\-extended-media, deployer-extended-database, deployer-instance, deployer-extended.
7) [TASK][BREAKING] By setting ``set('branch_detect_to_deploy', false);`` change the default unsafe bahaviour of deployer to
   deploy the currently checked out up branch. The branch must be set explicitly in host configuration.

11.0.0
~~~~~~

1) [BUGFIX] Fix use of SourceBroker\DeployerExtendedDatabase\Utility\ConsoleUtility in task config:createadmin.
2) [FEATURE][BREAKING] Extend set of not needed root files to cleanup on deploy.
3) [BUGFIX][BREAKING] Fix typo in env name - DET_CONFIG_CREATEADMIN_USERNAME to DEP_CONFIG_CREATEADMIN_USERNAME.

10.0.0
~~~~~~

1) [TASK] Add deploy:check_branch, deploy:info, deploy:log_extend tasks to deploy.
2) [TASK][BREAKING] Remove deployer-bulk-tasks dependency. Add database:updateschema task.
3) [TASK][BREAKING] Update database synchro config for TYPO3 9.

9.0.0
~~~~~

1) [TASK][BREAKING] Add sourcebroker/deployer-instance for instance vars management.
2) [FEATURE] Add task "config:createadmin" for creating TYPO3 admin user.
3) [BUGFIX] Remove colon from file names because if Windows compatibility.
4) [TASK] Remove not direct dependency.
5) [TASK] Normalize use of dots at the end of task description.

8.0.0
~~~~~

1) [TASK][BREAKING] Make typo3_console sem versioning more open.

7.1.0
~~~~~

1) [BUGFIX] If repo name has dots the database can not be auto created.
2) [TASK] Replace dots and dashes in database name to underscores to have more safe database name.

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
