<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

/*
    Les fixtures sont un jeu de données
    Elles servent à remplir la bdd juste après sa création,
    pour pouvoir manipuler des données dans mon code => c-a-d manipuler des entités
*/
class CategoryFixtures extends Fixture
{
    private SluggerInterface $slugger;

    #on utilise le __construct() car la fonction load() ne prend aucun autre parametre
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'politique',
            'Société',
            'Sport',
            'Cinema',
            'Mode',
            'Santé',
            'Hi-tech',
            'Economie',
            'Sciences',
            'Environnement'
        ];
        foreach($categories as $cat){
            $category = new Category();
            $category->setName($cat);
            $category->setAlias($this->slugger->slug($cat));
            $category->setCreatedAt(new DateTime());

            $manager->persist($category);

        }
        
        $manager->flush();
    }
}
