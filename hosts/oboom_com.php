<?php

class dl_oboom_com extends Download
{
    /*
    public function CheckAcc($cookie) {
    $data = $this->lib->curl('https://www.oboom.com/', 'lang=EN; ' .str_replace(':', '%3A', $cookie), '');

    if (preg_match('@premium_unix"\:([^,]+)@i', $data, $redir)) {
    if ($redir[1] == 'null') return array(false, "accfree");
    elseif (preg_match('@traffic"\:{"current"\:([^,]+),"increase"\:[^,]+,"last"\:[^,]+,"max"\:([^}]+)@i', $data, $redir2)) {
    if ($redir2[1] == 0 && $redir2[2] == 0) return array(false, "accfree");
    else return array(true, 'Until ' .date('H:i:s Y-m-d', $redir[1]). '<br/> Traffic available: ' .round($redir2[1]/1073741824, 2). ' GB<br/> Max: ' .round($redir2[2]/1073741824, 2). ' GB');
    }
    }
    else return array(false, "accinvalid");
    }
     */
    public function Login($user, $pass)
    {
        $mysalt = strrev($pass);

        $hash = $this->pbkdf2('sha1', $pass, $mysalt, 1000, 16);

        $page = $this->lib->curl('https://www.oboom.com/1/login', 'lang=EN', array('auth' => $user, 'pass' => $hash, 'source' => '/#app'), 0);

        $js = @json_decode($page, true);

        $cookie = 'user=' . urlencode($js[1]['cookie']) . '; lang=EN; ';
        return array(true, $cookie);
    }

    public function Leech($link)
    {

        if (strpos($link, '#')) {
            $link = str_replace('#', '', $link);
        }

        if (!preg_match('@https?://(www.)?oboom\.com/([\w]{8})@i', $link, $id)) {
            $this->error('Link invalid?.', true, false);
        }

        $link = "https://www.oboom.com/$id[2]";

        $page = $this->lib->curl($link, 'lang=EN; ' . $this->lib->cookie, '');

        if (strpos($page, '400 Bad Request')) {
            $this->error('Link invalid?.', true, false);
        }

        if (preg_match('@ocation: (https?://(www\.)?oboom\.com/[^\r\n]+)@i', $page, $redir)) {
            $page = $this->lib->curl(trim($redir[1]), $this->lib->cookie, '');
        }

        if (preg_match('@ocation: (https?://[^\r\n]+)@i', $page, $redir2)) {

            $link = trim($redir2[1]);

            if (strpos($link, 'redirect=true')) {
                $page = $this->lib->curl($link, 'lang=EN; ' . $this->lib->cookie, '');
                if (preg_match('@ocation: (https?://[^\r\n]+)@i', $page, $redir3)) {
                    $link = trim($redir3[1]);
                }

                if (strpos($link, '410,"abused"')) {
                    $this->error("Gone. The resource you requested is no longer available and will not come back.", true, false);
                }

                if (strpos($link, '404,"item"')) {
                    $this->error("The requested file was not found.", true, false);
                }

                if (strpos($link, '1/dlh?ticket')) {
                    return trim($link);
                } else {
                    $this->error($this->lib->cut_str($link, '?e=[', '",'), true, false);
                }

            }
        }

        if (!preg_match('@Session : "([^"]+)"@i', $page, $token)) {
            $this->error("Token not found.", true, false);
        }

        $page = $this->lib->curl('https://api.oboom.com/1/dl', $this->cookie, array('token' => $token[1], 'item' => $id[2]), 0);

        $json = @json_decode($page, true);

        if (isset($json[0]) && $json[0] == 200) {
            $link = trim('http://' . $json[1] . '/1.0/dlh?ticket=' . $json[2]);
            if (!preg_match('@https?://[\w]+\.oboom\.com/1\.0/dlh\?ticket=[^\r\n]+@i', $link, $dlink)) {
                $this->error('Error: Download link not found?.', true, false);
            }

            return trim($link);
        }

        if (isset($json[0]) && $json[0] != 200) {
            $this->CheckErr($json[0]);
        }

        return false;
    }

    private function CheckErr($code)
    {
        if (is_numeric($code)) {
            switch ($code) {
                default:$msg = '*No message for this error*';
                    break;
                case 400:$msg = 'Bad request.';
                    break;
                case 403:$msg = 'Access denied.';
                    break;
                case 404:$msg = 'Resource not found.';
                    break;
                case 409:$msg = 'Conflict.';
                    break;
                case 410:$msg = 'Gone. The resource you requested is no longer available and will not come back.';
                    break;
                case 413:$msg = 'Request entity too large.';
                    break;
                case 421:$msg = 'Connection limit exceeded.';
                    break;
                case 500:$msg = 'Internal server error.';
                    break;
                case 503:$msg = 'The service is temporary not available.';
                    break;
                case 507:$msg = 'At least one quota like storage space or item count reached.';
                    break;
                case 509:$msg = 'Bandwidth limit exceeded.';
                    break;
            }
            $this->error("[Error: $code] $msg.", true, false);
        }
    }

    private function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    { //https://github.com/defuse/password-hashing/blob/master/PasswordHash.php
        $algorithm = strtolower($algorithm);
        if (!in_array($algorithm, hash_algos(), true)) {
            trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
        }

        if ($count <= 0 || $key_length <= 0) {
            trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
        }

        if (function_exists("hash_pbkdf2")) {
            // The output length is in NIBBLES (4-bits) if $raw_output is false!
            if (!$raw_output) {
                $key_length = $key_length * 2;
            }
            return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
        }

        $hash_length = strlen(hash($algorithm, "", true));
        $block_count = ceil($key_length / $hash_length);

        $output = "";
        for ($i = 1; $i <= $block_count; $i++) {
            // $i encoded as 4 bytes, big endian.
            $last = $salt . pack("N", $i);
            // first iteration
            $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
            // perform the other $count - 1 iterations
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }

        if ($raw_output) {
            return substr($output, 0, $key_length);
        } else {
            return bin2hex(substr($output, 0, $key_length));
        }

    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Oboom.com Download Plugin
 * Date: 01.09.2018
 */
