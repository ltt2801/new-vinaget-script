<?php
$host = array();
$alias = array();
$alias['dfiles.eu'] = 'depositfiles.com';
$alias['dfiles.ru'] = 'depositfiles.com';
$alias['depositfiles.net'] = 'depositfiles.com';
$alias['depositfiles.org'] = 'depositfiles.com';
$alias['ul.to'] = 'uploaded.net';
$alias['uploaded.to'] = 'uploaded.net';
$alias['chiasenhac.com'] = 'chiasenhac.vn';
$alias['d01.megashares.com'] = 'megashares.com';
$alias['fp.io'] = 'filepost.com';
$alias['clz.to'] = 'cloudzer.net';
$alias['yfdisk.com'] = 'yunfile.com';
$alias['filemarkets.com'] = 'yunfile.com';
$alias['dfpan.com'] = 'yunfile.com';
$alias['keep2s.cc'] = 'k2s.cc';
$alias['keep2share.cc'] = 'k2s.cc';
$alias['rg.to'] = 'rapidgator.net';
$alias['depfile.com'] = 'depfile.us';
$alias['mega.co.nz'] = 'mega.nz';
$alias['up.4share.vn'] = '4share.vn';

// general hosts
$folderhost = opendir("hosts/");

while ($hostname = readdir($folderhost)) {
    if ($hostname == "." || $hostname == ".." || strpos($hostname, "bak")) {
        continue;
    }

    if (stripos($hostname, "php")) {
        $site = str_replace("_", ".", substr($hostname, 0, -4));
        if (isset($alias[$site])) {
            $host[$site] = array(
                'alias' => true,
                'site' => $alias[$site],
                'file' => str_replace(".", "_", $alias[$site]) . ".php",
                'class' => "dl_" . str_replace(array(".", "-"), "_", $alias[$site]),
            );
        } else {
            $host[$site] = array(
                'alias' => false,
                'site' => $site,
                'file' => $hostname,
                'class' => "dl_" . str_replace(array(".", "-"), "_", $site),
            );
        }
    }
}

closedir($folderhost);

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Alias Hosts
 * Date: 23.06.2018
 */
