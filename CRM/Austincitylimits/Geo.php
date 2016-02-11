<?php

class CRM_Austincitylimits_Geo {

  /**
   * District Number for provided lat lng
   * @var int
   */
  public $district = NULL;

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
    $districts = $alldistricts->getComponents();
    $this->district = $this->getDistrict($districts);
  }

  /**
   * finds the district of the address
   *
   * @param array $districts
   *   geometery for all districts
   *
   * @return int
   *   the district
   */
  public function getDistrict($districts) {
    foreach ($districts as $key => $district) {
      $addressGeos = $address->geos();
      if ($district->geos()->contains($addressGeos)) {
        return $key + 1;
      }
    }
    // Put it back into a geoPHP geometry
    $geometry = geoPHP::geosToGeometry($geos_result);

  }

  /**
   *
   * Saves district number to contact
   *
   */
  public function saveDistrict($contactId) {
    try {
      $result = civicrm_api3('Contact', 'create', array(
        'custom_7' => $this->district,
        'id' => $contactId,
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error', array(
        'domain' => 'com.aghstrategies.austincitylimits',
        1 => $error,
      )));
    }
  }

  /**
   * Clears district field
   *
   * @param  int $contactId
   *  The contact Id
   *
   */
  public function deleteDistrict($contactId) {
    try {
      $result = civicrm_api3('Contact', 'create', array(
        'custom_7' => "",
        'id' => $contactId,
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error', array(
        'domain' => 'com.aghstrategies.austincitylimits',
        1 => $error,
      )));
    }
  }

}
