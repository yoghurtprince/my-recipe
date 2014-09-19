<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\DemoBundle\Form\RecipeType;

class DemoController extends Controller
{

    /**
     * @Route("/recipe", name="_demo_recipe")
     * @Template()
     */
    public function recipeAction(Request $request)
    {
        $form = $this->createForm(new RecipeType());
        $form->handleRequest($request);

        if ($form->isValid()) {
        	$foodItems = $form->get('item')->getData();
        	$recipes = $form->get('recipe')->getData();

        	$uploadDir = $this->container->get('kernel')->getRootdir().'/../web/uploads';
        	
        	$itemsFile = $foodItems->move(
		        $uploadDir,
		        $foodItems->getClientOriginalName());
        	
        	$recipeFile = $recipes->move(
        			$uploadDir,
        			$recipes->getClientOriginalName());
        	

        	$recipes = new \Acme\DemoBundle\Model\Recipes();
        	$recipes->loadFromJSON(file_get_contents($recipeFile));

        	$items = new \Acme\DemoBundle\Model\FridgeItems();
        	$items->loadFromCSVFile($itemsFile);
        	
        	$fridge = new \Acme\DemoBundle\Model\Fridge($items);
        	
        	$recipeFinder = new \Acme\DemoBundle\Model\Finder($fridge, $recipes);
        	
        	$results = $recipeFinder->findCookTonight()."\n";
        	
            $request->getSession()->getFlashBag()->set('notice', 'Recipe for dinner tonigh was generated.');

            return array("results"=>$results, 'form' => $form->createView());
        }

        return array('form' => $form->createView());
    }
}
