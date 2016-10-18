<?php

/**
 * Austincitylimits.Citycouncildistrict API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_austincitylimits_Citycouncildistrict_spec(&$spec) {
  $spec['magicword']['api.required'] = 1;
}

/**
 * Austincitylimits.Citycouncildistrict API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_austincitylimits_Citycouncildistrict($params) {
  $params = array(
    'sequential' => 1,
    'custom_59' => array('IS NULL' => 1),
    'geo_code_1' => array('IS NOT NULL' => 1),
    'geo_code_2' => array('IS NOT NULL' => 1),
    'state_province_id' => 1042,
    'location_type_id' => 1,
  );
  // THIS WILL ONLY GET Contacts whose PRIMARY ADDRESS is home

  // TODO fix options bassed on API
  // if ($options) {
  //   $params['options'] = $options;
  // }
  try {
    $addresses = civicrm_api3('Contact', 'get', $params);
  }
  catch (CiviCRM_API3_Exception $e) {
    throw new API_Exception(/*errorMessage*/ 'Everyone knows that the magicword is "sesame"', /*errorCode*/ 1234);
    $error = $e->getMessage();
    CRM_Core_Error::debug_log_message(ts('API Error %1', array(
      'domain' => 'com.aghstrategies.austincitylimits',
      1 => $error,
    )));
  }
  foreach ($addresses['values'] as $address) {
    $geo = new CRM_Austincitylimits_Geo($address['geo_code_1'], $address['geo_code_2']);
    $geo->saveDistrict($address['contact_id']);
  }
}
