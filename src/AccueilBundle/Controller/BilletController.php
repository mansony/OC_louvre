<?php

namespace AccueilBundle\Controller;


use AccueilBundle\Entity\Billet;
use AccueilBundle\Entity\Reservation;
use AccueilBundle\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AccueilBundle\Form\Type\ReservationType;
use AccueilBundle\Form\Type\ReservationfType;

class BilletController extends Controller
{
    public function dateAction()
    {
        return $this->render('AccueilBundle:Billet:jour.html.twig');
    }

    public function datefAction()
    {
        return $this->render('AccueilBundle:Billet:jourf.html.twig');
    }

    public function billetAction(Request $request, $date, $dj, $places)
    {
        $em = $this->getDoctrine()->getManager();
        $prix_total = 0;


        $nb_billets = $em->getRepository('AccueilBundle:Billet')->getReservationDay($date);
        if(($places + $nb_billets) > 1000) {
            return $this->redirectToRoute('date_reserve');
        }

        $reservation = new Reservation();

        for($i = 0; $i != $places; $i++) {
            $billets[$i] = new Billet();
            $reservation->getBillet()->add($billets[$i]);
            $billets[$i]->setReservation($reservation);
        }

        $form = $this->createForm(ReservationType::class, $reservation);


        if ($form->handleRequest($request)->isValid()) {
            $reservation->setDemiJournee($dj);
            $date = date_create_from_format('Y-m-d', $date);
            $reservation->setDateResa($date);
            for($i = 0; $i != $places; $i++)
            {

                $this->container->get('accueil.attribution')->attributionTarifs($billets[$i]);

                $code = $billets[$i]->getNom().$billets[$i]->getId().$billets[$i]->getPrenom();
                $billets[$i]->setCode($code);

                $em->persist($billets[$i]);
            }

            //$this->container->get('accueil.attribution')->attributionFamille($reservation);

            for($i = 0; $i != $places; $i++)
            {
                $prix = $billets[$i]->getTarifs()->getPrix();
                $prix_total = $prix_total + $prix;
            }
            $reservation->setPrixTotal($prix_total);
            $em->persist($reservation);
            $em->flush();

            return $this->redirect($this->generateUrl('resume', array('id' => $reservation->getId())));
        }
        return $this->render('AccueilBundle:Billet:reservation.html.twig', array(
            'date' => $date,
            'dj' => $dj,
            'places' => $places,
            'form' => $form->createView()
        ));
    }

    public function billetfAction(Request $request, $date, $dj, $nom, $pays)
    {
        $em = $this->getDoctrine()->getManager();
        $prix_total = 35;

        $nb_billets = $em->getRepository('AccueilBundle:Billet')->getReservationDay($date);
        if((4 + $nb_billets) > 1000) {
            return $this->redirectToRoute('date_reserve');
        }
        $reservation = new Reservation();

        for($i = 0; $i != 4; $i++) {
            $billets[$i] = new Billet();
            $reservation->getBillet()->add($billets[$i]);
            $billets[$i]->setReservation($reservation);
        }
        $form = $this->createForm(ReservationfType::class, $reservation);

        if ($form->handleRequest($request)->isValid()) {
            $reservation->setDemiJournee($dj);
            $date = date_create_from_format('Y-m-d', $date);
            $reservation->setDateResa($date);
            for($i = 0; $i != 4; $i++)
            {
                 $this->container->get('accueil.attribution')->attributionTarifs($billets[$i]);
                $code = $billets[$i]->getNom().$billets[$i]->getId().$billets[$i]->getPrenom();
                $billets[$i]->setCode($code);
                $billets[$i]->setNom($nom);
                $billets[$i]->setPays($em->getRepository('AccueilBundle:Pays')->findOneByNomFr($pays));
                $billets[$i]->setTarifReduit(0);
                $em->persist($billets[$i]);
            }
            $reservation->setPrixTotal($prix_total);
            $em->persist($reservation);
            $em->flush();

            return $this->redirect($this->generateUrl('resumef', array('idf' => $reservation->getId())));
        }
        return $this->render('AccueilBundle:Billet:reservationf.html.twig', array(
            'date' => $date,
            'dj' => $dj,
            'nom' => $nom,
            'pays' => $pays,
            'form' => $form->createView()
        ));
    }

    public function recapAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $reservation = $em->getRepository('AccueilBundle:Reservation')->find($id);

        return $this->render('AccueilBundle:Billet:resume.html.twig', array(
            'reservation' => $reservation
        ));
    }

     public function recapfAction($idf)
    {
        $em = $this->getDoctrine()->getManager();

        $reservation = $em->getRepository('AccueilBundle:Reservation')->find($idf);

        return $this->render('AccueilBundle:Billet:resumef.html.twig', array(
            'reservation' => $reservation
        ));
    }


    public function valideAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $stripe = $request->request->get('stripeToken');
        $reservation = $em->getRepository('AccueilBundle:Reservation')->find($id);
        $reservation->setStripeToken($stripe);
        $em->persist($reservation);
        $em->flush();


        $html = $this->renderView('AccueilBundle:Billet:billet.html.twig', array(
            'reservation'  => $reservation));

        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $html2pdf->pdf->Output($this->container->getParameter('kernel.root_dir').'/../web/PDF/billet_'.$id.'.pdf', 'F');

  
        $message = \Swift_Message::newInstance()
            ->setSubject('Reservation des billets du Musée du Louvre')
            ->setFrom(array('virtual.triathlon.manager@gmail.com' => 'Musée du Louvre'))
            ->setTo($reservation->getEmail())
            ->setBody('Veillez verifier vos pièces jointes')
            ->attach(\Swift_Attachment::fromPath('PDF/billet_'.$id.'.pdf'));
        $this->get('mailer')->send($message);

        return $this->render('AccueilBundle:Billet:commande.html.twig');
    }
}
