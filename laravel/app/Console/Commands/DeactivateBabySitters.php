<?php

namespace App\Console\Commands;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\BabySitter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateBabySitters extends Command
{
    private INotification $notification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deactivate:baby_sitters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @param INotification $notification
     */
    public function __construct(INotification $notification)
    {
        parent::__construct();
        $this->notification = $notification;
    }

    /**
     * Execute the console command.
     *
     * @param INotification $notification
     * @return int
     */
    public function handle()
    {
        try {
            $timestamp = now()->subDays(2);
            $baby_sitters = BabySitter::where('updated_at', '<=', $timestamp)->get();
            foreach ($baby_sitters as $baby_sitter) {
                $baby_sitter->is_active = false;
                $baby_sitter->save();
                $this->notification->notify(
                    [],
                    'Aktifliğiniz durdurulmuştur.',
                    'Hesabınıza 48 saat giriş yapmadığınız için aktifliğiniz durdurulmuştur.', $baby_sitter->phone);

            }
        } catch (\Exception $exception) {
            Log::info('Messaging exception', $exception);
        }


    }
}
