<?php

namespace App\Tests\Resolver;

use App\Resolver\PublicIdValueResolver;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Ulid;

class PublicIdValueResolverTest extends TestCase
{
    private ManagerRegistry&MockObject $registry;
    private PublicIdValueResolver $resolver;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->resolver = new PublicIdValueResolver($this->registry);
    }

    public function testResolveReturnsEntityWhenValidPublicId(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('entity', TestEntityWithTrait::class, false, false, null);

        // On simule que la route contient le paramètre avec la clé "entity:publicId"
        $ulid = new Ulid();
        $base58 = $ulid->toBase58();
        $request->attributes->set('publicIdSimplify', $base58);

        $entity = new TestEntityWithTrait();

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(["publicId" => (string) $ulid])
            ->willReturn($entity);

        $this->registry->expects($this->once())
            ->method('getRepository')
            ->with(TestEntityWithTrait::class)
            ->willReturn($repository);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertSame($entity, $result[0]);
    }

    public function testResolveReturnsEmptyIfArgumentHasNoType(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('entity', null, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEmpty($result);
    }

    public function testResolveReturnsEmptyIfClassDoesNotUseTrait(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('entity', TestEntityWithoutTrait::class, false, false, null);
        $request->attributes->set('entity:publicId', 'somevalue');

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEmpty($result);
    }

    public function testResolveReturnsEmptyIfRequestParamMissing(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('entity', TestEntityWithTrait::class, false, false, null);

        // Pas de paramètre 'entity:publicId' dans la requête
        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEmpty($result);
    }

    public function testResolveThrowsNotFoundHttpExceptionIfUlidInvalid(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Identifiant public invalide.');

        $request = new Request();
        $argument = new ArgumentMetadata('entity', TestEntityWithTrait::class, false, false, null);
        $request->attributes->set('publicIdSimplify', 'invalid-base58');

        // Pas besoin de mock repo car la conversion échoue avant
        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveThrowsNotFoundHttpExceptionIfEntityNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Objet introuvable.');

        $request = new Request();
        $argument = new ArgumentMetadata('entity', TestEntityWithTrait::class, false, false, null);

        $ulid = new Ulid();
        $base58 = $ulid->toBase58();
        $request->attributes->set('publicIdSimplify', $base58);

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(["publicId" => (string) $ulid])
            ->willReturn(null);

        $this->registry->expects($this->once())
            ->method('getRepository')
            ->with(TestEntityWithTrait::class)
            ->willReturn($repository);

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveReturnsEmptyIfPrimitiveTypeGiven(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('param', 'string', false, false, null);
        $request->attributes->set('publicIdSimplify', 'some-value');

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEmpty($result);
    }
}
