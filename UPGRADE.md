# Upgrades

## 25.0.0 -> 26.0.0

1)  Host level `db_databases_overwrite` is applied again (it was ignored in 25.0.0). Check if `post_sql_in`,
    `post_sql_in_markers` or other settings you have there for hosts are still what you want to be executed
    on `db:pull`, `db:copy`, `db:import`.

2)  This package now adds its tables (`sys_history`, `sys_log`, `tx_powermail_domain_model_mail`,
    `tx_powermail_domain_model_answer`) to `ignore_tables_out` of `database_default` by setting
    `db_databases_overwrite_global` (before it changed `db_default`). If you set `db_databases_overwrite_global`
    in your `deploy.php` with `set()` you will replace those tables. Extend it instead. Do not use `add()` for it
    as it fails on nested lists.

        $dbDatabasesOverwriteGlobal = get('db_databases_overwrite_global', []);
        $dbDatabasesOverwriteGlobal['database_default'][0]['ignore_tables_out'][] = 'tx_my_table';
        set('db_databases_overwrite_global', $dbDatabasesOverwriteGlobal);

    You can still add tables for one instance only with host level `db_databases_overwrite`. It is merged on top
    and does not remove the tables added here.

3)  `file_upload_build_rsync_options` is set to `['--no-perms', '--chmod=ugo=rwX,o-w']`, so files uploaded by
    `file:upload_build` no longer keep permissions from the build. They get default permissions of the server
    (its `umask`, e.g. `644`/`755` for `umask 0022`), but never write permission for others.
    To keep source permissions as before set empty array:

        set('file_upload_build_rsync_options', []);

## 20.0.0 -> 24.0.0

The scope of changes to base code is big but for most cases you should only remove the old loader from `deploy.php`:

    require_once(__DIR__ . '/vendor/sourcebroker/deployer-loader/autoload.php');
    new \SourceBroker\DeployerExtendedTypo3\Loader();

and replace it with new package loading.

    require_once('./vendor/autoload.php');

    new \SourceBroker\DeployerLoader\Load([
        ['get' => 'sourcebroker/deployer-typo3-media'],
        ['get' => 'sourcebroker/deployer-typo3-database'],
        ['get' => 'sourcebroker/deployer-typo3-deploy'],
        ['get' => 'sourcebroker/deployer-extended-typo3'],
    ]);

For most cases you can also remove `bin/php` from your host definitions because it is now detected automatically
based on PHP version in composer.json. There is however condition that `phpXY` or `phpX.Y` is available in PATH
at host. Not all hosters deliver this unfortunately.

## 19.0.0 -> 20.0.0

1)  You need now to have composer.json in root directory.
2)  Task `deploy:extend_log` has been removed in favor of such same task build in in Deployer 7.
    File `.dep/releases.extended` with additional info can be removed.
3)  If you were modifying `db_databases` on host level with `array_merge_recursive` and in `db_databases` there
    were some closures then since Deployer 7 this will no longer work. You can get the same result when using
    `db_databases_overwrite`. You can also use `db_databases_overwrite_global` to overwrite with similar way on
    global level.

<!-- -->

    OLD

    host('local')
        ->set('deploy_path', getcwd())
        ->set('db_databases', array_merge_recursive(get('db_databases'),
            [
                'database_default' =>
                    [
                        [
                            'post_sql_in' =>
                                '
                                  UPDATE table .....
                                '
                        ]
                    ]
            ]));

    NEW

    host('local')
        ->set('deploy_path', getcwd())
        ->set('db_databases_overwrite',
            [
                'database_default' =>
                    [
                        [
                            'post_sql_in' =>
                                '
                                  UPDATE table .....
                                '
                        ]
                    ]
            ]);
