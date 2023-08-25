<?php

namespace App\Console\Commands;

use App\Models\Soporte\Categoria;
use App\Models\Soporte\Status;
use App\Models\Soporte\Ticket;
use App\Models\User;
use App\Notifications\SoporteTimeNotification;
use GrahamCampbell\ResultType\Success;
use Illuminate\Console\Command;

class TictekTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticketTime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checando tiempo de ticket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        //traer todos los ticket
        $Tickets=Ticket::all();

        $notificacion=[
            'ticket_name'=>'juan',
        ];    
        
        foreach($Tickets as $ticket){
            $ticket->update
            (
                [
                    'status_id'=>1
                ]
            );
            $user=$ticket->user;

            $user->notify(new SoporteTimeNotification($notificacion));
        }
        

        return Command::SUCCESS;
        
    }
}
