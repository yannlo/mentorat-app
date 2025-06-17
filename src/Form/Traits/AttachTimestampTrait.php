<?php

namespace App\Form\Traits;

use App\Entity\Utils\AbstractTimestamp;
use Symfony\Component\Form\FormEvent;

trait AttachTimestampTrait
{
    private function attachTimetamps(FormEvent $event)
    {
        $data = $event->getData();

        if (! $data instanceof $this->classname  && $data instanceof AbstractTimestamp) {
            return;
        }

        $data->setUpdatedAt(new \DateTimeImmutable());

        if (!$data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
    }
}
