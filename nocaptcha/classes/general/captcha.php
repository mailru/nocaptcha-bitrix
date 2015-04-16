<?

if (array_key_exists('captcha_id', $_POST))
{
    $_POST['captcha_sid'] = $_POST['captcha_id'];
    $_REQUEST['captcha_sid'] = $_REQUEST['captcha_id'];
    $_POST['captcha_word'] = $_POST['captcha_value'];
    $_REQUEST['captcha_word'] = $_REQUEST['captcha_value'];
}

class CCaptcha
{
    public function CheckCode($captchaValue, $captchaId)
    {
        CModule::IncludeModule('nocaptcha');
        if (CNocaptcha::GetInstance()->Check($captchaId, $captchaValue) === true)
            return true;
        return false;
    }

    public function CheckCodeCrypt($captchaValue, $captchaId,
                                   $password = '', $bUpperCode = true)
    {
        return $this->CheckCode($captchaValue, $captchaId);
    }

    public function SetCode()
    {
    }

    public function GetSID()
    {
        return 'fake_sid';
    }
}
?>
