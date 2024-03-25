<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishFormType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class WishController extends AbstractController
{

    #[Route('/detail/{id}', name: 'app_detail')]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }
    #[Route('/wish/{page}', name: 'app_wish', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function list(WishRepository $wishRepository, int $page): Response
    {
        if ($page < 1) {
            throw new NotFoundHttpException('impossible');
        }


        $offset = ($page - 1) * 3;

        $wishes = $wishRepository->pagination($offset);

        $nbSeriesMax = count($wishRepository->pagination());

        $pagesMax = ceil($nbSeriesMax / 3);

        return $this->render('wish/wish.html.twig', [
            'wishes' => $wishes,
            'currentPage' => $page,
            'pagesMax' => $pagesMax,
        ]);
    }

    #[Route('/create', name:'app_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger) : Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishFormType::class, $wish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($form->get('poster_file')->getData() instanceof UploadedFile){
                $posterFile = $form->get('poster_file')->getData();
                $fileName = $slugger->slug($wish->getTitre()).'-'.uniqid() .'.'.$posterFile->guessExtension();
                $posterFile->move('posters/wish/',$fileName);
                $wish->setPoster($fileName);
	        }
            $em->persist($wish);
            $em->flush();

            $this->addFlash('succes', 'enregistrement réussi');

        return $this->redirectToRoute('app_detail',['id'=> $wish->getId()]);
        }

        return $this->render('wish/wishForm.html.twig',[
            'wishForm' => $form,
        ]);
    }

    #[Route('/delete/{id}', name:'app_delete')]
    public function delete(EntityManagerInterface $em, Wish $wish) : Response
    {
        $em->remove($wish);
        $em->flush();
        $this->addFlash('success', 'Liste supprimé avec succès.');

        return $this->redirectToRoute('app_wish');
    }

    #[Route('/wish/{id}/edit', name:'app_edit')]
    public function edit(EntityManagerInterface $em, Request $request, Wish $wish, SluggerInterface $slugger) : Response
    {
        $form = $this->createForm(WishFormType::class)
                     ->setData($wish);
        $form->handleRequest($request);

    if($form->isSubmitted()&& $form->isValid() ){

        if($form->get('poster_file')->getData() instanceof UploadedFile){
            $posterFile = $form->get('poster_file')->getData();
            $fileName = $slugger->slug($wish->getTitre()).'-'.uniqid() .'.'.$posterFile->guessExtension();
            $posterFile->move('posters/wish/',$fileName);

            if ($wish->getPoster() && file_exists('posters/wish/' . $wish->getPoster())) {
                unlink('posters/wish/' . $wish->getPoster());
            }
            $wish->setPoster($fileName);
        }
        $em->persist($wish);
        $em->flush();
        $this->addFlash('success', 'Liste modifié avec succès.');

    return $this->redirectToRoute('app_detail', ['id' => $wish->getId()]);
    }
    return $this->render('wish/wishForm.html.twig',[
       'wishForm' => $form->createView()
    ]);
    }


}


