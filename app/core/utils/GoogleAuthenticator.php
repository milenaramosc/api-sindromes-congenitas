<?php

namespace App\Core\Utils;

class GoogleAuthenticator
{
    /**
     * @var int
     */
    protected $passCodeLength;

    /**
     * @var int
     */
    protected $secretLength;

    /**
     * @var int
     */
    protected $pinModulo;

    /**
     * NEXT_MAJOR: remove this property.
     */
    protected $fixBitNotation;

    /**
     * @param int $passCodeLength
     * @param int $secretLength
     */
    public function __construct($passCodeLength = 6, $secretLength = 10)
    {
        /* NEXT_MAJOR:
          - remove this block
          - make this class final
          - and protected properties and methods private */
        if (__CLASS__ !== get_class($this)) {
            @trigger_error(
                'Extending ' . __CLASS__ . ' is deprecated since 1.1, and will not be possible in 2.0. ',
                E_USER_DEPRECATED
            );
        }
        $this->passCodeLength = $passCodeLength;
        $this->secretLength = $secretLength;
        $this->pinModulo = pow(10, $this->passCodeLength);
    }

    /**
     * @param $secret
     * @param $code
     *
     * @return bool
     */
    public function checkCode($secret, $code)
    {
        $time = floor(time() / 30);
        for ($i = -1; $i <= 1; ++$i) {
            if ($this->codesEqual($this->getCode($secret, $time + $i), $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $secret
     * @param null $time
     *
     * @return string
     */
    public function getCode($secret, $time = null)
    {
        if (!$time) {
            $time = floor(time() / 30);
        }

        $base32 = new FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true);
        $secret = $base32->decode($secret);


        $time = pack('N', $time);
        $time = str_pad($time, 8, chr(0), STR_PAD_LEFT);

        $hash = hash_hmac('sha1', $time, $secret, true);
        $offset = ord(substr($hash, -1));
        $offset = $offset & 0xF;

        $truncatedHash = self::hashToInt($hash, $offset) & 0x7FFFFFFF;
        $pinValue = str_pad($truncatedHash % $this->pinModulo, 6, '0', STR_PAD_LEFT);

        return $pinValue;
    }

    /**
     * NEXT_MAJOR: Add a new parameter called $issuer.
     *
     * @param string $user
     * @param string $hostname
     * @param string $secret
     *
     * @return string
     */
    public function getUrl($user, $hostname, $secret)
    {
        $args = func_get_args();
        $encoder = 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=';
        $urlString = '%sotpauth://totp/%s@%s%%3Fsecret%%3D%s' . (array_key_exists(3, $args) && !is_null($args[3]) ? ('%%26issuer%%3D' . $args[3]) : '');
        $encoderURL = sprintf($urlString, $encoder, $user, $hostname, $secret);

        return $encoderURL;
    }

    /**
     * @return string
     */
    public function generateSecret()
    {
/*
        $secret = bin2hex(openssl_random_pseudo_bytes($this->secretLength));//decbin(rand(0,1024));//random_bytes($this->secretLength);

        $base32 = new FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true);

        return $base32->encode($secret); */

        $secret = "";
        for ($i = 1; $i <= $this->secretLength; $i++) {
            $c = rand(0, 255);
            $secret .= pack("c", $c);
        }
        $base32 = new FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true);
        return  $base32->encode($secret);
    }

    /**
     * @param string $bytes
     * @param int    $start
     *
     * @return int
     */
    protected static function hashToInt($bytes, $start)
    {
        $input = substr($bytes, $start, strlen($bytes) - $start);
        $val2 = unpack('N', substr($input, 0, 4));

        return $val2[1];
    }

    /**
     * A constant time code comparison.
     *
     * @param string $known known code
     * @param string $given code received from a user
     *
     * @return bool
     *
     * @see http://codereview.stackexchange.com/q/13512/6747
     */
    private function codesEqual($known, $given)
    {
        if (strlen($given) !== strlen($known)) {
            return false;
        }

        $res = 0;

        $knownLen = strlen($known);

        for ($i = 0; $i < $knownLen; ++$i) {
            $res |= (ord($known[$i]) ^ ord($given[$i]));
        }

        return $res === 0;
    }
}
