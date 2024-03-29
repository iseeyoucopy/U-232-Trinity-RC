<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */

/* Chmod class
 * Original idea from here http://gr.php.net/manual/en/function.chmod.php#77163
 *
 * Changed accordingly and improved for a mod on TBDEV.NET by Alex2005
*/

class Chmod
{
    private $_dir, $_modes = [
        'owner' => 0,
        'group' => 0,
        'public' => 0,
    ];

    public function Chmod($dir, $OwnerModes = [], $GroupModes = [], $PublicModes = [])
    {
        $this->_dir = $dir;
        $this->setOwnerModes($OwnerModes[0], $OwnerModes[1], $OwnerModes[2]);
        $this->setGroupModes($GroupModes[0], $GroupModes[1], $GroupModes[2]);
        $this->setPublicModes($PublicModes[0], $PublicModes[1], $PublicModes[2]);
    }

    private function setOwnerModes($read, $write, $execute)
    {
        $this->_modes['owner'] = $this->setMode($read, $write, $execute);
    }

    private function setMode($read, $write, $execute)
    {
        $mode = 0;
        if ($read) {
            $mode += 4;
        }
        if ($write) {
            $mode += 2;
        }
        if ($execute) {
            $mode += 1;
        }
        return $mode;
    }

    private function setGroupModes($read, $write, $execute)
    {
        $this->_modes['group'] = $this->setMode($read, $write, $execute);
    }

    private function setPublicModes($read, $write, $execute)
    {
        $this->_modes['public'] = $this->setMode($read, $write, $execute);
    }

    public function setChmod()
    {
        if (is_array($this->_dir)) {
            $return = [];
            foreach ($this->_dir as $dir) {
                $return[] = $this->returnValue($dir);
            }
            return $return;
        }

        return $this->returnValue($this->_dir);
    }

    private function returnValue($dir)
    {
        return (is_dir($dir) ? [
            'chmod',
            @chmod($dir, $this->getMode()),
            $this->getMode(),
            $dir,
        ] : [
            'mkdir',
            @mkdir($dir, $this->getMode()),
            $this->getMode(),
            $dir,
        ]);
    }

    private function getMode()
    {
        return $this->_modes['owner'] . $this->_modes['group'] . $this->_modes['public'];
    }
}

?>
