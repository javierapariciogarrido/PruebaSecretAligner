<?php

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Todo;


class AgregarElementosCommand extends Command 
{
    //Nombre del comando
    protected static $defaultName = 'agregar_elementos';
    protected static $defaultDescription = 'Agrega elemento a la TODO list';

    protected function configure(): void
    {
        /*
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;*/
        $this->addArgument('nombre',InputArgument::REQUIRED,'Nombre del usuario.')
             ->addArgument('estado',InputArgument::REQUIRED,'Estado de la tarea')
             ->addArgument('fechatope',InputArgument::OPTIONAL,'Fecha tope a realizar tarea');   
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

        /*
        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }
        
        if ($input->getOption('option1')) {
            // ...
        }
        */
        
        //Cargo entity manager 
        $em =$this->getDoctrine()->getManager();
        
        //Creo el objeto 
        $todo=new Todo();
        
        //Recojo valores de parámetros 
        $nombre = $input->getArgument('nombre');
        $fechatope=$input->getArgument('fechatope');
        $estado=$input->getArgument('estado');
        
        //Si hemos introducido fechatope
        if(!empty($fechatope)){
            $fechatopeconvertida=new \DateTime($fechatope);//Pasamos a objeto datetime 
        }else{//Si no introducimos nada asigno null
            $fechatopeconvertida=null;
        }
        
        
        
        //Asignamos valores al objeto
        $todo->setNombre($nombre);
        
        //Asignamos automaticamente la fecha de creación al dia que se crea
        $todo->setFechaCreacion(new \Datetime('now'));
        
        $todo->setFechaTope($fechatopeconvertida);
        $todo->setEstado($estado);
        
        //Persistir(Guardar objeto en doctrine)
        $em->persist($todo);
        
        //Volcar los datos en la tabla
        $em->flush();
        
        //Damos mensaje de éxito       
        //$output->writeln(['Hola','Soy yo']);
        //$output->writeln('Nombre de usuario: '.$input->getArgument('nombre'));
        
        $io->success('Tarea creada correctamente');

        return Command::SUCCESS;
    }
}
