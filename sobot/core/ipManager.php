<?php 

namespace sobot\core;

class ipManager{
    function rangeToIp($cidr) {
      $range = array();
      $cidr = explode('/', $cidr);
      $range['lower'] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
      $range['higher'] = long2ip((ip2long($range['lower'])) + pow(2, (32 - (int)$cidr[1])) - 1);
      return $range;
    }
}