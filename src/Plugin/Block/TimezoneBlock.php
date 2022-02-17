<?php

namespace Drupal\timezone\Plugin\Block;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\timezone\TimezoneCalculation;

/**
 * Provides a 'Timezone: block' block.
 *
 * @Block(
 *   id = "timezone",
 *   admin_label = @Translation("Timezone")
 * )
 */
class TimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The timezone service.
   *
   * @var \Drupal\timezone\TimezoneCalculation
   */
  protected $timezone;

  /**
   * Constucts a TimezoneCalculation.
   *
   * @param array $configuration
   *   A configuration array containing information about the puglin instance.
   * @param string $plugin_id
   *   The plugin_id for the pulugin instance.
   * @param string $plugin_definition
   *   The pugin implementation defination.
   * @param \Drupal\timezone\TimezoneCalculation $timezone
   *   The timezone service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimezoneCalculation $timezone, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->timezone = $timezone;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('timezone.timezone_service'),
        $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   *
   * The return value of the build() method is a renderable array. Returning an
   * empty array will result in empty block contents. The front end will not
   * display empty blocks.
   */
  public function build() {
    $config = $this->configFactory->get('timezone.settings');
    $country = $config->get('country');
    $city = $config->get('city');
    $timezone = $config->get('timezone_select');
    $data = [];
    $data['country'] = $country;
    $data['city'] = $city;
    $data['timezone'] = $timezone;
    $build = [];
    $timezone_data = $this->timezone->getTimeCalculated($timezone);
    $data['date'] = $timezone_data;
    $build['#theme'] = 'timezone';
    $build['#data'] = $data;
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
