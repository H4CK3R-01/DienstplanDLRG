<?php

namespace App\Mail;

use App\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ServicesList extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $tableheader = \App\Qualification::where('isservicedefault', true)->get();
        //get all services of next 2 month
        $services = Service::where([['date','>=', DB::raw('CURDATE()')], ['date', '<=', \Carbon\Carbon::today()->addMonth(2)]])->orderBy('date')->with('positions.qualification')->get();

        return $this->subject('Wachplan MES')->view('email.serviceslist')->with([
            'tableheader' => $tableheader,
            'services' => $services,
        ]);
    }
}