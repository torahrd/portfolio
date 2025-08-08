<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailgunSandboxCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-mailgun-sandbox {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mailgun email sending using sandbox domain';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?? 'test@example.com';

        $this->info("Testing Mailgun sandbox email sending to: {$email}");
        $this->info('⚠️  IMPORTANT: Recipient must be authorized in Mailgun sandbox!');

        try {
            Mail::raw('This is a test email from TasteRetreat via Mailgun Sandbox.', function ($message) use ($email) {
                $message->to($email)
                    ->subject('TasteRetreat - Mailgun Sandbox Test')
                    ->from('noreply@taste-retreat.com', 'TasteRetreat');
            });

            $this->info('✅ Email sent successfully!');
            $this->info('📧 Check your inbox for the test email.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: '.$e->getMessage());

            // 詳細なエラー情報を提供
            if (str_contains($e->getMessage(), '401') || str_contains($e->getMessage(), 'Forbidden')) {
                $this->error('💡 401 Forbidden エラーの可能性のある原因:');
                $this->error('   1. APIキーが間違っている');
                $this->error('   2. 受信者がサンドボックスで承認されていない');
                $this->error('   3. サンドボックスドメイン名が間違っている');
                $this->error('   4. サンドボックス制限（最大5人の承認済み受信者のみ）');
            }

            return Command::FAILURE;
        }
    }
}
