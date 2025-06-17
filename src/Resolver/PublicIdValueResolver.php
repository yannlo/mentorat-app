<?php

namespace App\Resolver;

use App\Entity\Traits\HasPublicIdTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionClass;

class PublicIdValueResolver implements ValueResolverInterface
{
    private readonly string $paramName;

    public function __construct(
        private readonly ManagerRegistry $registry
    ) {
        $this -> paramName = "publicIdSimplify";
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $expectedClass = $argument->getType();

        if ($expectedClass === null) {
            return [];
        }



        // Vérifier si la classe utilise le trait HasPublicIdTrait
        if (!$this->classUsesTraitRecursive($expectedClass, HasPublicIdTrait::class)) {
            return [];
        }



        // Ce resolver ne s'applique que si la route a un paramètre de type {variable:publicId}

        if (!$request->attributes->has($this->paramName)) {
            return [];
        }



        $value = $request->attributes->get($this->paramName);


        // Essayer de convertir la valeur Base58 en ULID
        try {
            $ulid = Ulid::fromBase58($value);
        } catch (\Throwable) {
            throw new NotFoundHttpException('Identifiant public invalide.');
        }

        // Chercher l'entité par son ULID
        $repository = $this->registry->getRepository($expectedClass);
        $entity = $repository->findOneBy(["publicId" => (string) $ulid]);

        if (!$entity) {
            throw new NotFoundHttpException('Objet introuvable.');
        }

        return [$entity];
    }


    private function classUsesTraitRecursive(string $class, string $trait): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $reflection = new ReflectionClass($class);

        do {
            $traits = $reflection->getTraits();
            foreach ($traits as $usedTrait) {
                if ($usedTrait->getName() === $trait) {
                    return true;
                }
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection !== false);

        return false;
    }
}
