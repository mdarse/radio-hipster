<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application('My Silex Application', 'n/a');


$console->register( 'shift' )
  ->setDefinition( array(/*Add all options*/) )
  ->setDescription('Shift the item in the playlist to pass to the next song')
  ->setHelp('Usage: <info>./console.php sync [--test]</info>')
  ->setCode(
    function(InputInterface $input, OutputInterface $output) use ($app)
    {
        //\RH\Playlist::shift();
    }
  );
  
  
return $console;
