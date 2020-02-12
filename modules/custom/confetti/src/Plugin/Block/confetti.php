<?php

namespace Drupal\confetti\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with a notification bar functionality.
 *
 * @Block(
 *   id = "notification_bar",
 *   admin_label = @Translation("Notification Bar"),
 * )
 */
class confetti extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    <script>
      import ConfettiGenerator from "confetti-js";
      var confettiSettings = { target: 'my-canvas' };
      var confetti = new ConfettiGenerator(confettiSettings);
      confetti.render();
    </script>


    return [
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