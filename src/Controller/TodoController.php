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
        $session->getFlashBag()->add('message','Tarea creda correctamente en lista');
        
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
    
    
    public function marcar_tarea_realizada($id){
        
        //Cargamos repositorio de todo
        $todo_repo= $this->getDoctrine()->getRepository(Todo::class);
        
        //Cargamos entity manager
        $em=$this->getDoctrine()->getManager();
        
        // Cargamos la tarea que queremos marcar como realizada
        $tarea_a_modificar=$todo_repo->find($id);
        
        //Comprobar si el objeto llega correctamente,si no llega damos error
        if(!$tarea_a_modificar){
            $session=new Session();
            $session->getFlashBag()->add('message','¡¡La tarea a modificar no existe!!');
        
        }else{ // Si la tarea existe
            //Asignamos el valor del campo estado al registro que queremos modificar
            $tarea_a_modificar->setEstado('realizada');

            //Persistimos en doctrine los datos que queremos modificar
            $em->persist($tarea_a_modificar);
            //Guardamos en la base de datos
            $em->flush();
            
            //Creamos mensaje con sesión
            $session=new Session();
            $session->getFlashBag()->add('message','¡¡Has puesto la  tarea '.$tarea_a_modificar->getNombre(). ' como realizada correctamente!!');
        }
        
                
        //Redireccionamos a la página principal
        return $this->redirectToRoute('todo_index');
    }
    
    
    
    
}
