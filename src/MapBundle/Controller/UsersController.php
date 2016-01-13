<?php namespace MapBundle\Controller;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use FOS\RestBundle\Controller\FOSRestController;
use MapBundle\Document\User;
use MapBundle\Form\Type\PasswordType;
use MapBundle\Form\Type\RegisterUserType;
use MapBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UsersController extends FOSRestController
{
    protected $dm;

    protected $repository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->dm = $registry->getManager();
        $this->repository = $registry->getRepository('MapBundle:User');
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   output="array<MapBundle\Document\User>"
     * )
     */
    public function getUsersAction()
    {
        $this->denyAccessUnlessGranted('view', new User);

        $users = $this->repository->findAll();
        $view = $this->view($users);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   output="MapBundle\Document\User",
     *   input="MapBundle\Form\Type\RegisterUserType"
     * )
     */
    public function postUsersAction(Request $request)
    {
        $user = new User;

        $this->denyAccessUnlessGranted('create', $user);

        $form = $this->createForm(new RegisterUserType, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $encoder = $this->container->get('security.password_encoder');
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $this->dm->persist($user);
            $this->dm->flush();
            //ToDo: hide internal data
            $view = $this->view($user);
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   output="MapBundle\Document\User",
     *   input="MapBundle\Form\Type\UserType"
     * )
     */
    public function putUserAction(Request $request, $id)
    {
        $user = $this->repository->find($id);

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $this->denyAccessUnlessGranted('edit', $user);

        $form = $this->createForm(new UserType, $user, array('method' => 'PUT'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->dm->flush();
            $view = $this->view();
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   input="MapBundle\Form\Type\PasswordType"
     * )
     */
    public function putUserPasswordAction(Request $request, $id)
    {
        $user = $this->repository->find($id);

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $this->denyAccessUnlessGranted('edit', $user);

        $form = $this->createForm(new PasswordType, $user, array('method' => 'PUT'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $encoder = $this->container->get('security.password_encoder');
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $this->dm->flush();
            $view = $this->view();
        } else {
            $view = $this->view($form, 400);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   output="MapBundle\Document\User"
     * )
     */
    public function getUserAction($id)
    {
        $this->denyAccessUnlessGranted('view', new User);

        if ($id === 'current') {
            $user = $this->get('security.context')->getToken()->getUser();
            $view = $user instanceof User ? $this->view($user) : $this->view(null);

            return $this->handleView($view);
        }

        $user = $this->repository->find($id);

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->handleView($this->view($user));
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   output="MapBundle\Document\User"
     * )
     */
    public function putUserAdminAction($id)
    {
        return $this->updateAdmin($id, true);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section = "users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   output="MapBundle\Document\User"
     * )
     */
    public function deleteUserAdminAction($id)
    {
        return $this->updateAdmin($id, false);
    }

    protected function updateAdmin($userId, $isAdmin)
    {
        $this->denyAccessUnlessGranted('edit_admin', new User);

        $user = $this->repository->find($userId);

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $user->setIsAdmin($isAdmin);
        $this->dm->flush();

        return $this->handleView($this->view($user));
    }
}
