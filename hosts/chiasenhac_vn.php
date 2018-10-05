<?php

class dl_chiasenhac_vn extends Download
{
    public function CheckAcc($cookie) // use acc free

    {
        $data = $this->lib->curl("http://chiasenhac.vn/member.php", $cookie, "");
        if (stristr($data, 'Tài khoản: <b>')) {
            return array(true, "accfree");
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $post = array();
        $post["username"] = $user;
        $post["password"] = $pass;
        $post["redirect"] = "";
        $post["autologin"] = "checked";
        $post["login"] = 'Đăng nhập';

        $data = $this->lib->curl("http://chiasenhac.vn/login.php", "", $post);
        $cookie = $this->lib->GetCookies($data);

        return $cookie;
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");

        if (!preg_match('@https?:\/\/.+?chiasenhac\.vn\/.*\~(\S+).html@i', $data, $id)) {
            $this->error("Cannot get ID", true, false, 2);
        } else {
            $linkdownload = preg_replace('@(.html)$@', '_download.html', $url);
            $data = $this->lib->curl($linkdownload, $this->lib->cookie, "");
        }

        if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[Lossless_FLAC\]\.flac)"/', $data, $match)) {
            if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[500kbps_M4A\]\.m4a)"/', $data, $match)) {
                if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[320kbps_MP3\]\.mp3)"/', $data, $match)) {
                    if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[256kbps_MP3\]\.mp3)"/', $data, $match)) {
                        if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[192kbps_MP3\]\.mp3)"/', $data, $match)) {
                            if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[128kbps_MP3\]\.mp3)"/', $data, $match)) {
                                if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[64kbps_MP3\]\.mp3)"/', $data, $match)) {
                                    if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[32kbps_M4A\]\.m4a)"/', $data, $match)) {
                                        if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[HD 1080p_MP4\]\.mp4)"/', $data, $match)) {
                                            if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[1902x1080_MP4\]\.mp4)"/', $data, $match)) {
                                                if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[1280x726_MP4\]\.mp4)"/', $data, $match)) {
                                                    if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[HD 720p_MP4\]\.mp4)"/', $data, $match)) {
                                                        if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[854x484_MP4\]\.mp4)"/', $data, $match)) {
                                                            if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MV 480p_MP4\]\.mp4)"/', $data, $match)) {
                                                                if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[640x364_MP4\]\.mp4)"/', $data, $match)) {
                                                                    if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MV 360p_MP4\]\.mp4)"/', $data, $match)) {
                                                                        if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[320x182_MP4\]\.mp4)"/', $data, $match)) {
                                                                            if (!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MV 180p_MP4\]\.mp4)"/', $data, $match)) {
                                                                                if (preg_match('@https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/[^"\'><\r\n\t]+@i', $data, $match)) {
                                                                                    return trim($match[0]);
                                                                                }

                                                                            } else {
                                                                                return trim($match[1]);
                                                                            }

                                                                        } else {
                                                                            return trim($match[1]);
                                                                        }

                                                                    } else {
                                                                        return trim($match[1]);
                                                                    }

                                                                } else {
                                                                    return trim($match[1]);
                                                                }

                                                            } else {
                                                                return trim($match[1]);
                                                            }

                                                        } else {
                                                            return trim($match[1]);
                                                        }

                                                    } else {
                                                        return trim($match[1]);
                                                    }

                                                } else {
                                                    return trim($match[1]);
                                                }

                                            } else {
                                                return trim($match[1]);
                                            }

                                        } else {
                                            return trim($match[1]);
                                        }

                                    } else {
                                        return trim($match[1]);
                                    }

                                } else {
                                    return trim($match[1]);
                                }

                            } else {
                                return trim($match[1]);
                            }

                        } else {
                            return trim($match[1]);
                        }

                    } else {
                        return trim($match[1]);
                    }

                } else {
                    return trim($match[1]);
                }

            } else {
                return trim($match[1]);
            }

        } else {
            return trim($match[1]);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Chiasenhac.vn Download Plugin
 * Date: 26.06.2018
 */
