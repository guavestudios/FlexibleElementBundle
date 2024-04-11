<?php

namespace Guave\FlexibleElementBundle\Elements;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\ContentModel;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\System;

class ContentFlexibleElement extends ContentElement
{
    /** @var string */
    protected $strTemplate = 'ce_flexibleelement';

    /**
     * {@inheritDoc}
     */
    protected function compile(): void
    {
        $this->Template->flexibleImage = self::prepareImages($this->getModel(), 'orderSRC');
    }

    /**
     * {@inheritDoc}
     */
    public function generate(): string
    {
        if ($this->customTpl) {
            $tmplStr = str_replace('.html5', '', $this->customTpl);
        } else {
            $tmpl = static::getTemplateByLayout($this->elementTemplate);
            $tmplStr = '';
            if (!empty($tmpl['template'])) {
                $tmplStr = $tmpl['template'];
            }
        }

        $this->strTemplate = $tmplStr;

        if (TL_MODE === 'BE') {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new BackendTemplate($this->strTemplate);

            $this->Template->title = $this->flexibleTitle;
            $this->Template->wildcard = $this->flexibleSubtitle;

            return $this->Template->parse();
        }

        return parent::generate();
    }

    public static function getIconPath()
    {
        return $GLOBALS['TL_FLEXIBLEELEMENT']['iconPath'];
    }

    /**
     * @deprecated
     */
    public static function getBackendMap(): array
    {
        $base = static::getIconPath();
        $arr = [];
        $templates = &$GLOBALS['TL_FLEXIBLEELEMENT']['templates'];
        foreach ($templates as $tmpl) {
            $arr[] = $base . $tmpl['id'];
        }

        return $arr;
    }

    public static function getTemplateByLayout($layout)
    {
        $templates = &$GLOBALS['TL_FLEXIBLEELEMENT']['templates'];
        foreach ($templates as $tmpl) {
            if ($tmpl['id'] === $layout) {
                return $tmpl;
            }
        }

        return null;
    }

    public static function prepareImages(ContentModel $model, string $attribute): array
    {
        if ($model->$attribute === null) {
            return [];
        }

        if (!$model->$attribute || !\is_array(StringUtil::deserialize($model->$attribute))) {
            return self::getImageData(FilesModel::findByUuid($model->$attribute));
        }

        $images = [];
        $files = FilesModel::findMultipleByUuids(StringUtil::deserialize($model->$attribute));

        foreach ($files as $file) {
            $images[] = self::getImageData($file);
        }

        return $images;
    }

    public static function getImageData(FilesModel $model): array
    {
        $rootDir = System::getContainer()->getParameter('kernel.project_dir');

        if (!is_file($rootDir . '/' . $model->path)) {
            return [];
        }

        if ($model->meta) {
            $meta = StringUtil::deserialize($model->meta);
            $meta = $meta[$GLOBALS['TL_LANGUAGE']];
        } else {
            $meta['title'] = $model->title;
        }

        return [
            'src' => '/' . $model->path,
            'name' => $model->name,
            'title' => $meta['title'],
        ];
    }
}
