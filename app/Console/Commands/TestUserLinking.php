<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProjectParticipant;
use App\Models\User;

class TestUserLinking extends Command
{
    protected $signature = 'project:test-linking {phone}';
    protected $description = 'Test if user with phone will be linked to projects';

    public function handle()
    {
        $phone = $this->argument('phone');
        
        // Проверяем, существует ли пользователь
        $user = User::where('phone', $phone)->first();
        
        if ($user) {
            $this->info("✓ User exists: {$user->name} (ID: {$user->id})");
        } else {
            $this->warn("User with phone {$phone} does not exist yet");
        }
        
        // Ищем участников без user_id с этим телефоном
        $participants = ProjectParticipant::whereNull('user_id')
            ->where('phone', $phone)
            ->get();
        
        if ($participants->isEmpty()) {
            $this->info("\nNo pending project invitations for this phone");
        } else {
            $this->info("\n✓ Found {$participants->count()} pending project invitation(s):");
            foreach ($participants as $p) {
                $project = \App\Models\Project::find($p->project_id);
                $this->line("  - Project: {$project->name} (ID: {$p->project_id})");
            }
            
            if (!$user) {
                $this->info("\n→ When user registers with phone {$phone}, they will automatically get access to these projects!");
            }
        }
        
        // Проверяем участников с user_id
        if ($user) {
            $linked = ProjectParticipant::where('user_id', $user->id)->get();
            if ($linked->count() > 0) {
                $this->info("\n✓ User is already linked to {$linked->count()} project(s):");
                foreach ($linked as $l) {
                    $project = \App\Models\Project::find($l->project_id);
                    $this->line("  - Project: {$project->name} (ID: {$l->project_id})");
                }
            }
        }
        
        return 0;
    }
}
