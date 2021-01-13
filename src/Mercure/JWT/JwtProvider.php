<?php


namespace App\Mercure\JWT;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtProvider
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function __invoke(): string
    {
        return (new Builder())
            ->withClaim('mercure', ['publish' => ['*'],'subscribe'=>['*']])
            ->getToken(new Sha256(), new Key($this->secret));
    }
}