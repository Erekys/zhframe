<?php
/**
 * Created by PhpStorm.
 * User: kali
 * Date: 2017/11/2
 * Time: 14:20
 */
namespace zhframe\Request;
class Request{
    protected static $instance;
    protected function __construct($options = [])
    {
    }
    public static function getInstance($options = [])
    {
        if (is_null(self::$instance)) {

            self::$instance = new static($options);

        }
        return self::$instance;
    }
    public function ip()
    {
        return $this->getClientIp();
    }
    public function getClientIp()
    {
        $ipAddresses = $this->getClientIps();

        return $ipAddresses[0];
    }
    public function getClientIps()
    {
        $clientIps = array();
        $ip = $this->server->get('REMOTE_ADDR');

        if (!$this->isFromTrustedProxy()) {
            return array($ip);
        }

        $hasTrustedForwardedHeader = self::$trustedHeaders[self::HEADER_FORWARDED] && $this->headers->has(self::$trustedHeaders[self::HEADER_FORWARDED]);
        $hasTrustedClientIpHeader = self::$trustedHeaders[self::HEADER_CLIENT_IP] && $this->headers->has(self::$trustedHeaders[self::HEADER_CLIENT_IP]);

        if ($hasTrustedForwardedHeader) {
            $forwardedHeader = $this->headers->get(self::$trustedHeaders[self::HEADER_FORWARDED]);
            preg_match_all('{(for)=("?\[?)([a-z0-9\.:_\-/]*)}', $forwardedHeader, $matches);
            $forwardedClientIps = $matches[3];

            $forwardedClientIps = $this->normalizeAndFilterClientIps($forwardedClientIps, $ip);
            $clientIps = $forwardedClientIps;
        }

        if ($hasTrustedClientIpHeader) {
            $xForwardedForClientIps = array_map('trim', explode(',', $this->headers->get(self::$trustedHeaders[self::HEADER_CLIENT_IP])));

            $xForwardedForClientIps = $this->normalizeAndFilterClientIps($xForwardedForClientIps, $ip);
            $clientIps = $xForwardedForClientIps;
        }

        if ($hasTrustedForwardedHeader && $hasTrustedClientIpHeader && $forwardedClientIps !== $xForwardedForClientIps) {
            throw new ConflictingHeadersException('The request has both a trusted Forwarded header and a trusted Client IP header, conflicting with each other with regards to the originating IP addresses of the request. This is the result of a misconfiguration. You should either configure your proxy only to send one of these headers, or configure Symfony to distrust one of them.');
        }

        if (!$hasTrustedForwardedHeader && !$hasTrustedClientIpHeader) {
            return $this->normalizeAndFilterClientIps(array(), $ip);
        }

        return $clientIps;
    }
}