<?php

namespace Src\RouterBoard;

class IPValidator extends AbstractRouterBoard
{

    /**
     * Check if string is valid IPv4 address or domain
     * @param string $addr
     * @return boolean
     */
    public function ipv4validator($addr)
    {

        if ($this->ifDomainName($addr)) {
            if ($this->checkIPAddress(gethostbyname($addr)))
                return true;
            $this->logger->log("Domain: '" . $addr . "' not exist!", $this->logger->setError());
            return false;
        }

        if ($this->checkIPAddress($addr))
            return true;
        $this->logger->log("Input string: '" . $addr . "' is not valid IP address!", $this->logger->setError());
        return false;
    }

    /**
     * Check if a string represents domain name
     * @param string $domain
     * @return boolean
     */
    protected function ifDomainName($domain)
    {
        $validHostnameRegex = "/^[a-zA-Z0-9.\-]{2,256}\.[a-z]{2,6}$/";
        if (preg_match($validHostnameRegex, $domain)) {
            return true;
        }
        return false;
    }

    private function checkIPAddress($addr)
    {
        return filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

}
