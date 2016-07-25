@servers(['airflix' => 'envoy@airflix.local',])

@setup
    $test = 'hola, whatever';

    $domain = isset($domain) ? $domain : "airflix.local";
    $branch = isset($branch) ? $branch : "master";
    $repo = 'git@github.com:wells/airflix.git';
    $rootDir = '/srv/www';
    $appDir = $rootDir . '/' . $domain;
    $storageDir = $appDir . '/storage';
    $downloadsDir = $appDir . '/downloads';
    $releaseDir = $appDir . '/releases';
    $appSymlink = $appDir . '/current';
    $rollbackSymlink = $appDir . '/rollback';
    $release = 'release_' . date('YmdHis');
@endsetup

@task('test', ['on' => 'airflix'])
    #test
    echo {{ $test }}
@endtask

@task('rollback', ['on' => 'airflix'])
    #rollback
    if [[ -L {{ $rollbackSymlink }} && -d {{ $rollbackSymlink }} ]]
    then
        ln -nfs $(readlink {{ $rollbackSymlink }}) {{ $appSymlink }};
        rm {{ $rollbackSymlink }};
    fi

    sudo service php7.0-fpm restart;
    sleep 1;
    php {{ $appSymlink }}/artisan queue:restart;
@endtask

@task('deploy', ['on' => 'airflix'])
    #remove-rollback
    if [[ -L {{ $rollbackSymlink }} && -d {{ $rollbackSymlink }} ]]
    then
        rm -rf $(readlink {{ $rollbackSymlink }});
        rm {{ $rollbackSymlink }};
    fi

    #fetch-package
    if [[ ! -d {{ $appDir }} ]]
    then
        mkdir {{ $appDir }};
        chmod ug+rwx {{ $appDir }};
    fi

    if [[ ! -d {{ $releaseDir }} ]]
    then
        mkdir {{ $releaseDir }};
        chmod ug+rwx {{ $releaseDir }};
    fi

    cd {{ $releaseDir }};
    git clone -b {{ $branch }} --depth=1 {{ $repo }} {{ $release }};

    #run-composer
    cd {{ $releaseDir }}/{{ $release }};

    if [[ ! -d {{ $storageDir }} ]]
    then
        mv {{ $releaseDir }}/{{ $release }}/storage {{ $storageDir }};
        chmod ug+rwx {{ $storageDir }};
    fi

    if [[ ! -d {{ $downloadsDir }} ]]
    then
        mv {{ $releaseDir }}/{{ $release }}/public/downloads {{ $downloadsDir }};
        chmod ug+rwx {{ $downloadsDir }};
    fi

    composer install --prefer-dist --no-scripts;
    php artisan clear-compiled --env=production;
    php artisan optimize --env=production;
    php artisan route:cache --env=production;

    #update-permissions
    cd {{ $releaseDir }};
    chmod -R ug+rwx {{ $release }};

    #update-symlinks
    echo 'Updating symlinks.';
    if [[ -L {{ $appSymlink }} ]]
    then
        cd $appDir;
        ln -nfs $(readlink {{ $appSymlink }}) {{ $rollbackSymlink }};
    fi

    cd {{ $releaseDir }}/{{ $release }};

    rm -rf {{ $releaseDir }}/{{ $release }}/storage;
    ln -nfs {{ $storageDir }} storage;

    rm -rf {{ $releaseDir }}/{{ $release }}/public/downloads;
    ln -nfs {{ $appDir }}/downloads public/downloads;

    ln -nfs {{ $appDir }}/.env .env;

    ln -nfs {{ $releaseDir }}/{{ $release }} {{ $appSymlink }};

    sudo service php7.0-fpm restart;
    sleep 1;
    php {{ $appSymlink }}/artisan queue:restart;
@endtask