<?php

require_once 'swmactivities.civix.php';
use CRM_Swmactivities_ExtensionUtil as E;

function swmactivities_civicrm_postEmailSend(&$params) {
  if (!empty($params['groupName'] && !empty($params['valueName']))) {
    $templates = CRM_Swmactivities_Form_Swmsettings::getSwmsettings();
    if (in_array($params['valueName'], $templates['swmactivities_templates'])) {
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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function swmactivities_civicrm_xmlMenu(&$files) {
  _swmactivities_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function swmactivities_civicrm_postInstall() {
  _swmactivities_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function swmactivities_civicrm_uninstall() {
  _swmactivities_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function swmactivities_civicrm_enable() {
  _swmactivities_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function swmactivities_civicrm_disable() {
  _swmactivities_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function swmactivities_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _swmactivities_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function swmactivities_civicrm_managed(&$entities) {
  _swmactivities_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function swmactivities_civicrm_caseTypes(&$caseTypes) {
  _swmactivities_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function swmactivities_civicrm_angularModules(&$angularModules) {
  _swmactivities_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function swmactivities_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _swmactivities_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function swmactivities_civicrm_entityTypes(&$entityTypes) {
  _swmactivities_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function swmactivities_civicrm_themes(&$themes) {
  _swmactivities_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function swmactivities_civicrm_preProcess($formName, &$form) {

} // */

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
