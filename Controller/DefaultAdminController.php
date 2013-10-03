<?php

namespace Pix\SortableBehaviorBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SortableAdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param integer $id
     * @param string $position
     */
    public function moveAction($id, $position)
    {
        $object = $this->admin->getObject($id);

        $position_service = $this->get('pix_sortable_behavior.position');
        $last_position = $position_service->getLastPosition();
        $position = $position_service->getPosition($object, $position, $last_position);

        $object->setPosition($position);
        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object)
            ));
        }
        $this->get('session')->setFlash('sonata_flash_info', 'Position mise à jour');

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

}