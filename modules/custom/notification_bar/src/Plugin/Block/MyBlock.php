<?php

namespace Drupal\notification_bar\Plugin\Block;

use DateTime;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a notification bar functionality.
 *
 * @Block(
 *   id = "notification_bar",
 *   admin_label = @Translation("Notification Bar"),
 * )
 */
class MyBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    $now = date('Y-m-d');
    $current_month = lcfirst(date('M'));
    $due_year = date("Y");

    $q1_start = date( 'Y-m-d', strtotime("February 1"));
    $q1_end = date( 'Y-m-d', strtotime("April 30"));
    $q2_start = date('Y-m-d', strtotime("May 1"));
    $q2_end = date('Y-m-d',strtotime("July 31"));
    $q3_start = date('Y-m-d',strtotime("August 1"));
    $q3_end = date('Y-m-d',strtotime("October 31"));
    $q4_start = date('Y-m-d',strtotime("November 1"));

    if($current_month =='jan') {
      $q4_start = date('Y-m-d',strtotime("January 1"));
      $q4_end = date('Y-m-d', strtotime("January 31") );
    } else {
      $q4_end = date('Y-m-d', strtotime(date('Y-m-d', strtotime("January 31")) . "+ 1 year"));
      $due_year = $due_year + 1;
    }

    if( $now >= $q1_start && $now < $q1_end )
      $due_quarter = "Q1 - April 30, " . $due_year;
    if( $now >= $q2_start && $now < $q2_end )
      $due_quarter = "Q2 - July 31, " . $due_year;
    if( $now >= $q3_start && $now < $q3_end )
      $due_quarter = "Q3 - October 31, " . $due_year;
    if( $now >= $q4_start && $now < $q4_end )
      $due_quarter = "Q4 - January 31, " . $due_year;

    $output = "<h3>Forms Due:</h3>";

    if (\Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->hasRole('quarterly_data_entry')) {
      //$output = $output . "Affiliate Quarterly Finance and Governance: " . '<a href="/form/affiliates-quarterly-data">' . $due_quarter . '</a><br />';
      $output = $output . $due_quarter . ' - <a href=/form/affiliates-quarterly-data>Affiliate Quarterly Finance and Governance &#8250;' . '</a>' . '<br />';

    }

    if (\Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->hasRole('quarterly_data_entry')) {
      $output = $output . $due_quarter . ' - <a href=/form/affiliates-quarterly-program-sta>Affiliate Quarterly Program Statistics &#8250;'. '</a>'  . '<br />';
    }

    if (\Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->hasRole('affiliate_master_role')) {
      $output = $output . 'January 31, ' . $due_year . ' - <a href=/form/affiliates-annual-data>Affiliates Annual Data &#8250;' .'</a>' . '<br />';
    }

    if (\Drupal\user\Entity\User::load(\Drupal::currentUser()->id())->hasRole('hospital_partner')) {
      $output = $output . $due_quarter . '- <a href=/form/healthcare-partners-quarterly-da>Healthcare Partner Quarterly &#8250;'. '</a>'  . '<br />';
    }

    return [
      '#markup' => $output,
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['my_block_settings'] = $form_state->getValue('my_block_settings');
  }
}