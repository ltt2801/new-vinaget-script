<?php
$host = array();
$alias = array();
$alias['depositfiles.com']['dfiles.eu'] = true;
$alias['depositfiles.com']['dfiles.ru'] = true;
$alias['depositfiles.com']['depositfiles.net'] = true;
$alias['depositfiles.com']['depositfiles.org'] = true;
$alias['uploaded.net']['ul.to'] = true;
$alias['uploaded.net']['uploaded.to'] = true;
$alias['chiasenhac.vn']['chiasenhac.com'] = true;
$alias['yunfile.com']['yfdisk.com'] = true;
$alias['yunfile.com']['filemarkets.com'] = true;
$alias['yunfile.com']['dfpan.com'] = true;
$alias['k2s.cc']['keep2s.cc'] = true;
$alias['k2s.cc']['keep2share.cc'] = true;
$alias['rapidgator.net']['rg.to'] = true;
$alias['depfile.us']['depfile.com'] = true;
$alias['mega.nz']['mega.co.nz'] = true;
$alias['4share.vn']['up.4share.vn'] = true;


// general hosts
$folderhost = opendir("hosts/");

while ($hostname = readdir($folderhost)) {
    if ($hostname == "." || $hostname == ".." || strpos($hostname, "bak")) {
        continue;
    }

    if (stripos($hostname, "php")) {
        $site = str_replace("_", ".", substr($hostname, 0, -4));
        if (isset($alias[$site])) {
            foreach ($alias[$site] as $alias_host => $value) {
                $host[$alias_host] = array(
                    'alias' => true,
                    'site' => $site,
                    'file' => str_replace(".", "_", $site) . ".php",
                    'class' => "dl_" . str_replace(array(".", "-"), "_", $site),
                );
            }
        }

        $host[$site] = array(
            'alias' => false,
            'site' => $site,
            'file' => $hostname,
            'class' => "dl_" . str_replace(array(".", "-"), "_", $site),
        );
    }
}

closedir($folderhost);

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Alias Hosts
 * Date: 08.09.2020
 */
