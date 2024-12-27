<?php

require_once 'swmactivities.civix.php';
use CRM_Swmactivities_ExtensionUtil as E;

function swmactivities_civicrm_alterMailParams(&$params, $context) {
  if ($context == 'singleEmail' && !empty($params['groupName'] && !empty($params['valueName']))) {
    $templates = CRM_Swmactivities_Form_Swmsettings::getSwmsettings();
    if (
      in_array($params['valueName'], $templates['swmactivities_templates']) && !empty($params['contactId'])) {
      try {
        $result = civicrm_api3('Activity', 'create', [
          'source_contact_id' => $params['contactId'],
          'target_contact_id' => $params['contactId'],
          'activity_type_id' => "Email",
          'subject' => "System Workflow Message Sent: {$params['subject']}",
          'details' => $params['text'],
        ]);
      }
      catch (CiviCRM_API3_Exception $e) {
        $error = $e->getMessage();
        CRM_Core_Error::debug_log_message(ts('API Error %1', array(
          'domain' => 'com.aghstrategies.swmactivities',
          1 => $error,
        )));
      }
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function swmactivities_civicrm_config(&$config) {
  _swmactivities_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function swmactivities_civicrm_install() {
  _swmactivities_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function swmactivities_civicrm_enable() {
  _swmactivities_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function swmactivities_civicrm_navigationMenu(&$menu) {
  _swmactivities_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _swmactivities_civix_navigationMenu($menu);
} // */
