<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function regiserAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //data validation:
            $usernameRegex = "/^\w{3,30}$/";
            $emailRegex = "/^(\w+)@(\w+)\.(\w+)$/";
            $passwordRegex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/";
            if (!preg_match($usernameRegex, $user->getUsername()) || !preg_match($emailRegex, $user->getEmail()) //invalid
                || !preg_match($passwordRegex, $user->getPassword()) && $user->getPhone() != "" && $user->getCity() != ""){
                return $this->redirectToRoute('register');
            }

            //check if username or email taken
            /** @var ArrayCollection $userList */
            $userList = $userRepo->findAll();
            if ($user->usernameRegistered($userList)) {
                return $this->render('register/register.html.twig', array(
                    'form' => $form->createView(),
                    'usernameTaken' => true,
                    'emailTaken' => false,
                    'username' => $user->getUsername(),
                    'email' => ''
                ));
            }
            if ($user->emailRegistered($userList)) {
                return $this->render('register/register.html.twig', array(
                    'form' => $form->createView(),
                    'usernameTaken' => false,
                    'emailTaken' => true,
                    'username' => '',
                    'email' => $user->getEmail()
                ));
            }

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            // Add Role
            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $userRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($userRole);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //login after registration
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));
            return $this->redirectToRoute('homepage');
        }
        return $this->render('register/register.html.twig', array(
            'form' => $form->createView(),
            'usernameTaken' => false,
            'emailTaken' => false,
            'username' => ''
        ));
    }
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();
        return $this->render('login/login.html.twig', array(
            'error' => $error,
            'username' => $username,
        ));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }
}
