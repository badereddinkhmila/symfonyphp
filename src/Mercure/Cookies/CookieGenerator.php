<?php


namespace App\Mercure\Cookies;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\Cookie;

class CookieGenerator
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generate(): Cookie
    {
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => ['*'],'publish'=>['*']])
            ->getToken(new Sha256(), new Key($this->secret));
        return Cookie::create('mercureAuthorization', $token, (new \DateTime())->add(new \DateInterval('P1D')), '/.well-known/mercure',null,false, true);
    }
}