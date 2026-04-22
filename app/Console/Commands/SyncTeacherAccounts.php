<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncTeacherAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simad:sync-teacher-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing User accounts for existing teachers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teachers = \App\Models\Teacher::whereNull('user_id')->whereNotNull('email')->get();
        $this->info("Menemukan " . $teachers->count() . " guru tanpa akun login.");

        /** @var \App\Models\Teacher $teacher */
        foreach ($teachers as $teacher) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $teacher->email],
                [
                    'name' => $teacher->nama_lengkap,
                    'password' => \Illuminate\Support\Facades\Hash::make($teacher->nip ?? 'password123'),
                ]
            );

            $user->assignRole('guru');
            $teacher->update(['user_id' => $user->id]);
            
            $this->line("Dibuatkan akun untuk: " . $teacher->nama_lengkap);
        }

        $this->info("Sinkronisasi selesai.");
    }
}
