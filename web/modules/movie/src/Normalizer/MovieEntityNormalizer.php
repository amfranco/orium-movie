<?php

namespace Drupal\movie\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Converts typed data objects to arrays.
 */
class MovieEntityNormalizer extends ContentEntityNormalizer {
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\movie\MovieInterface';


  /**
   * Constructs a MovieEntityNormalizer object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = array()) {
    $attributes = parent::normalize($entity, $format, $context);

    // Alter values.
    $attributes['id'] = !empty($attributes['id']) ? $attributes['id'][0]['value'] : '';
    $attributes['title'] = !empty($attributes['title']) ? $attributes['title'][0]['value'] : '';
    $attributes['release_date'] = !empty($attributes['release_date']) ? $attributes['release_date'][0]['value'] : '';

    if (!empty($attributes['field_genre'])) {
      // Get genre term ID.
      $genre_tid = $attributes['field_genre'][0]['target_id'];
      // Load term.
      $genre = $this->entityTypeManager->getStorage('taxonomy_term')->load($genre_tid);
      $attributes['genre'] = $genre->getName();
    }

    // Unset unused fields.
    unset($attributes['field_genre']);
    unset($attributes['uuid']);

    return $attributes;
  }
}
