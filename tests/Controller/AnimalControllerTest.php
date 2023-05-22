<?php

namespace App\Test\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnimalControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AnimalRepository $repository;
    private string $path = '/animal/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Animal::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Animal index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'animal[nom]' => 'Testing',
            'animal[tailleMoyenne]' => 'Testing',
            'animal[dureeDeVieMoyenne]' => 'Testing',
            'animal[artMartial]' => 'Testing',
            'animal[numeroTelephone]' => 'Testing',
            'animal[pays]' => 'Testing',
        ]);

        self::assertResponseRedirects('/animal/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Animal();
        $fixture->setNom('My Title');
        $fixture->setTailleMoyenne('My Title');
        $fixture->setDureeDeVieMoyenne('My Title');
        $fixture->setArtMartial('My Title');
        $fixture->setNumeroTelephone('My Title');
        $fixture->setPays('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Animal');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Animal();
        $fixture->setNom('My Title');
        $fixture->setTailleMoyenne('My Title');
        $fixture->setDureeDeVieMoyenne('My Title');
        $fixture->setArtMartial('My Title');
        $fixture->setNumeroTelephone('My Title');
        $fixture->setPays('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'animal[nom]' => 'Something New',
            'animal[tailleMoyenne]' => 'Something New',
            'animal[dureeDeVieMoyenne]' => 'Something New',
            'animal[artMartial]' => 'Something New',
            'animal[numeroTelephone]' => 'Something New',
            'animal[pays]' => 'Something New',
        ]);

        self::assertResponseRedirects('/animal/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getTailleMoyenne());
        self::assertSame('Something New', $fixture[0]->getDureeDeVieMoyenne());
        self::assertSame('Something New', $fixture[0]->getArtMartial());
        self::assertSame('Something New', $fixture[0]->getNumeroTelephone());
        self::assertSame('Something New', $fixture[0]->getPays());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Animal();
        $fixture->setNom('My Title');
        $fixture->setTailleMoyenne('My Title');
        $fixture->setDureeDeVieMoyenne('My Title');
        $fixture->setArtMartial('My Title');
        $fixture->setNumeroTelephone('My Title');
        $fixture->setPays('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/animal/');
    }
}
