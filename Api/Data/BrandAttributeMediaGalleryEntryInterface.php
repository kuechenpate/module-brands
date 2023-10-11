<?php
/**
 * Product Media Attribute
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kuechenpate\Brands\Api\Data;

use Magento\Framework\Api\Data\ImageContentInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @api
 * @since 100.0.2
 */
interface BrandAttributeMediaGalleryEntryInterface extends ExtensibleDataInterface
{
    const ID = 'id';
    const LABEL = 'label';
    const POSITION = 'position';
    const DISABLED = 'disabled';
    const TYPES = 'types';
    const MEDIA_TYPE = 'media_type';
    const FILE = 'file';
    const CONTENT = 'content';

    /**
     * Retrieve gallery entry ID
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set gallery entry ID
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static;

    /**
     * Get media type
     *
     * @return string
     */
    public function getMediaType(): string;

    /**
     * Set media type
     *
     * @param string $mediaType
     * @return $this
     */
    public function setMediaType(string $mediaType): static;

    /**
     * Retrieve gallery entry alternative text
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Set gallery entry alternative text
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): static;

    /**
     * Retrieve gallery entry position (sort order)
     *
     * @return int
     */
    public function getPosition(): int;

    /**
     * Set gallery entry position (sort order)
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): static;

    /**
     * Check if gallery entry is hidden from product page
     *
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * Set whether gallery entry is hidden from product page
     *
     * @param bool $disabled
     * @return $this
     */
    public function setDisabled(bool $disabled): static;

    /**
     * Retrieve gallery entry image types (thumbnail, image, small_image etc.)
     *
     * @return string[]
     */
    public function getTypes(): array;

    /**
     * Set gallery entry image types (thumbnail, image, small_image etc.)
     *
     * @param string[] $types
     * @return $this
     */
    public function setTypes(array $types = null): static;

    /**
     * Get file path
     *
     * @return string|null
     */
    public function getFile(): ?string;

    /**
     * Set file path
     *
     * @param string $file
     * @return $this
     */
    public function setFile(string $file): static;

    /**
     * Get media gallery content
     *
     * @return ImageContentInterface|null
     */
    public function getContent(): ?ImageContentInterface;

    /**
     * Set media gallery content
     *
     * @param ImageContentInterface $content
     * @return $this
     */
    public function setContent(ImageContentInterface $content): static;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return BrandAttributeMediaGalleryEntryExtensionInterface|null
     */
    public function getExtensionAttributes(): ?BrandAttributeMediaGalleryEntryExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param BrandAttributeMediaGalleryEntryExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        BrandAttributeMediaGalleryEntryExtensionInterface $extensionAttributes
    ): static;
}
