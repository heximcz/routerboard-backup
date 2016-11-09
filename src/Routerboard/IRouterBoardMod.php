<?php

namespace Src\RouterBoard;

use Src\RouterBoard\InputParser;

interface IRouterBoardMod
{

    /**
     * Add new IP address to backup list
     * @param ip address $ip
     */
    public function addNewIP(InputParser $input);

    /**
     * Delete IP address from backup list
     * @param ip address $ip
     */
    public function deleteIP(InputParser $input);

    /**
     * Change existing IP address in backup list
     * @param old and new ip address in array $ip
     */
    public function updateIP(InputParser $input);

}