<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Todo; // Entidad Todo para gestionar lista Todo
use Symfony\Component\HttpFoundation\Request; // Para recoger las peticiones 
use Symfony\Component\HttpFoundation\Session\Session; //Para crear sesión

class TodoController extends AbstractController{
    
    public function index(){
        return $this->render('todo/index.html.twig');
    }
    
    
    public function formulario_add(){
        return $this->render('todo/formulario_add.html.twig');
    }
    
    
    
    
    
    public function save(Request $request){
        //Recogemos datos que nos llegan por el formulario
        $nombre = $request->get("nombre");
        $estado=$request->get("estado");
        
        //Obtenemos la fecha que nos llega del formulario y como llega tipo string pasamos 
        //de string a tipo datetime ya que fechatope es tipo datetime
        $fechatope= new \DateTime($request->get("fechatope"));
        
        
        
        //Cargo entity manager 
        $em = $this->getDoctrine()->getManager();
        
        //Creo el objeto 
        $todo=new Todo();
        
        //Asigno valores 
        $todo->setNombre($nombre);
        
        //Asignamos automaticamente la fecha de creación al dia que se crea
        $todo->setFechaCreacion(new \Datetime('now'));
        $todo->setFechaTope($fechatope);
        $todo->setEstado($estado);
        
        //Persistir(Guardar objeto en doctrine)
        $em->persist($todo);
        
        //Volcar los datos en la tabla
        $em->flush();
        
        //Creamos sesión para informar de que se ha guardado correctamente
        $session=new Session();
        $session->getFlashBag()->add('message-exito','Tarea creda correctamente en lista');
        
        //Redirecciono a la Ruta de Inicio
        return $this->redirectToRoute('todo_index');
            
    }
    
    
    public function visualizarlistado(){
        //Cargo repositorio
        $todo_repo=$this->getDoctrine()->getRepository(Todo::class);
        
        //Guardamos todos los registros de la lista en listatodo ordenado por id descendente
        $listatodo=$todo_repo->findBy([],['id'=>'DESC']);
        
        
        return $this->render('todo/listado.html.twig',[
            'listatodo'=>$listatodo
        ]);
    }
    
    
    
}
