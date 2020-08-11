<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\RoleRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function adminlogin()
    {
        return $this->render('admin/login.html.twig');
    }

    /**
     * @Route("/admin/login/error", name="login_error")
     */
    public function loginerror(){
        return $this->render('errors/login_error.html.twig');
    }

    /**
     * @Route("/admin/home",name="admin_home")
     */
    public function adminhome()
    {
        return $this->render('admin/home.html.twig');
    }

     /**
     * @Route("/admin/logout",name="admin_logout")
     */

    public function logout(){
        return $this->redirectToRoute('welcome_page');
    }

    /**
     * @Route("/admin/docteurs/{page?1}",name="doctors_list",requirements={"page":"\d+"})
     */

     public function manage_users(PaginationService $ps,Request $request,UserPasswordEncoderInterface $encoder,RoleRepository $rp,$page){
   
            $user=new User();
            $form=$this->createForm(UserFormType::class,$user);
            $form->handleRequest($request);
            $manager=$this->getDoctrine()->getManager();
            // set default role as doctor 
            $role = $rp->findRole(2);
            if( $form->isSubmitted() && $form->isValid() ){
                //upload d'image
                if( !empty($user->getAvatar())){
                $image=$form->get('avatar')->getData();
                $fichier=" /uploads/" . md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'),$fichier);
                $user->setAvatar($fichier);
                }
                // encoder le mot de passe
                $hash=$encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($hash);
                // default values in form
                $user->setIsDoctor(true);
                $user->addUserRole($role[0]);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Le Docteur <strong>{$user->getFirstname()} {$user->getLastname()}</strong> a bien été crée"
                );
                return $this->redirectToRoute('doctors_list');}

        //pagination
        $ps->setEntityClass(User::class)
           ->setPage($page)
           ->setLimit(10);
        return $this->render('/admin/users/userslist.html.twig',[
            'users'=>$ps->getData(),
            'form'=>$form->createView(),
            'pages'=>$ps->getPages(),
            'page'=>$page,

        ]);
     }


}
