<?php

namespace Drupal\movie\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\movie\MovieInterface;
use Drupal\user\UserInterface;

/**
 * Defines the movie entity class.
 *
 * @ContentEntityType(
 *   id = "movie",
 *   label = @Translation("Movie"),
 *   label_collection = @Translation("Movies"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\movie\MovieListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\movie\Form\MovieForm",
 *       "edit" = "Drupal\movie\Form\MovieForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "movie",
 *   admin_permission = "administer movie",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/movie/add",
 *     "canonical" = "/movie/{movie}",
 *     "edit-form" = "/admin/content/movie/{movie}/edit",
 *     "delete-form" = "/admin/content/movie/{movie}/delete",
 *     "collection" = "/admin/content/movie"
 *   },
 *   field_ui_base_route = "entity.movie.settings"
 * )
 */
class Movie extends ContentEntityBase implements MovieInterface {

  /**
   * {@inheritdoc}
   *
   * When a new movie entity is created, set the uid entity reference to
   * the current user as the creator of the entity.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += ['uid' => \Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getReleaseDate() {
    return strtotime($this->get('release_date')->value);
  }

  /**
   * {@inheritdoc}
   */
  public function setReleaseDate($timestamp) {
    $this->set('release_date', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The movie title'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['release_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Release Date'))
      ->setDescription(t('Movie release date'))
      ->setRequired(TRUE)
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'datetime',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
