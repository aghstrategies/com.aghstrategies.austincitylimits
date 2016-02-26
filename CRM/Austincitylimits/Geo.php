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
      'coordinates' => array($long, $lat),
    );
    $pointJson = json_encode($point);
    $this->address = geoPHP::load($pointJson, 'json');

    // Load districts.
    $austinJson = file_get_contents(CRM_Core_Resources::singleton()->getUrl('com.aghstrategies.austincitylimits', 'CRM/Austincitylimits/Districts.geojson'));

    // Split districts geometry into separate features.
    $austinArray = json_decode($austinJson, TRUE);
    $districts = $austinArray['features'];
    foreach ($districts as &$district) {
      // Put back to JSON and load to geoPHP.
      $district = json_encode($district);
      $district = geoPHP::load($district, 'json');
    }
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
      if ($district->contains($this->address)) {
        // We know that the districts are all in order in the file.
        return $key + 1;
      }
    }

  }

  /**
   *
   * Saves district number to contact
   *
   */
  public function saveDistrict($contactId) {
    try {
      $result = civicrm_api3('Contact', 'create', array(
        'custom_59' => $this->district,
        'id' => $contactId,
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error: %1', array(
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
  public static function deleteDistrict($contactId) {
    try {
      $result = civicrm_api3('Contact', 'create', array(
        'custom_59' => "",
        'id' => $contactId,
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error: %1', array(
        'domain' => 'com.aghstrategies.austincitylimits',
        1 => $error,
      )));
    }
  }

  /**
   * Remove the field value from the submitted form values.
   *
   * @param  string $formName
   *   Form name
   * @param  CRM_Contact_Form_Contact|CRM_Contact_Form_Inline_CustomData $form
   *   The form object
   * @param  string $districtField
   *   The name of the district field to clear
   */
  public static function dontSaveDistrict($formName, &$form, $districtField) {
    $data = &$form->controller->container();
    if ($formName == 'CRM_Contact_Form_Inline_CustomData') {
      unset($data['values']['CustomData'][$districtField]);
    }
    else {
      unset($data['values']['Contact'][$districtField]);
    }
  }

}
