<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Manager;

use A2Global\A2Platform\Bundle\CoreBundle\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Yaml\Yaml;

class SettingsManager
{
    protected PropertyAccessor $propertyAccessor;

    public function __construct(
        protected ParameterBagInterface  $parameters,
        protected EntityManagerInterface $entityManager,
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function updateGlobalParameters()
    {
        $customParameters = [];

        $settings = $this->entityManager
            ->getRepository(Setting::class)
            ->createQueryBuilder('s')
            ->getQuery()
            ->getArrayResult();
        
        foreach ($settings as $setting) {
            $value = reset($setting['value']);
            $path = explode('.', $setting['name']);
            $propertyAccessorPath = '[' . Setting::PARAMETER_PREFIX . '][' . implode('][', $path) . ']';
            $this->propertyAccessor->setValue($customParameters, $propertyAccessorPath, $value);
        }
        $this->parameters->set(Setting::PARAMETER_PREFIX, $customParameters);
    }

    public function handleForm(FormInterface $form)
    {
        $this->handleSubForm($form);
        $this->flush();
    }

    public function updateSetting($path, $value, $andFlush = true)
    {
        $pathExploded = explode('.', $path);
        $propertyAccessorPath = '[' . implode('][', $pathExploded) . ']';
        $this->propertyAccessor->setValue($this->parameters, $propertyAccessorPath, $value);
        $setting = $this->entityManager->getRepository(Setting::class)->findOneBy([
            'name' => $path,
        ]);

        if (!$setting) {
            $setting = (new Setting())->setName($path);
            $this->entityManager->persist($setting);
        }
        $setting->setValue([$value]);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    protected function handleSubForm(FormInterface $form, $path = [])
    {
        if (count($form->all()) > 0) {
            foreach ($form->all() as $key => $subForm) {
                $this->handleSubForm($subForm, array_merge($path, [$key]));
            }

            return;
        }
        $this->updateSetting(implode('.', $path), $form->getData(), false);
    }

    public static function getCacheConfigFilepath($cacheDir)
    {
        return sprintf('%s/%s.yml', $cacheDir, Setting::CACHE_FILEPATH);
    }
}