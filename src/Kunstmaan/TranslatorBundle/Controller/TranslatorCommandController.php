<?php

namespace Kunstmaan\TranslatorBundle\Controller;

use Kunstmaan\AdminBundle\FlashMessages\FlashTypes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kunstmaan\TranslatorBundle\Model\Import\ImportCommand;

class TranslatorCommandController extends Controller
{
    /**
     * @Route("/clear-cache", name="KunstmaanTranslatorBundle_command_clear_cache")
     */
    public function clearCacheAction()
    {

        $this->get('kunstmaan_translator.service.translator.resource_cacher')->flushCache();
        $this->get('session')->getFlashBag()->add(FlashTypes::SUCCESS, $this->get('translator')->trans('kuma_translator.command.clear.flash.success'));

        return new RedirectResponse($this->generateUrl('KunstmaanTranslatorBundle_settings_translations'));
    }

    /**
     * @Route("/import", name="KunstmaanTranslatorBundle_command_import")
     */
    public function importAction()
    {
        $importCommand = new ImportCommand();
        $importCommand
            ->setForce(false)
            ->setDefaultBundle($this->container->getParameter('kuma_translator.default_bundle'))
            ->setBundles($this->container->getParameter('kuma_translator.bundles'))
            ->setGlobals(true);

        $this->get('kunstmaan_translator.service.importer.command_handler')->executeImportCommand($importCommand);

        $this->get('session')->getFlashBag()->add(FlashTypes::SUCCESS, $this->get('translator')->trans('kuma_translator.command.import.flash.success'));

        return new RedirectResponse($this->generateUrl('KunstmaanTranslatorBundle_settings_translations'));
    }

    /**
     * @Route("/import-forced", name="KunstmaanTranslatorBundle_command_import_forced")
     */
    public function importForcedAction()
    {
        $importCommand = new ImportCommand();
        $importCommand
            ->setForce(true)
            ->setDefaultBundle($this->container->getParameter('kuma_translator.default_bundle'))
            ->setBundles($this->container->getParameter('kuma_translator.bundles'))
            ->setGlobals(false);

        $this->get('kunstmaan_translator.service.importer.command_handler')->executeImportCommand($importCommand);

        $this->get('session')->getFlashBag()->add(FlashTypes::SUCCESS, $this->get('translator')->trans('kuma_translator.command.import.flash.force_success'));

        return new RedirectResponse($this->generateUrl('KunstmaanTranslatorBundle_settings_translations'));
    }
}
