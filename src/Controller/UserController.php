<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;  // Entidad usuario
use App\Form\RegisterType; //Clase del formulario de registro
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; //Codificar contraseña
use Symfony\Component\HttpFoundation\Session\Session;


class UserController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder) {
        // Instanciamos modelo usuario
        $user = new User();

        //Creamos el formulario
        $form = $this->createForm(RegisterType::class, $user);

        //Vinculamos(seteando) lo que llega por post(request) con modelo user
        $form->handleRequest($request);

        //Comprobamos si el formulario se ha enviado
        if ($form->isSubmitted() && $form->isvalid()) {
            // Comprobar si el email el cual nos queremos registrar existe ya en la base de datos
            $user_repo = $this->getDoctrine()->getRepository(User::class);
            $emailusuario = $user_repo->findBy(['email' => $user->getEmail()]);


            // Aqui comprobamos si no existe otro usuario que tenga el mismo email
            $coincidencias = count($emailusuario);

            if ($coincidencias == 0) {
                //Setemamos campo Role manualmente:
                $user->setRole('ROLE_USER');

                //Seteamos manualmente la fecha de registro del usuario a la de hoy
                $user->setCreatedAt(new \Datetime('now'));

                //Cifrar contraseña
                $encoded = $encoder->encodePassword($user, $user->getPassword());
                // Seteo la contraseña cifrada

                $user->setPassword($encoded);

                //Guardamos usuario ya que todos los campos de usuario estan rellenos
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // Creamos sesión flash informando que el usuario ha sifo registrado
                $session = new Session();
                $session->getFlashBag()->add('message', '¡¡USUARIO REGISTRADO CORRECTAMENTE,YA PUEDES INICIAR SESION!!');
            } else { // Si existe el email que metemos para registrarnos nos avisa que no podemos
                $session = new Session();
                $session->getFlashBag()->add('error-login', '¡¡NO PUEDES REGISTRARTE CON ESE EMAIL PORQUE OTRO USUARIO YA LO TIENE!!');
            }




            //Redirigimos
            return $this->redirectToRoute('login');
        }// Fin if

        return $this->render('user/register.html.twig', [
                    'form' => $form->createView()
        ]);
    }
    
    public function login(AuthenticationUtils  $autenticationUtils){
        $error=$autenticationUtils->getLastAuthenticationError();
        $lastUsername=$autenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig',[
           'error'=>$error,
            'last_username'=>$lastUsername
        ]);
    }
    
    
    
}
