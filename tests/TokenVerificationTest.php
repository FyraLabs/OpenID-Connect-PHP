<?php


use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;
use PHPUnit\Framework\MockObject\MockObject;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class TokenVerificationTest extends TestCase
{
    /**
     * @param $alg
     * @param $jwt
     * @throws OpenIDConnectClientException
     * @dataProvider providesTokens
     */
    public function testTokenVerification($alg, $jwt)
    {
        /** @var OpenIDConnectClient | MockObject $client */
        $client = $this->getMockBuilder(OpenIDConnectClient::class)->setMethods(['fetchUrl'])->getMock();
        $client->method('fetchUrl')->willReturn(file_get_contents(__DIR__ . "/data/jwks-$alg.json"));
        $client->setProviderURL('https://jwt.io/');
        $client->providerConfigParam(['jwks_uri' => 'https://jwt.io/.well-known/jwks.json']);
        $verified = $client->verifyJWTSignature($jwt);
        self::assertTrue($verified);
        $client->setAccessToken($jwt);
    }

    public function providesTokens(): array
    {
        return [
            'RS256' => ['rs256', 'eyJhbGciOiJSUzI1NiIsImtpZCI6IlJTQSJ9.eyJzdWIiOiJvd28ifQ.Qa1DIn-8xyJXLb9qXHy12hzLzAGIw6hYXgm9HbZWDVz-X-6-lo9w6NR0GzfMRqZNYRIy-Mew-dXtUa9EMd1vJdeyY9oTH3hD-cd6U3q9-ZiFLMWVaMFUxfhvDpJqfTcF3d-zahW21MntMuCGhlbg4_ziJjyuWerWeUmwXWbJIO5AXopyFrutpvgk_B39PI3BONAXt840egic5HU44qhbt7eDjYC7qeImyGCaeu4N32wBva0zoNCUC7ONoag5P9-Z0V_oLuQ2Ym17w7iErFs10F1bW5y4CcYjwAD4swZmRo0-RYknTVXQjlQ-90M4mhwJJz_dcGaT3EVrSfdUxA4_kA']
        ];
    }
}
