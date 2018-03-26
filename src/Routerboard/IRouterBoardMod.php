<?php

namespace Src\RouterBoard;

interface IRouterBoardMod
{

    /**
     * Add new IP address to backup list
     * @param InputParser $input - ip address
     */
    public function addNewIP(InputParser $input);

    /**
     * Delete IP address from backup list
     * @param InputParser $input - ip address
     */
    public function deleteIP(InputParser $input);

    /**
     * Change existing IP address in backup list
     * @param InputParser $input - old and new ip address in array
     */
    public function updateIP(InputParser $input);

}