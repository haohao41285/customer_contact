<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class getStaffCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getStaff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        try{
            $client = new \Google_Client();
            $client->setApplicationName('Contact App');
            $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setAuthConfig(public_path('credentials.json'));
            $sheets = new \Google_Service_Sheets($client);
            $spreadsheetId = '1UJhvzAkCVFw4ZAqYw6ip5mKuAN2B6wUPT9zSTfCfg7o';
            $range = 'A2:H';
            $data = $sheets->spreadsheets_values->get($spreadsheetId, $range);
            foreach($data->getValues() as $d){
                $arr[] = [
                    'name' => $d[1]." " .$d[2],
                    'position' => $d[3],
                    'email' => $d[4],
                    'phone' => $d[5]
                ];
                // $this->info($d[1]);
            }
            //Check Staff table
            $count = DB::table('staffs')->count();
            if($count == )

            // $this->info(var_export($data->getValues()), true );
        }catch(\Exception $e){
            \Log::info($e);
            
        }
        $this->info('ok');
    }
}
