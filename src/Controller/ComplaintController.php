<?php
namespace App\Controller;
use App\Entity\Complaint;
use App\Form\ComplaintsType;
use App\Repository\ComplaintRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class ComplaintController extends AbstractController
{
    /**
     * @Route("/complaint", name="complaint_home")
     *
     */
    public function index(Request $request,RoleRepository $rp,UserRepository $urp): Response
    {
        $complaint = new Complaint();
        $errors=false;
        $entityManager=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $complaintForm = $this->createForm(ComplaintsType::class,$complaint);
        $complaintForm->handleRequest($request);
        if ($complaintForm->isSubmitted() && !$complaintForm->isValid()) {
            $errors=true;
        }
        if($complaintForm->isSubmitted() && $complaintForm->isValid()){
            $errors=false;
            $complaint->setComplaintCreator($user);
            $entityManager->persist($complaint);
            $entityManager->flush();
            $this->addFlash(
                'success',
                "La réclamation a bien été crée."
            );
            return $this->redirectToRoute('complaint_home');
        }
        return $this->render('complaint/index.html.twig', [
            'form' => $complaintForm->createView(),
            'errors'=>$errors
        ]);
    }

    /**
     * @Route("/complaint/list",name="complaint_list")
     * @param RoleRepository $role_rp
     * @param ComplaintRepository $comp_rp
     * @return JsonResponse
     */
    public function complaintsList(RoleRepository $role_rp,ComplaintRepository $comp_rp): JsonResponse
    {
        function treatment($cp): array
        {
            $creator=$cp->getComplaintCreator();
            $avatar=$creator->getAvatar();
            $firstname=$creator->getFirstname();
            $lastname=$creator->getLastname();
            return array(
                'id'=>$cp->getId(),
                'type' => $cp->getComplaintType(),
                'state'=>$cp->getIsTreated(),
                'description'=>$cp->getComplaintDescription(),
                'date'=>$cp->getCreatedAt()->format('Y-m-d H:i:s'),
                'fname'=>$firstname,
                'lname'=>$lastname,
                'avatar'=>$avatar
            );
        }
        $idx=0;
        $complaints=array();
        $comps=new ArrayCollection();
        if($this->isGranted('ROLE_DOCTOR')) {
            $comps->add($this->getUser()->getComplaints());
            $pats = $this->getUser()->getDoctor();
            foreach ($pats as $pt) {
                $temp = $pt->getComplaints();
                if (!$temp->isEmpty()) {
                    $comps->add($temp);
                }
            }
            foreach ($comps as $cmp) {
                foreach ($cmp as $cp) {
                    $complaints[$idx++] = treatment($cp);
                }
            }
        }
        elseif ($this->isGranted('ROLE_ADMIN')){
            $comps=$comp_rp->findAll();
            foreach($comps as $cmp){
                $complaints[$idx++] = treatment($cmp);
            }
        }
        else{
            $comps=$this->getUser()->getComplaints();
            foreach($comps as $cmp){
                $complaints[$idx++] = treatment($cmp);
            }
        }
        return new JsonResponse($complaints);
    }


    /**
     * @Route("/complaint/{id}/edit", name="complaint_edit", methods={"GET","POST"})
     * @param Request $request
     * @param ComplaintRepository $comprp
     * @param UserRepository $urp
     * @param $id
     * @return Response
     */
    public function complaint_edit(Request $request,ComplaintRepository $comprp,UserRepository $urp,$id): Response
    {
        $comp=$comprp->find($id);
        $treat=true;
        $entityManager=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        if($comp->getComplaintCreator()->getId() == $user->getId()){
            $treat=false;
        }
        $complaintForm = $this->createForm(ComplaintsType::class,$comp);
        $complaintForm->handleRequest($request);
        if($complaintForm->isSubmitted() && $complaintForm->isValid()){
            $comp->setUpdated();
            $entityManager->flush();
            $this->addFlash(
                'warning',
                "La réclamation a bien été modifiée."
            );
            return $this->redirectToRoute('complaint_home');
        }
        return $this->render('complaint/edit.html.twig', [
            'form' => $complaintForm->createView(),
            'treat'=>$treat
        ]);
    }
    /**
     * @Route("/complaint/{id}/delete", name="complaint_delete", methods={"POST"})
     * @param Request $request
     * @param UserRepository $urp
     * @return Response
     */
    public function delete_complaint(Request $request,ComplaintRepository $comprp,$id): Response
    {
        $complaint = $comprp->find($id);
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($complaint);
        $entityManager->flush();
        $this->addFlash(
                'danger',
                "La réclamation a bien été supprimée."
            );
            return $this->redirectToRoute('complaint_home');
    }
}
