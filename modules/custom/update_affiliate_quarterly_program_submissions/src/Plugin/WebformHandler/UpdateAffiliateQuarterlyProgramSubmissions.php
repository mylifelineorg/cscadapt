<?php

namespace Drupal\update_affiliate_quarterly_program_submissions\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Url;

use Drupal\taxonomy\Entity\Term;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\Entity\User;

/**
 *
 *
 * @WebformHandler(
 *   id = "Affiliate Quarterly Program Submissions",
 *   label = @Translation("Update Affiliate Quarterly Program Submissions"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Updates Affiliates and Healthcare Partner taxonomy with form submission status"),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class UpdateAffiliateQuarterlyProgramSubmissions extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */

  // Function to be fired after submitting the Webform.
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    // Get an array of the values from the submission.
    $values = $webform_submission->getData();

    //get full user object and corresponding term ID of affiliate/healthcare partner taxonomy
    $userCurrent = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($userCurrent->id());
    $userAffiliateTID = $user->get('field_affiliate_healthcare_part')->target_id;

    //variables to assist in retrieving submission URL
    $sid = $webform_submission->id();
    $webform = $this->getWebform();
    $wid = $webform->id();

    //get submission URL
    $submission_url = Url::fromRoute('entity.webform_submission.canonical', ['webform' => $wid, 'webform_submission' => $sid], ['absolute' => TRUE])->toString();

    //load user's affiliate Term object
    $term = Term::load($userAffiliateTID);

    //append submission URL and quarter and year to title of link field
    $term->get(field_affiliate_quarterly_progra)->appendItem([
      'uri' => $submission_url,
      'title' => $values['quarter'] . ' - ' . $values['year'],
      'options' => [
        'attributes' => [
          'target' => '_blank',
        ],
      ],
    ]);

    $term->Save();





  }
}