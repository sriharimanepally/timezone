<?php

namespace Drupal\timezone;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

/**
 * Timezone calculation Service.
 */
class TimezoneCalculation {

  use StringTranslationTrait;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * TimezoneCalculation constructor.
   *
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter.
   */
  public function __construct(TimeInterface $time, DateFormatterInterface $date_formatter) {
    $this->time = $time;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Returns the timezone.
   *
   * @return \Drupal\timezone\TranslatableMarkup
   *   The timezone.
   */
  public function getTimeCalculated($timezone) {
    $current_time = $this->time->getRequestTime();
    $date_format = $this->dateFormatter->format($current_time, 'custom', 'jS M Y - g:i A', $timezone);
    return $date_format;
  }

}
