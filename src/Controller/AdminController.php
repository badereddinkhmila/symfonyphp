<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/login", name="admin_login")
     */
    public function adminlogin()
    {
        return $this->render('admin/login.html.twig');
    }

    /**
     * @Route("/login/error", name="login_error")
     */
    public function loginerror()
    {
        return $this->render('errors/login_error.html.twig');
    }

    /**
     * @Route("/home",name="admin_home")
     */
    public function adminhome()
    {
        return $this->render('admin/home.html.twig');
    }

    /**
     * @Route("/json/doctors",name="json_doctors",methods={"GET"})
     * @param UserRepository $repository
     * @return JsonResponse
     */
    public function Doctors(UserRepository $repository)
    {
        $doctors = $repository->findByisDoctor(true);
        $jsonData = array();
        $idx = 0;
        foreach ($doctors as $pt) {
            $temp = [
                'id' => $pt->getId(),
                'firstname' => $pt->getFirstname(),
                'lastname' => $pt->getLastname(),
                'avatar' => $pt->getAvatar(),
                'email' => $pt->getEmail(),
            ];
            $jsonData[$idx++] = $temp;
        }
        return new JsonResponse($jsonData);
    }

    /**
     * @Route("/docteurs",name="doctors_list")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param RoleRepository $rp
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function manage_users(Request $request, UserPasswordEncoderInterface $encoder, RoleRepository $rp)
    {

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        $manager = $this->getDoctrine()->getManager();
        // set default role as doctor
        $role = $rp->find(2);
        if ($form->isSubmitted() && $form->isValid()) {
            //upload d'image
            if (!empty($user->getAvatar())) {
                $image = $form->get('avatar')->getData();
                $fichier = " /uploads/" . md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);
                $user->setAvatar($fichier);
            }
            // encoder le mot de passe
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            // default values in form
            $user->setIsDoctor(true);
            $user->addUserRole($role);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le Docteur <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été crée"
            );
            return $this->redirectToRoute('doctors_list');
        }


        return $this->render('/admin/users/Doctorslist.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
