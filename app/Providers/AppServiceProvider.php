<?php

namespace App\Providers;
use App\Repositories\AuthRepository;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ChatRepository;
use App\Repositories\Contracts\ChatRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\GroupRepository;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\OnboardingRepository;
use App\Repositories\Contracts\OnboardingRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use App\Repositories\ProfileRepository;
use App\Repositories\Contracts\TemplateRepositoryInterface;
use App\Repositories\TemplateRepository;
use App\Repositories\Contracts\WaiverRepositoryInterface;
use App\Repositories\WaiverRepository;
use App\Repositories\Contracts\InviteRepositoryInterface;
use App\Repositories\InviteRepository;
use App\Repositories\Contracts\SalarySlipRepositoryInterface;
use App\Repositories\SalarySlipRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\TaskRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
{
    $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

    $this->app->bind(ChatRepositoryInterface::class, ChatRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class); // add this
    $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
            $this->app->bind(OnboardingRepositoryInterface::class, OnboardingRepository::class);
    $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
    $this->app->bind(TemplateRepositoryInterface::class, TemplateRepository::class);
    $this->app->bind(WaiverRepositoryInterface::class, WaiverRepository::class);
    $this->app->bind(InviteRepositoryInterface::class, InviteRepository::class);
$this->app->bind( SalarySlipRepositoryInterface::class, SalarySlipRepository::class );
   $this->app->bind( TaskRepositoryInterface::class, TaskRepository::class );

}
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
