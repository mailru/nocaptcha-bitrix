<?

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mailru.nocaptcha/default_option.php");

class CNocaptcha
{
    const API_SERVER = "https://api-nocaptcha.mail.ru";

    var $publicKey;
    var $privateKey;
    var $arRuLangs;
    var $nextId = 0;
    var $arSettings = array();

    public static function GetInstance()
    {
        static $instance = null;
        if ($instance === null)
            $instance = new static();
        AddEventHandler("main", "OnEpilog",
                        array("CNocaptcha", "AddSettingsAndScript"));
        return $instance;
    }

    protected function __construct()
    {
        $this->publicKey = COption::GetOptionString("mailru.nocaptcha", "public_key",
                                                    $nocaptcha_default_option["public_key"]);
        $this->privateKey = COption::GetOptionString("mailru.nocaptcha", "private_key",
                                                     $nocaptcha_default_option["private_key"]);
        $t = COption::GetOptionString("mailru.nocaptcha", "ru_langs",
                                      $nocaptcha_default_option["ru_langs"]);
        $this->arRuLangs = preg_split("/[^[:alnum:]]+/", $t, -1, PREG_SPLIT_NO_EMPTY);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private static function JoinParams($params)
    {
        if (!count($params))
            return "";
        $ret = array();
        foreach ($params as $key => $val)
        {
            $ret[] = $key."=".urlencode(stripslashes($val));
        }
        return "?".join("&", $ret);
    }

    private function GenerateSettings()
    {
        if (!in_array(LANGUAGE_ID, $this->arRuLangs))
            $this->arSettings["lang"] = "en";
        return "var nocaptchaSettings=".json_encode($this->arSettings).";";
    }

    private function GenerateScriptUrl()
    {
        $params = array("public_key" => $this->publicKey);
        return self::API_SERVER."/captcha".self::JoinParams($params);
    }

    public static function AddSettingsAndScript()
    {
        global $APPLICATION;
        $inst = self::GetInstance();
        $APPLICATION->AddHeadString('<script type="text/javascript">'
                                    .$inst->GenerateSettings().'</script>');
        $APPLICATION->AddHeadScript($inst->GenerateScriptUrl());
    }

    public function Check($captchaId, $captchaValue)
    {
        $params = array(
            "private_key" => $this->privateKey,
            "captcha_id" => $captchaId,
            "captcha_value" => $captchaValue,
        );
        $url = self::API_SERVER."/check".self::JoinParams($params);

        $resp = @file_get_contents($url);
        if($resp === false)
        {
            return "request failed";
        }
        $data = json_decode($resp);
        if (!$data)
        {
            return "invalid response";
        }
        if ($data->status !== "ok")
        {
            return "service returned an error: ".$data->desc;
        }
        if (!$data->is_correct)
        {
            return false;
        }
        return true;
    }

    public function AddContainerId($id)
    {
        if (!isset($this->arSettings["containers"]))
            $this->arSettings["containers"] = array();
        $this->arSettings["containers"][] = $id;
    }

    public function GenerateContainerId()
    {
        $id = "nocaptcha".$this->nextId++;
        $this->AddContainerId($id);
        return $id;
    }
}
?>
