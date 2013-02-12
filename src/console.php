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

//      This boucle is used to test if the script works
//      $test = true;
//        while ($test) {

      $contenu_array = null;
      $contenu_array = file('timeDir/time');
        
        if (!$fp = fopen("timeDir/time","w+")) {
            throw new Exception();
            exit;
        }
        
            
        
        if ($contenu_array != null)
        {
            $tab = explode('/', $contenu_array[0]);
            $times = explode(':', $tab[1]);
            $minutes = $times[0];
            $secondes = $times[1];
            $timstampToNotPass = $tab[0] + $secondes + 60*$minutes;

            
            if($timstampToNotPass> time())
            {
                fputs($fp, $tab[0] . '/' . $tab[1]);
            }
            else
            {
                RH\Playlist::shift();
                $song = \RH\Playlist::getSong();
                if($song == null)
                    exit;
                fputs($fp, time() . '/' . $song->getTime());
            }
        }
        else
        {
            $song = \RH\Playlist::getSong();
            fputs($fp, time() . '/' . $song->getTime());
        }
        fclose($fp);
//        }
    }
  );
  
  
return $console;
