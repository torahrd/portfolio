<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailgunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-mailgun {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mailgun email sending';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?? 'test@example.com';
        
        $this->info("Testing Mailgun email sending to: {$email}");
        $this->info("Domain: " . config('services.mailgun.domain'));
        $this->info("Endpoint: " . config('services.mailgun.endpoint'));
        
        try {
            Mail::raw('This is a test email from TasteRetreat via Mailgun.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('TasteRetreat - Mailgun Test')
                        ->from('noreply@taste-retreat.com', 'TasteRetreat');
            });
            
            $this->info('✅ Email sent successfully!');
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            
            // 詳細なエラー情報を提供
            if (str_contains($e->getMessage(), '401') || str_contains($e->getMessage(), 'Forbidden')) {
                $this->error('💡 401 Forbidden エラーの可能性のある原因:');
                $this->error('   1. APIキーが間違っている');
                $this->error('   2. ドメインが未検証');
                $this->error('   3. サンドボックスドメインの制限（承認されたメールアドレスのみ）');
                $this->error('   4. ドメインとAPIキーの組み合わせが正しくない');
            }
            
            return Command::FAILURE;
        }
    }
} 