<style>
.package {
    display: inline-block;
    margin: 3px 0;
    padding: 3px 6px;
    background-color: #fff;
}
.package-executed {
    display: inline-block;
    margin: 3px 0;
    padding: 3px 6px;
    background-color: #ccc;
    text-decoration: line-through;
}
</style>
<div class="wrap">
    <h1><?php _e('SQL пакеты')?></h1>

    <p>Выберите нужный пакет и нужное обновление БД и файловой системы и запустите. Пройденные пакеты пометятся как исполненные. Не запускайте один и тот же пакет дважды без необходимости. Перед исполнением пакета рекомендуется сделать бэкап.</p>

    <div>
        <?php
        $packages_path = \DiductioSqlPackages\PLUGIN_PATH. '/packages';

        // get executed
        $executed_packages = get_option('dsp_executed_packages', array());
        if($executed_packages) {
            $executed_packages = unserialize($executed_packages);
        }

        // run package
        if(isset($_GET['package'])) {
            $executed_packages[] = $_GET['package'];

            $requested_package = include($packages_path . '/' . $_GET['package']);
            if(isset($requested_package['_exec'])) {
                $requested_package['_exec']();
            }

            update_option('dsp_executed_packages', serialize(array_unique($executed_packages)));
        }

        // get packages
        $catalog_descriptor = opendir($packages_path );

        $packages = [];
        
        do {
            $package = readdir($catalog_descriptor);

            if(!$package || $package === '.' || $package === '..') {
                continue;
            }

            $addclass = in_array($package, $executed_packages) ? '-executed' : '';

            $current_package_path = $packages_path . '/' . $package;

            if(file_exists($current_package_path)) {
                $package_data = include($current_package_path);

                $packaged[$package] = $package_data;

                echo '<p class="package' . $addclass . '">';
                echo $package_data['title'] . ' - ' . $package_data['descr'];
                echo '&nbsp;&nbsp;&nbsp;<a class="button" href="?page=dsp-packages&package=' . $package . '">Выполнить</a>';
                echo '</p>';
                echo '<br>';
            }
        } while($package);



        
        ?>
    </div>
</div>