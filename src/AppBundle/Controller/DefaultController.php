<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Eintrag;

class DefaultController extends Controller {

    public $kategorien = [
        'Allgemein' => 'Allgemein',
        'Arbeit' => 'Arbeit',
        'Gesundheit' => 'Gesundheit',
        'Freunde' => 'Freunde',
        'Familie' => 'Familie',
        'Interessen' => 'Interessen'
    ];
    public $monate = [
        1 => "Januar",
        2 => "Februar",
        3 => "März",
        4 => "April",
        5 => "Mai",
        6 => "Juni",
        7 => "Juli",
        8 => "August",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Dezember"];

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/blog/{year}/{month}", name="blog")
     */
    public function blogAction($year = 1700, $month = "Hanswurst") {
        $jahr_akt = date("Y");
        $mon_akt = date("F");
        if ($year == 1700)
            $year = $jahr_akt;
        if ($month == "Hanswurst")
            $month = $mon_akt;
        $eintr = $this->getDoctrine()
                ->getRepository('AppBundle\Entity\Eintrag')
                ->findAll();
//                ->findBy($criteria)
        $eintr = array_reverse($eintr);

        return $this->render('default/blog.html.twig', array(
                    'eintr' => $eintr,
                    'year' => $year,
                    'month' => $month,
                    'monate' => $this->monate
        ));
    }

    /**
     * @Route("/neu", name="neu")
     */
    public function neuAction(Request $request) {
        $eintrag = new Eintrag();
        $form = $this->createFormBuilder($eintrag)
                ->add('ueberschrift', TextType::class, array(
                    'label' => 'Überschrift',
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%'
                    )
                ))
                ->add('kategorie', ChoiceType::class, [
                    'choices' => $this->kategorien,
                    'attr' => ['class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%']
                        ]
                )
                ->add('text', TextareaType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%'
                    )
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'abschicken',
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $ueberschrift = $form['ueberschrift']->getData();
            $kategorie = $form['kategorie']->getData();
            $text = $form['text']->getData();

            $eintrag->setUeberschrift($ueberschrift);
            $eintrag->setKategorie($kategorie);
            $eintrag->setText($text);
            $eintrag->setDatum(date('Y-m-d H:i:s'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($eintrag);
            $em->flush();


            return $this->redirect('blog');
        }

        return $this->render('default/neu.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/bearbeiten/{id}", name="bearbeiten")
     */
    public function bearbeitenAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $eintr = $em->getRepository('AppBundle\Entity\Eintrag')->find($id);

        $form = $this->createFormBuilder($eintr)
                ->add('ueberschrift', TextType::class, array(
                    'label' => 'Überschrift',
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%'
                    )
                ))
                ->add('text', TextareaType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%'
                    )
                ))
                ->add('kategorie', ChoiceType::class, [
                    'choices' => $this->kategorien,
                    'attr' => ['class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%']
                        ]
                )
                ->add('datum', DateTimeType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'style' => 'margin-bottom:1.5em; width:90%',
                    //'disabled' => 'true'
                    )
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'ändern',
                    'attr' => array(
                        'class' => 'btn btn-primary'
                    )
                ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $ueberschrift = $form['ueberschrift']->getData();
            $kategorie = $form['kategorie']->getData();
            $text = $form['text']->getData();
            $datum =$form['datum']->getData();

            $eintr->setUeberschrift($ueberschrift);
            $eintr->setKategorie($kategorie);
            $eintr->setText($text);
            $eintr->setDatum($datum);

            $em->persist($eintr);
            $em->flush();

            return $this->redirectToRoute('blog');
        }

        return $this->render('default/bearbeiten.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/löschen/{id}", name="loeschen")
     */
    //eine Request brauche ich nur, wenn etwas in die DB eingetragen wird?
    public function loeschenAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $eintr = $em->getRepository('AppBundle\Entity\Eintrag')->find($id);
        $em->remove($eintr);
        $em->flush();

        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/test", name="testpage")
     */
    public function testAction(Request $request) {
        // replace this example code with whatever you need
        // die('ich bin Test');
        return $this->render('default/test.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR
        ]);
    }

    /**
     * @Route("/bootstrap", name="bootstrap")
     */
    public function bootstrapAction(Request $request) {
        // replace this example code with whatever you need
        // die('ich bin Test');
        return $this->render('default/bootstrap.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR
        ]);
    }
    /**
     * @Route("/paramtest/{name}", name="paramtest")
     */
    public function testmitparamAction($name = "anton") {
        // replace this example code with whatever you need
        return $this->render('default/testmitparam.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR
        ]);
    }

}
