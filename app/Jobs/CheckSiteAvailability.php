<?php

namespace App\Jobs;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use NotificationChannels\Telegram\Telegram;

class CheckSiteAvailability implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siteIds;

    public function __construct(array $siteIds)
    {
        $this->siteIds = $siteIds;
    }

    public function handle(Telegram $telegram)
    {
        // info("Job CheckSiteAvailability executado em " . now());

        $sites = Site::find($this->siteIds);

        foreach ($sites as $site) {
            $response = Http::get($site->url);
            $responseCode = $response->status();

            $this->updateSiteStatus($site->url, $responseCode);

            if ($responseCode < 400) {
                $this->handleSiteAvailable($telegram, $site->url);
            } else {
                $this->handleSiteUnavailable($telegram, $site->url, $responseCode);
            }
        }
    }

    protected function handleSiteUnavailable($telegram, $siteUrl, $responseCode)
    {
        if ($this->hasStatusChanged($siteUrl)) {
            $this->notifyTelegram($telegram, "🚨 Erro {$responseCode} 🚨\nAtenção: O portal {$siteUrl} está indisponível!");
            Cache::put("unavailable_site:{$siteUrl}", now());
        }
    }

    protected function handleSiteAvailable($telegram, $siteUrl)
    {
        if ($this->wasSiteUnavailable($siteUrl)) {
            $startTime = Cache::get("unavailable_site:{$siteUrl}");
            $endTime = now();
            $duration = $endTime->diffInMinutes($startTime);
            $this->notifyTelegram($telegram, "✅ Site $siteUrl está disponível novamente!\nTempo de indisponibilidade: {$duration} minutos");
            Cache::forget("unavailable_site:{$siteUrl}");
        }
    }

    protected function updateSiteStatus($siteUrl, $status)
    {
        $previousStatus = Cache::get("site_status:$siteUrl");
        Cache::put("site_status:$siteUrl", $status);
        Cache::put("previous_status:$siteUrl", $previousStatus);
    }

    protected function hasStatusChanged($siteUrl)
    {
        $previous = Cache::get("previous_status:$siteUrl");
        $current = Cache::get("site_status:$siteUrl");

        return $previous != $current;
    }

    protected function wasSiteUnavailable($siteUrl)
    {
        return Cache::has("unavailable_site:{$siteUrl}");
    }

    protected function notifySiteAvailable($telegram, $siteUrl)
    {
        $this->notifyTelegram(
            $telegram,
            "Site $siteUrl está disponível novamente!"
        );

        Cache::forget("unavailable_site:{$siteUrl}");
    }

    protected function notifyTelegram(Telegram $telegram, $message)
    {
        $telegram->sendMessage([
            'chat_id' => config('services.telegram-bot-api.chat_id'),
            'text' => $message,
        ]);
    }
}
