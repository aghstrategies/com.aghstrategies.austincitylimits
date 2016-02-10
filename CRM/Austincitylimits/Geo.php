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

  /**
   * build the object
   *
   * @param float $lat
   *   latitude
   * @param float $long
   *   longitude
   */
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

  /**
   * finds the district of the address
   *
   * @return int
   *   the district
   */
  public function getDistrict() {
    foreach ($this->districts as $key => $district) {
      $addressGeos = $address->geos();
      if ($district->geos()->contains($addressGeos)) {
        return $key + 1;
      }
    }
    // Put it back into a geoPHP geometry
    $geometry = geoPHP::geosToGeometry($geos_result);

  }

}
