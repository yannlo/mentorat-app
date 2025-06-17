<?php

namespace App\Tests\Resolver;

use App\Entity\Users\Student;
use App\Repository\User\StudentRepository;
use App\Resolver\PublicIdValueResolver;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PublicIdValueResolverIntegrationTest extends KernelTestCase
{
    private PublicIdValueResolver $resolver;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        /** @var ManagerRegistry $registry */
        $registry = $container->get(ManagerRegistry::class);

        $this->resolver = new PublicIdValueResolver($registry);
    }

    public function testSupportsAndResolveWithValidPublicIdParameter(): void
    {
        $container = static::getContainer();
        /** @var StudentRepository $sr */
        $publicIdValue = "1CQChf2dQt4goDWpTYsDmy"; // Exemple valide (doit correspondre à une ULID base58)

        $request = new Request([], [], ["publicIdSimplify" => $publicIdValue]);
        $argumentMetadata = new ArgumentMetadata('student', Student::class, false, false, null);

        // La méthode resolve doit retourner un tableau non vide avec l'entité
        $result = $this->resolver->resolve($request, $argumentMetadata);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Student::class, $result[0]);
    }

    public function testResolveReturnsEmptyArrayIfNoParameter(): void
    {
        $argumentName = 'student';

        $request = new Request(); // pas de paramètre dans l'URL
        $argumentMetadata = new ArgumentMetadata($argumentName, Student::class, false, false, null);

        $result = $this->resolver->resolve($request, $argumentMetadata);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testResolveThrowsNotFoundHttpExceptionOnInvalidPublicId(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $argumentName = 'student';
        $request = new Request([], [], ["publicIdSimplify" => 'invalid-base58']);
        $argumentMetadata = new ArgumentMetadata($argumentName, Student::class, false, false, null);

        $this->resolver->resolve($request, $argumentMetadata);
    }

    public function testResolveThrowsNotFoundHttpExceptionIfEntityNotFound(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $argumentName = 'student';
        $request = new Request(
            [],
            [],
            ["publicIdSimplify" => '7ZZZZZZZZZZZZZZZZZZZZZZZZZ']
        ); // ULID base58 non existant
        $argumentMetadata = new ArgumentMetadata(
            $argumentName,
            Student::class,
            false,
            false,
            null
        );

        $this->resolver->resolve($request, $argumentMetadata);
    }
}
