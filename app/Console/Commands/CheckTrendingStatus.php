<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTrendingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-trending-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for trends that are gaining traction again';

    public function handle()
    {
        // Trends older than 3 days
        $oldTrends = \App\Models\Trend::where('created_at', '<', now()->subDays(3))->get();
        $notificationService = new \App\Services\NotificationService();

        foreach ($oldTrends as $trend) {
            // Check for recent likes in the last hour
            $recentLikes = $trend->likes()->where('created_at', '>=', now()->subHour())->count();
            
            // Check for recent comments in the last hour
            $recentComments = $trend->comments()->where('created_at', '>=', now()->subHour())->count();

            $velocity = $recentLikes + ($recentComments * 2);

            // If meaningful activity detected
            if ($velocity >= 5) {
                $this->info("Trend resurfacing: {$trend->title}");

                // Notify users who previously liked/commented but not recently
                $previousEngagers = $trend->likes()
                    ->where('created_at', '<', now()->subHour())
                    ->with('user')
                    ->get()
                    ->pluck('user')
                    ->unique('id');

                foreach ($previousEngagers as $user) {
                    $notificationService->send(
                        $user,
                        "Trending Again 🔥",
                        "A trend you liked, '{$trend->title}', is heating up again with new activity!",
                        "info",
                        $trend,
                        route('trends.show', $trend->id)
                    );
                }
            }
        }
    }
}
