<?php

class CRM_Austincitylimits_Geo {

  /**
   * An array of geometries for each council district.
   *
   * @var array
   */
  public $districts = array();

  /**
   * The geometry of the address.
   *
   * @var geoPHP geometry
   */
  public $address = NULL;

  public function __construct($lat, $long) {
    //load geoPHP library
    include_once 'packages/geoPHP/geoPHP.inc';

    // find point of address
    $point = array(
      'type' => 'Point',
      'coordinates' => array($lat, $long),
    );
    $pointJson = json_encode($point);
    $this->address = geoPHP::load($pointJson, 'json');

    //find districts
    $austinJson = file_get_contents(CRM_Core_Resources::singleton()->getUrl('com.aghstrategies.austincitylimits', 'CRM/Austincitylimits/Districts.geojson'));
    $alldistricts = geoPHP::load($austinJson, 'json');
    $this->districts = $alldistricts->getComponents();
  }

}
