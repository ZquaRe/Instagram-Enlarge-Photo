<?php

/**
 * Class Instagram
 *
 * @author Furkan Sezgin (ZquaRe)
 * @mail furkan-sezgin@hotmail.com
 */

namespace ZquaRe\Instagram;
class instagram
{

    const JsonAPI = '/?__a=1';

    //Cache
    const CacheTime = 60 * 60 * 1; //Time zone to hold cache (hours) | Example 1 hour
    const CacheFolder = 'caches/'; //Cache file path
    const CacheExtension = '.json';  //Cache file extension
    public $CacheObj = null;
    private $Cache = null;

    private $profileURL = null;
    private $UserName = null;

    //User Profile Details
    private $Biography = null;
    private $External_url = null;
    private $Followed = null;
    private $Follower = null;
    private $Fullname = null;
    private $Business = null;
    private $Business_Category = null;
    private $Private = null;
    private $Verified = null;
    private $ProfilePic = null;

    /**
     * instagram constructor.
     * @param $URL
     * @param bool $Cache
     */
    function __construct($URL, $Cache = true)
    {
        $this->profileURL = $URL;
        $this->Cache = $Cache;

        //Is Cache selected?
        if ($this->Cache) {
            self::UsernameGetter($this->profileURL);
            //If the file creation time has not expired, read from the Cache file.
            if (time() - self::CacheTime < @filemtime(self::CacheFolder . md5($this->UserName) . self::CacheExtension)) {
                //Read from cache file
                $this->CacheObj = json_decode(file_get_contents(self::CacheFolder . md5($this->UserName) . self::CacheExtension));
            } else {

                //File Control
                if (!file_exists(self::CacheFolder)) {
                    //Create if no file
                    mkdir(self::CacheFolder, 0777);
                }
                //Keep as Cache
                $this->CacheObj = self::cURL(self::UsernameGetter($this->profileURL));
                file_put_contents(self::CacheFolder . md5($this->UserName) . self::CacheExtension, json_encode($this->CacheObj));
            }
        }

    }

    /**
     * @return mixed
     */
    public function Picture()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->profile_pic_url_hd;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->profile_pic_url_hd;
        }
    }

    /**
     * @return mixed
     */
    public function Biography()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->biography;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->biography;
        }
    }

    /**
     * @return mixed
     */
    public function Followed()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->edge_followed_by->count;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->edge_followed_by->count;
        }
    }

    /**
     * @return mixed
     */
    public function Follower()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->edge_follow->count;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->edge_follow->count;
        }
    }

    /**
     * @return mixed
     */
    public function Username()
    {
        self::UsernameGetter($this->profileURL);
        return $this->UserName;
    }

    /**
     * @return mixed
     */
    public function Fullname()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->full_name;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->full_name;
        }
    }

    /**
     * @return mixed
     */
    public function Business()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->is_business_account;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->is_business_account;
        }
    }

    /**
     * @return mixed
     */
    public function Business_Category()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->business_category_name;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->business_category_name;
        }
    }

    /**
     * @return mixed
     */
    public function Private()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->is_private;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->is_private;
        }
    }

    /**
     * @return mixed
     */
    public function Verified()
    {
        if ($this->Cache) {
            return $this->CacheObj->graphql->user->is_verified;
        } else {
            return self::cURL(self::UsernameGetter($this->profileURL))->graphql->user->is_verified;
        }
    }
    /**
     * @param $URL
     * @return string
     */
    //If instagram.com address is specified / smash user and get username, even if instagram.com address is not specified, just in case / smash
    private function UsernameGetter($URL)
    {
        $this->profileURL = $URL;
        if (preg_match("#instagram.com#", $this->profileURL)) {
            $this->UserName = array_filter(explode('/', $this->profileURL))[3];
            return $this->profileURL = 'https://www.instagram.com/' . $this->UserName . self::JsonAPI;
        } else {
            $this->UserName = array_filter(explode('/', $this->profileURL))[0];
            return $this->profileURL = 'https://www.instagram.com/' . $this->UserName . self::JsonAPI;
        }

    }

    /**
     * @param $url
     * @return mixed
     */
    private function cURL($url)
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result);
    }
}

?>